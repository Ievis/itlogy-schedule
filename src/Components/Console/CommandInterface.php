<?php

namespace App\Components\Console;

interface CommandInterface
{
    public function name();

    public function props();
}