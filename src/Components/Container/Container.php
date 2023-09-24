<?php

namespace App\Components\Container;

use ReflectionClass;

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
            $this->provideServices($definition);
            $instance = $definition->getInstance();
            return $definition->getMethodExecution($instance);
        }

        return $this->services[$service_id] ?? null;
    }

    private function provideServices(Definition $definition)
    {
        $definition->appendParameters($this->getParameters($definition));
        if ($definition->getMethodCall()) {
            $definition->appendMethodCallParameters($this->getParameters($definition, $definition->getMethodCall()['method']));
        }

        return $definition;
    }

    public function getParameters(Definition $definition, string $method = '__construct')
    {
        $parameters = [];

        $reflection_parameters = $this->getReflectionParameters($definition, $method);
        foreach ($reflection_parameters as $reflection_parameter) {
            if (empty($reflection_parameter->getType())) {
                continue;
            }
            $reflection_parameter_name = $reflection_parameter->getType()->getName();
            if ($this->has($reflection_parameter_name)) {
                $parameters[$reflection_parameter->getName()] = $this->get($reflection_parameter_name);
            }
        }

        return $parameters;
    }

    public function getReflectionClass(string $service)
    {
        return new ReflectionClass($service);
    }

    public function getReflectionParameters(Definition $definition, string $method = '__construct')
    {
        $reflection_class = $this->getReflectionClass($definition->getServiceName());
        if ($reflection_class->hasMethod($method)) {
            return $reflection_class->getMethod($method)->getParameters();
        }

        return [];
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