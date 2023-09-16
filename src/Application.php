<?php

namespace App;

use App\Controller\AbstractController;
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
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Application
{
    public ContainerBuilder $container_builder;
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
        $this->loadContainer();
        $this->loadRoutes();
        $this->getControllerInfo();
    }

    private function loadContainer()
    {
        $this->container_builder = ServiceProvider::loadFromConfig(new ContainerBuilder(), $this->request);
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
        $this->routes = $this->container_builder->get(RouteCollection::class);
    }

    private function getControllerInfo()
    {
        $context = $this->container_builder->get(RequestContext::class);

        $matcher = new UrlMatcher($this->routes, $context);
        try {
            $matcher->match($context->getPathInfo());
        } catch (ResourceNotFoundException) {
            $this->response = new Response('Not found!');
            return;
        }

        $route_parameters = $matcher->match($context->getPathInfo());
        $this->controller_info = new ControllerInfo($route_parameters);
        $this->controller_info->setReflectionParameters($this->container_builder);
    }

    public function handle(): Response
    {
        if ($this->hasResponse()) {
            return $this->response;
        }
        $this->registerControllerDefinition();

        try {
            $this->content = $this->container_builder->get($this->controller_info->getController());
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
            'em' => $this->container_builder->get(EntityManager::class),
            'validator' => $this->container_builder->get(Validation::class),
            'view' => $this->container_builder->get(View::class)
        ]);
        $this->container_builder->setDefinition($controller, $definition);

        $this->expects(Request::class, $reflection_parameters, $parameters);
        $this->expects(EntityRepository::class, $reflection_parameters, $parameters);

        $definition->addMethodCall($method, $parameters, true);
        $this->container_builder->setDefinition($controller, $definition);

    }

    private function expects(string $class, array $reflection_parameters, array &$parameters)
    {
        foreach ($reflection_parameters as $reflection_parameter) {
            $parameter_class = $reflection_parameter->getType()->getName();
            $parameter_class = $this->container_builder->get($parameter_class);
            $requested_class = $this->container_builder->get($class);

            if ($parameter_class instanceof $requested_class) {
                $parameters[$reflection_parameter->getName()] = $parameter_class;
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