<?php

namespace App\Resource;

use App\Entity\Entity;

class JsonResource
{
    public Entity $entity;
    public array $data;

    public function __construct(Entity $entity)
    {
        $this->entity = $entity;
    }

    public function getJson()
    {
        return json_encode($this->data);
    }
}