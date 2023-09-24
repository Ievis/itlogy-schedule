<?php

namespace App\Components\Container;

use App\Components\Container\Exception\ParameterLengthsNotEqualException;
use App\Components\Container\Exception\ParameterNotFoundException;
use ReflectionClass;
use ReflectionMethod;

class Definition
{
    public string $service;
    public array $call;
    public array $parameters;

    public function __construct(string $service, array $parameters = [])
    {
        $this->service = $service;
        $this->parameters = $parameters;
    }

    public function addMethodCall(string $method, array $parameters = [])
    {
        $this->call = [
            'method' => $method,
            'parameters' => $parameters
        ];
    }

    public function appendParameters(array $parameters)
    {
        $this->parameters = array_merge($this->parameters, $parameters);
    }

    public function appendMethodCallParameters(array $parameters)
    {
        if (!empty($this->call)) {
            $this->call['parameters'] = array_merge($this->call['parameters'], $parameters);
        }
    }

    public function getInstance()
    {
        $reflection_class = new ReflectionClass($this->getServiceName());
        if(!$reflection_class->hasMethod('__construct')) {
            return $reflection_class->newInstance();
        }
        $construct_info = $reflection_class->getMethod('__construct');
        $construct_parameters = $construct_info->getParameters();
        $parameters = $this->arrangeParameters($this->getParameters(), $construct_parameters);

        return $reflection_class->newInstanceArgs($parameters);
    }

    public function getMethodExecution(object $instance)
    {
        if (empty($this->call)) {
            return $instance;
        }
        $reflection_class = new ReflectionClass($this->getServiceName());
        $method_info = $reflection_class->getMethod($this->getMethodCall()['method']);
        $method_parameters = $method_info->getParameters();
        $parameters = $this->arrangeParameters($this->getMethodCall()['parameters'], $method_parameters);
        $method = new ReflectionMethod($instance::class, $this->getMethodCall()['method']);

        return $method->invokeArgs($instance, $parameters);
    }

    public function getServiceName()
    {
        return $this->service;
    }

    public function getMethodCall()
    {
        return $this->call;
    }

    public function getParameters()
    {
        return $this->parameters;
    }

    public function arrangeParameters(array $parameters, array $reflection_parameters)
    {
        if (count($parameters) != count($reflection_parameters)) {
            throw new ParameterLengthsNotEqualException();
        }

        $arranged_parameters = [];
        foreach ($parameters as $parameter_name => $parameter) {
            $position = $this->getParameterPosition($parameter_name, $reflection_parameters);
            $arranged_parameters[$position] = $parameter;
        }

        $parameters = [];
        for ($i = 0; $i < count($arranged_parameters); $i++) {
            $parameters[$i] = $arranged_parameters[$i];
        }

        return $parameters;
    }

    public function getParameterPosition(string $name, array $reflection_parameters)
    {
        foreach ($reflection_parameters as $parameter) {
            if ($name == $parameter->getName()) {
                return $parameter->getPosition();
            }
        }

        throw new ParameterNotFoundException();
    }
}