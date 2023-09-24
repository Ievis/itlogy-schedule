<?php

namespace App\Components\Console;

use App\Components\Console\Exception\CommandNotFoundException;
use App\Components\Console\Exception\InvalidPropertyException;
use App\Components\Console\Exception\TooManyArgumentsException;
use App\Components\Container\Container;
use App\Components\Container\Definition;
use App\Config;
use App\Providers\ServiceProvider;

class Application
{
    public Container $container;
    public string $arg;
    public array $commands = [];

    public function __construct()
    {
        global $argv;
        $args = array_filter($argv, function ($key) {
            return $key === 1;
        }, ARRAY_FILTER_USE_KEY);
        if (count($args) > 1) {
            throw new TooManyArgumentsException();
        }

        new Config();
        $this->container = ServiceProvider::loadContainer();
        $this->arg = $args[1];
    }

    public function add(CommandInterface $command)
    {
        $this->commands[$command->name()] = $command;
    }

    public function run()
    {
        $command = $this->getCommand();
        $definition = new Definition($command::class, [
            'prop' => $this->getProp()
        ]);
        $definition->addMethodCall('body');
        $this->container->setDefinition($command::class, $definition);

        $this->container->get($command::class);
    }

    public function getCommand()
    {
        foreach ($this->commands as $command) {
            $prop = $this->getProp();
            if ($command->name() === $this->getName()) {
                if ($this->hasProp($prop, $command)) {
                    $command->setProp($this->getProp());

                    return $command;
                }

                throw new InvalidPropertyException();
            }
        }

        throw new CommandNotFoundException();
    }

    public function getProp()
    {
        if (str_contains($this->arg, ':')) {
            return substr($this->arg, strpos($this->arg, ':') + 1);
        }

        return null;
    }

    public function getName()
    {
        if (str_contains($this->arg, ':')) {
            return substr($this->arg, 0, strpos($this->arg, ':'));
        }

        return $this->arg;
    }

    public function hasProp(null|string $prop, CommandInterface $command)
    {
        return empty($command->props()) or in_array($prop, $command->props());
    }
}