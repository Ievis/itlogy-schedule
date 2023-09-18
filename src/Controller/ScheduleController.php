<?php

namespace App\Controller;

use App\Entity\Repository\ScheduleRepository;
use App\Entity\Repository\UserRepository;
use App\Exception\ValidationException;
use App\Service\ScheduleValidationService;
use App\View\View;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

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
            'will_at' => $request->request->get('will_at'),
            'student' => $user_repository->find($request->request->get('student_id')),
            'teacher' => $user_repository->find($request->request->get('teacher_id')),
        ], $schedule_repository);
        try {
            $data = $validation->validated();
        } catch (ValidationException $e) {
            return $e->getResponse();
        }

        $schedule_repository->insert($data);
        $_SESSION['message'] = 'Занятие запланировано';
        return new RedirectResponse('/schedules/create');
    }
}