<?php

namespace App\Service;

use App\Components\Container\Container;
use App\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\ParameterBag;

class ControllerInfo
{
    public string $controller;
    public string $method;
    public array $parameters;

    public function __construct(array $route_parameters)
    {
        $controller_info = explode('::', $route_parameters['_controller']);
        $this->controller = $controller_info[0];
        $this->method = $controller_info[1];

        $this->parameters = $route_parameters['_parameters'];
    }

    public function getController()
    {
        return $this->controller;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getParameters()
    {
        return $this->parameters;
    }
}