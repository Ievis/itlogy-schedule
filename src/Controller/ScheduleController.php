<?php

namespace App\Controller;

use App\Components\Http\Request\Request;
use App\Components\Http\Response\RedirectResponse;
use App\Entity\Repository\ScheduleRepository;
use App\Entity\Repository\UserRepository;
use App\Exception\ValidationException;
use App\Service\ScheduleValidationService;
use App\View\View;

class ScheduleController extends AbstractController
{
    public function show(UserRepository $repository)
    {
        $students = $repository->getByRole('student');
        $teachers = $repository->getByRole('teacher');

        return new View('schedules_create', [
            'students' => $students,
            'teachers' => $teachers
        ]);
    }

    public function create(Request $request, ScheduleRepository $schedule_repository, UserRepository $user_repository)
    {
        $validation = new ScheduleValidationService([
            'will_at' => $request->post('will_at'),
            'student' => $user_repository->find($request->post('student_id') ?? 0),
            'teacher' => $user_repository->find($request->post('teacher_id') ?? 0),
        ], $schedule_repository);
        try {
            $data = $validation->validated();
        } catch (ValidationException $e) {
            return $e->getResponse();
        }

        $schedule_repository->insert([
            'will_at' => ((array)$data['will_at'])['date'],
            'student_id' => $data['student']['user_id'],
            'teacher_id' => $data['teacher']['user_id'],
        ]);
        $_SESSION['message'] = 'Занятие запланировано';
        return new RedirectResponse('/schedules/create');
    }
}