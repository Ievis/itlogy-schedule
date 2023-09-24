<?php

return [
    'schedules_show' => [
        'path' => '/',
        'controller' => 'App\Controller\MainController::index',
        'method' => 'GET'
    ],
    'schedules_create' => [
        'path' => '/schedules/create',
        'controller' => 'App\Controller\ScheduleController::show',
        'method' => 'GET'
    ],
    'schedules_create_action' => [
        'path' => '/schedules/create',
        'controller' => 'App\Controller\ScheduleController::create',
        'method' => 'POST'
    ]
];