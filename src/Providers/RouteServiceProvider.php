<?php

namespace App\Providers;

use App\Components\Http\Request\Request;
use App\Components\Route\Route;

class RouteServiceProvider extends ServiceProvider implements ProviderInterface
{
    public function requiredServices()
    {
        return [
            Request::class,
        ];
    }
    public function process(): array
    {
        $routes = require __DIR__ . '/../../config/routes.php';
        $route = new Route($routes, $this->container->get(Request::class));
        $this->collect([$route]);

        return $this->services;
    }

}