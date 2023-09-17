<?php

namespace App\Providers;

use App\Entity\Course;
use App\Entity\Entity;
use App\Entity\Repository\CourseRepository;
use App\Entity\Repository\ScheduleRepository;
use App\Entity\Repository\UserRepository;
use App\Entity\Schedule;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;

class EntityManagerServiceProvider extends ServiceProvider implements ProviderInterface
{
    public array $repositories = [
        UserRepository::class => User::class,
        CourseRepository::class => Course::class,
        ScheduleRepository::class => Schedule::class,
    ];

    public function process(): array
    {
        $entity_manager = require __DIR__ . '/../../config/database.php';
        $entity_repository = new EntityRepository($entity_manager, new ClassMetadata(Entity::class));
        $this->collectEntityManagers($entity_manager);
        $this->collect([$entity_manager, $entity_repository]);

        return $this->services;
    }

    private function collectEntityManagers(EntityManager $entity_manager)
    {
        foreach ($this->repositories as $repository => $entity) {
            $this->collect([new $repository($entity_manager, new ClassMetadata($entity))]);
        }

    }
}