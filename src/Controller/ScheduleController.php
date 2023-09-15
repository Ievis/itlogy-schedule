<?php

namespace App\Controller;

use App\Resource\ScheduleResource;

class ScheduleController extends AbstractController
{
    public function show()
    {
        return new ScheduleResource();
    }
}