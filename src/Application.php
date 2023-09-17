<?php

namespace App;

use App\Providers\ServiceProvider;
use App\Resource\JsonResource;
use App\Service\ControllerInfo;
use App\View\View;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\RecursiveValidator;

class Application
{
    public ContainerBuilder $container;
    public Request $request;
    public ControllerInfo $controller_info;
    public null|Response $response = null;
    public ResponseHeaderBag $headers;
    public string|JsonResource $content;

    public function __construct(Request $request)
    {
        $this->setConfig();
        $this->setRequest($request);
        $this->loadContainer();
        $this->getControllerInfo();
    }

    private function loadContainer()
    {
        $provider = new ServiceProvider(new ContainerBuilder(), $this->request);
        $provider->process();
        $this->container = $provider->getContainer();
    }

    private function setConfig()
    {
        new Config();
    }

    private function setRequest(Request $request)
    {
        $this->request = $request;
    }

    private function getControllerInfo()
    {
        $context = $this->container->get(RequestContext::class);
        $matcher = $this->container->get(UrlMatcher::class);

        try {
            $matcher->match($context->getPathInfo());
        } catch (ResourceNotFoundException) {
            $this->response = new Response('Not found!');
            return;
        }

        $route_parameters = $matcher->match($context->getPathInfo());
        $this->controller_info = new ControllerInfo($route_parameters);
        $this->controller_info->setReflectionParameters($this->container);
    }

    public function handle(): Response
    {
        if ($this->hasResponse()) {
            return $this->response;
        }
        $this->registerControllerDefinition();

        try {
            $this->content = $this->container->get($this->controller_info->getController());
        } catch (ValidationFailedException) {
            $this->response = new Response('Validation errors');
            return $this->response;
        }

        return new Response();
    }

    private function registerControllerDefinition()
    {
        $controller = $this->controller_info->getController();
        $method = $this->controller_info->getMethod();
        $parameters = $this->controller_info->getParameters();
        $reflection_parameters = $this->controller_info->getReflectionParameters();

        $definition = new Definition($controller, [
            'em' => $this->container->get(EntityManager::class),
            'validator' => $this->container->get(RecursiveValidator::class),
            'view' => $this->container->get(View::class)
        ]);
        $this->container->setDefinition($controller, $definition);
        $this->provideControllerServices($parameters);

        $definition->addMethodCall($method, $parameters, true);
        $this->container->setDefinition($controller, $definition);
    }

    private function provideControllerServices(array &$parameters)
    {
        $reflection_parameters = $this->controller_info->getReflectionParameters();
        foreach ($reflection_parameters as $reflection_parameter) {
            $reflection_parameter = $reflection_parameter->getType()->getName();
            if ($this->container->has($reflection_parameter)) {
                $parameters[] = $this->container->get($reflection_parameter);
            }
        }
    }

    public function terminate(Request $request, Response $response)
    {
        if ($this->hasResponse()) {
            $this->response->send();
            return;
        }
        $response->headers = $this->getHeaders();
        $response->setContent($this->getContent());
        $response->setStatusCode(200);
        $response->send();
    }

    public function getHeaders()
    {
        $headers = [];
        $headers['Content-Type'] = $this->expectsJson()
            ? 'application/json'
            : 'text/html';

        return new ResponseHeaderBag($headers);
    }

    public function getContent()
    {
        return $this->expectsJson()
            ? $this->content->getJson()
            : $this->content;
    }

    public function expectsJson()
    {
        return $this->content instanceof JsonResource;
    }

    public function hasResponse()
    {
        return $this->response instanceof Response;
    }
}