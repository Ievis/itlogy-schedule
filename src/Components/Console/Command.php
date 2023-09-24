<?php

namespace App\Components\Console;

class Command implements CommandInterface
{
    public null|string $prop;

    public function __construct(null|string $prop = null)
    {
        $this->prop = $prop;
    }

    public function setProp($prop)
    {
        $this->prop = $prop;
    }

    public function getProp()
    {
        return $this->prop;
    }

    public function props()
    {
        return [];
    }

    public function name()
    {
        return '';
    }
}