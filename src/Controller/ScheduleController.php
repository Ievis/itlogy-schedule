<?php

namespace App\Controller;

use App\Entity\Repository\UserRepository;
use App\Entity\Schedule;
use App\Entity\User;
use App\Resource\ScheduleResource;
use Symfony\Component\HttpFoundation\Request;

class ScheduleController extends AbstractController
{
    public function show(Request $request, UserRepository $repository)
    {
//        $user = new User([
//            'email' => '111@mail.ru',
//            'first_name' => '111',
//            'last_name' => '222',
//            'surname' => '333',
//            'phone' => '444',
//            'role' => 'teacher'
//        ]);

        $student = $this->em->createQueryBuilder()
            ->select('u')
            ->from(User::class, 'u')
            ->where('u.role = ?1')
            ->setParameter(1, 'student')
            ->getQuery()
            ->getResult();
        dd($request, $repository->findAll());
        $schedule = new Schedule([

        ]);

        return new ScheduleResource();
    }
}