<?php

namespace App\Service;

use App\Controller\AbstractController;

class ControllerInfo
{
    public AbstractController $controller;
    public string $method;
    public array $vars;

    public function __construct(array $route_parameters)
    {
        $controller_info = explode('::', $route_parameters['_controller']);

        $this->controller = new $controller_info[0];
        $this->method = $controller_info[1];

        $this->vars = array_diff($route_parameters, [
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

    public function getVars()
    {
        return $this->vars;
    }
}