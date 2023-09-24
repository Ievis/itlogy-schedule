<?php

namespace App\Providers;

use App\Components\Container\Container;
use App\Components\Http\Request\Request;

class ServiceProvider implements ProviderInterface
{
    protected null|Request $request;
    public array $services = [];
    private array $providers;
    public Container $container;

    public function __construct(Container $container)
    {
        $this->request = $container->get(Request::class);
        $this->providers = require __DIR__ . '/../../config/providers.php';
        $this->container = $container;
    }

    public static function loadContainer(null|Request $request = null): Container
    {
        $container = new Container();
        if ($request) {
            $container->set(Request::class, $request);
        }
        $provider = new self($container);
        $provider->process();

        return $provider->getContainer();
    }

    public function process(): array
    {
        foreach ($this->providers as $provider) {
            $provider = new $provider($this->container);
            if (!$this->hasRequiredServices($provider)) {
                continue;
            }
            $this->services = array_merge($this->services, $provider->process());
        }

        return $this->services;
    }

    public function getContainer()
    {
        foreach ($this->services as $service) {
            $this->container->set($service::class, $service);
        }

        return $this->container;
    }

    public function collect(array $services)
    {
        $this->services = array_merge($this->services, $services);
    }

    public function requiredServices()
    {
        return [];
    }

    public function hasRequiredServices(ProviderInterface $provider)
    {
        foreach ($provider->requiredServices() as $service) {
            if (!$this->container->has($service)) {
                return false;
            }
        }

        return true;
    }
}