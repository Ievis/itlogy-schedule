<?php

namespace App\Components\Route;

use App\Components\Http\Exception\MethodNotAllowedHttpException;
use App\Components\Http\Request\Request;
use App\Components\Http\Exception\NotFoundHttpException;

class Route
{
    public string $uri;
    public string $method;
    public array $routes;
    public array $parameters = [];
    public array $matched_route = [];

    public function __construct(array $routes, Request $request)
    {
        $this->uri = $request->getRequestUri();
        $this->method = $request->getRequestMethod();
        $this->routes = $routes;

        $this->removeQueryParams();
    }

    public function match()
    {
        $this->matchRoutes();
        return $this->getRouteInfo();
    }

    public function matchRoutes()
    {
        $uri = $this->explodeUri($this->uri);

        foreach ($this->routes as $route_name => $route) {
            $route = $this->explodeUri($route['path']);
            if ($this->matchExplodedUri($uri, $route)) {
                if ($this->matched_route) {
                    $this->matched_route[array_keys($this->matched_route)[0]] = $this->routes[$route_name];
                } else {
                    $this->matched_route[$route_name] = $this->routes[$route_name];
                }
                if ($this->method != $this->routes[$route_name]['method']) {
                    continue;
                }

                return;
            }
        }

    }

    public function matchExplodedUri(array $uri, array $route)
    {
        foreach ($route as $key => $value) {
            if (count($uri) != count($route)) {
                return false;
            }
            if (str_starts_with($value, '{')) {
                $parameter_name = substr($value, 1, strlen($value) - 2);
                $this->parameters[$parameter_name] = $uri[$key];
                $route[$key] = $uri[$key];
            }
        }

        return $uri == $route;
    }

    public function explodeUri(string $uri)
    {
        $exploded_uri = [];
        while (str_contains($uri, '/')) {
            $uri = substr($uri, strpos($uri, '/') + 1);
            $next_slash_position = strpos($uri, '/');
            $uri_element = substr($uri, 0);
            if ($next_slash_position) {
                $uri_element = substr($uri, 0, $next_slash_position);
            }
            $exploded_uri[] = $uri_element;
        }

        return $exploded_uri;
    }

    public function removeQueryParams()
    {
        $query_position = strpos($this->uri, '?');
        $query_position = $query_position == 0
            ? strlen($this->uri)
            : $query_position;
        $this->uri = substr($this->uri, 0, $query_position);
    }

    public function getRouteInfo(): array
    {
        if (empty($this->matched_route)) {
            throw new NotFoundHttpException();
        }
        $route_name = array_keys($this->matched_route)[0];
        $route = $this->matched_route[$route_name];
        if ($this->method != $route['method']) {
            throw new MethodNotAllowedHttpException();
        }

        return [
            '_route' => $route_name,
            '_controller' => $route['controller'],
            '_parameters' => $this->parameters
        ];
    }
}