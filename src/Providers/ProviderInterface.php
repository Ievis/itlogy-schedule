<?php

namespace App\Providers;

interface ProviderInterface
{
    public function process(): array;
}