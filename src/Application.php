<?php

namespace App;

use App\Controller\AbstractController;
use App\Resource\JsonResource;
use App\Service\ControllerInfo;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

class Application
{
    public array $config;
    public Request $request;
    public null|Response $response = null;
    public RouteCollection $routes;
    public ControllerInfo $controller_info;
    public AbstractController $controller;

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
        if ($this->response instanceof Response) {
            return $this->response;
        }

        $controller = $this->controller_info->getController();
        $method = $this->controller_info->getMethod();
        $content = $controller->$method();
        $headers = [];

        if ($content instanceof JsonResource) {
            $headers['Content-Type'] = 'application/json';
            $content = $content->getJson();
        }

        return new Response($content, 200, $headers);
    }

    public function terminate(Request $request, Response $response)
    {
        $response->send();
    }
}