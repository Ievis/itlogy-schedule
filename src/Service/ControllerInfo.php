<?php

namespace App\Service;

use App\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\ParameterBag;

class ControllerInfo
{
    public string $controller;
    public string $method;
    public array $parameters;
    public array $reflection_parameters;

    public function __construct(array $route_parameters)
    {
        $controller_info = explode('::', $route_parameters['_controller']);

        $this->controller = $controller_info[0];
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

    public function setReflectionParameters(ContainerBuilder $container_builder)
    {
        $this->reflection_parameters = $container_builder
            ->getReflectionClass($this->controller)
            ->getMethod($this->method)
            ->getParameters();
    }

    public function getReflectionParameters()
    {
        return $this->reflection_parameters;
    }
}