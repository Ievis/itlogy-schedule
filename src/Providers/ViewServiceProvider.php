<?php

namespace App\Providers;

use App\View\View;

class ViewServiceProvider extends ServiceProvider implements ProviderInterface
{
    public function process(): array
    {
        return [new View()];
    }
}