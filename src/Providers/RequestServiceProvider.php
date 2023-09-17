<?php

namespace App\Providers;

class RequestServiceProvider extends ServiceProvider implements ProviderInterface
{
    public function process(): array
    {
        return [$this->request];
    }
}