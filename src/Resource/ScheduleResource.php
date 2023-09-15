<?php

namespace App\Resource;

class ScheduleResource extends JsonResource
{
    public function __construct()
    {
        parent::__construct();
        $this->toArray();
    }

    public function toArray()
    {
        $this->data = [
            'success' => true,
            'message' => 'Ok!'
        ];
    }
}