<?php

namespace App\Components\Container;

use App\Components\Container\Exception\ParameterLengthsNotEqualException;
use App\Components\Container\Exception\ParameterNotFoundException;
use ReflectionClass;
use ReflectionMethod;

class Container
{
    public array $services = [];
    public array $definitions = [];

    public function set(string $service_id, object $service)
    {
        $this->services[$service_id] = $service;
    }

    public function get(string $service_id)
    {
        $definition = $this->getDefinition($service_id);
        if ($definition) {
            $instance = $definition->getInstance();
            return $definition->getMethodExecution($instance);
        }

        return $this->services[$service_id];
    }

    public function getReflectionClass(string $service)
    {
        return new ReflectionClass($service);
    }

    public function has(string $service_id)
    {
        return in_array($service_id, array_keys($this->services));
    }

    public function setDefinition(string $definition_id, Definition $definition)
    {
        $this->definitions[$definition_id] = $definition;
    }

    public function getDefinition(string $service_id)
    {
        foreach ($this->definitions as $definition) {
            if ($definition->getServiceName() == $service_id) {
                return $definition;
            }
        }

        return null;
    }
}