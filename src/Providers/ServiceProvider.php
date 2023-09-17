<?php

namespace App\Providers;

use App\Entity\Entity;
use App\View\View;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Yaml\Yaml;

class ServiceProvider implements ProviderInterface
{
    protected Request $request;
    public array $services = [];
    private array $providers;
    private ContainerBuilder $container;

    public function __construct(ContainerBuilder $container, Request $request)
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