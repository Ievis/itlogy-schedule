<?php

namespace App\Providers;

use App\Components\Route\Route;

class RouteServiceProvider extends ServiceProvider implements ProviderInterface
{
    public function process(): array
    {
        $routes = require __DIR__ . '/../../config/routes.php';
        $route = new Route($routes, $this->request);

        return [$route];
    }

}