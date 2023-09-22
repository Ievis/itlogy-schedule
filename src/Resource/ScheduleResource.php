<?php

namespace App\Resource;

use App\Entity\Entity;
use App\Entity\Schedule;

class ScheduleResource extends JsonResource
{
    public function __construct(Schedule $entity)
    {
        parent::__construct($entity);
        $this->toArray();
    }

    public function toArray()
    {
        $this->data = [
            'id' => $this->entity->getId(),
            'will_at' => $this->entity->getWillAt()
        ];
    }
}