<?php

namespace App\Controller;

use App\Entity\Repository\ScheduleRepository;
use Symfony\Component\HttpFoundation\Request;

class ScheduleController extends AbstractController
{
    public function create(Request $request, ScheduleRepository $repository)
    {
        dd($request->request->get('name'));
    }
}