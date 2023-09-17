<?php

namespace App\Providers;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

class RouteServiceProvider extends ServiceProvider implements ProviderInterface
{
    public function process(): array
    {
        $loader = new YamlFileLoader(new FileLocator(__DIR__ . '/../../config'));
        $routes = $loader->load('routes.yml');
        $context = (new RequestContext())->fromRequest($this->request);
        $matcher = new UrlMatcher($routes, $context);

        return [$matcher, $context];
    }

}