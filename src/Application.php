<?php

namespace App;

use App\Resource\JsonResource;
use App\Service\ControllerInfo;
use Symfony\Component\Config\Definition\Configuration;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class Application
{
    public Request $request;
    public RouteCollection $routes;
    public ControllerInfo $controller_info;
    public null|Response $response = null;
    public ResponseHeaderBag $headers;
    public string|JsonResource $content;

    public function __construct(Request $request)
    {
        $this->setConfig();

        $this->setRequest($request);
        $this->loadRoutes();
        $this->getControllerInfo();
    }

    private function setConfig()
    {
        new Config();
    }

    private function setRequest(Request $request)
    {
        $this->request = $request;
    }

    private function loadRoutes()
    {
        $file_locator = new FileLocator('../config');
        $loader = new YamlFileLoader($file_locator);

        $this->routes = $loader->load('routes.yml');
    }

    private function getControllerInfo()
    {
        $context = new RequestContext();
        $context->fromRequest($this->request);

        $matcher = new UrlMatcher($this->routes, $context);
        try {
            $matcher->match($context->getPathInfo());
        } catch (ResourceNotFoundException) {
            $this->response = new Response('Not found!');
            return;
        }

        $route_parameters = $matcher->match($context->getPathInfo());
        $this->controller_info = new ControllerInfo($route_parameters);
    }

    public function handle(): Response
    {
        if ($this->hasResponse()) {
            return $this->response;
        }
        $controller = $this->controller_info->getController();
        $method = $this->controller_info->getMethod();
        $parameters = $this->controller_info->getParameters();

        $container_builder = new ContainerBuilder();
        $definition = new Definition($controller::class);
        $definition->addMethodCall($method, $parameters, true);
        $container_builder->setDefinition($controller::class, $definition);

        try {
            $this->content = $container_builder->get($controller::class);
        } catch (ValidationFailedException) {
            $this->response = new Response('Validation errors');
            return $this->response;
        }

        return new Response();
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