<?php

namespace App;

use App\Components\Container\Container;
use App\Components\Container\Definition;
use App\Components\Http\Request\Request;
use App\Components\Http\Response\RedirectResponse;
use App\Components\Http\Response\Response;
use App\Components\Route\Route;
use App\Providers\ServiceProvider;
use App\Resource\JsonResource;
use App\Service\ControllerInfo;
use App\View\View;
use App\Components\Http\Exception\MethodNotAllowedHttpException;
use App\Components\Http\Exception\NotFoundHttpException;
use PDO;

class Application
{
    public Container $container;
    public Request $request;
    public ControllerInfo $controller_info;
    public null|Response $response = null;
    public RedirectResponse|View|JsonResource $content;

    public function __construct(Request $request)
    {
        $this->setConfig();
        $this->setRequest($request);
        $this->loadContainer();
        $this->getControllerInfo();
    }

    private function loadContainer()
    {
        $this->container = ServiceProvider::loadContainer($this->request);
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
        $route = $this->container->get(Route::class);

        try {
            $route_parameters = $route->match();
        } catch (NotFoundHttpException) {
            $this->response = new Response('Not found!', 404);
            return;
        } catch (MethodNotAllowedHttpException) {
            $this->response = new Response('Method not allowed!', 405);
            return;
        }

        $this->controller_info = new ControllerInfo($route_parameters);
    }

    public function handle(): Response
    {
        if ($this->hasResponse()) {
            return $this->response;
        }
        $this->registerControllerDefinition();
        $this->content = $this->container->get($this->controller_info->getController());

        if ($this->content instanceof RedirectResponse) {
            $this->response = $this->content;

            return $this->response;
        }

        return new Response();
    }

    private function registerControllerDefinition()
    {
        $controller = $this->controller_info->getController();
        $method = $this->controller_info->getMethod();
        $parameters = $this->controller_info->getParameters();

        $definition = new Definition($controller);
        $definition->addMethodCall($method, $parameters);
        $this->container->setDefinition($controller, $definition);
    }

    public function terminate(Response $response)
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

        return $headers;
    }

    public function getContent()
    {
        ob_start();
        return $this->expectsJson()
            ? $this->content->getJson()
            : $this->content->getHtml();
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