<?php

namespace App\Service;

use App\Controller\AbstractController;
use Symfony\Component\HttpFoundation\ParameterBag;

class ControllerInfo
{
    public AbstractController $controller;
    public string $method;
    public array $parameters;

    public function __construct(array $route_parameters)
    {
        $controller_info = explode('::', $route_parameters['_controller']);

        $this->controller = new $controller_info[0];
        $this->method = $controller_info[1];

        $this->parameters = array_diff($route_parameters, [
            '_route' => $route_parameters['_route'],
            '_controller' => $route_parameters['_controller']
        ]);
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