<?php

namespace App\Providers;

use App\Components\Container\Container;
use App\Components\Http\Request\Request;

class ServiceProvider implements ProviderInterface
{
    protected Request $request;
    public array $services = [];
    private array $providers;
    private Container $container;

    public function __construct(Container $container, Request $request)
    {
        $this->request = $request;
        $this->providers = require __DIR__ . '/../../config/providers.php';
        $this->container = $container;
    }

    public function process(): array
    {
        foreach ($this->providers as $provider) {
            $provider = new $provider($this->container, $this->request);
            $this->services = array_merge($this->services, $provider->process());
        }

        return $this->services;
    }

    public function getContainer()
    {
        foreach($this->services as $service) {
            $this->container->set($service::class, $service);
        }

        return $this->container;
    }

    public function collect(array $services)
    {
        $this->services = array_merge($this->services, $services);
    }
}