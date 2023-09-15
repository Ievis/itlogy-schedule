<?php

namespace App\Controller;

use App\Resource\ScheduleResource;

class ScheduleController extends AbstractController
{
    public function show()
    {
//        return new ScheduleResource();

        return $this->view->render('main', [
            'a' => 'I am a',
            'b' => 'I am b'
        ]);
    }
}