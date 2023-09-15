<?php

namespace App\Resource;

use App\Entity\Entity;

class JsonResource
{
    protected null|Entity $entity;
    public array $data;

    public function __construct(null|Entity $entity = null)
    {
        $this->entity = $entity;
    }

    public function getJson()
    {
        return json_encode($this->data);
    }
}