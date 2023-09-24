<?php

namespace App\Providers;

use App\Components\Db\QueryBuilder;
use App\Config;
use App\Entity\Repository\CourseRepository;
use App\Entity\Repository\ScheduleRepository;
use App\Entity\Repository\UserRepository;
use PDO;

class DbServiceProvider extends ServiceProvider implements ProviderInterface
{
    public array $repositories = [
        UserRepository::class,
        CourseRepository::class,
        ScheduleRepository::class,
    ];

    public function requiredServices()
    {
        return [];
    }

    public function process(): array
    {
        $dsn = 'mysql:host=' . Config::get('db_host') . ';'
            . 'dbname=' . Config::get('db_name');
        $pdo = new PDO($dsn, Config::get('db_user'), Config::get('db_password'));
        $builder = new QueryBuilder($pdo);
        $repositories = $this->getRepositoryInstances($builder);
        $this->collect([$builder]);
        $this->collect($repositories);

        return $this->services;
    }

    public function getRepositoryInstances(QueryBuilder $builder)
    {
        $repositories = [];
        foreach ($this->repositories as $repository) {
            $repositories[] = new $repository($builder);
        }

        return $repositories;
    }
}