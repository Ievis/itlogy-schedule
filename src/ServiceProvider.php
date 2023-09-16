<?php

namespace App;

use App\Entity\Entity;
use App\View\View;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Yaml\Yaml;

class ServiceProvider
{
    public static function loadFromConfig(ContainerBuilder $container_builder, Request $request): ContainerBuilder
    {
        $loader = new YamlFileLoader(new FileLocator('../config'));
        $routes = $loader->load('routes.yml');
        $context = (new RequestContext())->fromRequest($request);
        $view = new View();
        $em = require __DIR__ . '/../config/database.php';
        $validator = Validation::createValidatorBuilder()
            ->addYamlMapping(__DIR__ . '/../config/Validatior/validation.yml')
            ->getValidator();
        $er = new EntityRepository($em, new ClassMetadata(Entity::class));

        $container_builder->set(Request::class, $request);
        $container_builder->set(EntityManager::class, $em);
        $container_builder->set(EntityRepository::class, $er);
        $container_builder->set(RouteCollection::class, $routes);
        $container_builder->set(RequestContext::class, $context);
        $container_builder->set(Validation::class, $validator);
        $container_builder->set(View::class, $view);

        self::registerEntityManagers($container_builder);
        return $container_builder;
    }

    private static function registerEntityManagers(ContainerBuilder &$container_builder)
    {
        $entity_managers = Yaml::parseFile(__DIR__ . '/../config/services.yml')['entity_managers'];

        foreach ($entity_managers as $entity_manager_classname => $entity_classname) {
            $container_builder->register($entity_manager_classname);
            $container_builder->set($entity_manager_classname, new $entity_manager_classname(
                $container_builder->get(EntityManager::class),
                new ClassMetadata($entity_classname)
            ));
        }
    }
}