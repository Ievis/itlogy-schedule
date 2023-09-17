<?php

namespace App\Controller;

use App\Entity\Repository\ScheduleRepository;
use App\View\View;

class MainController extends AbstractController
{
    public function index(ScheduleRepository $repository)
    {
        $schedules = $repository->findAllWithUsers();

        return new View('main', [
            'schedules' => $schedules
        ]);
    }
}