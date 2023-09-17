<?php

namespace App\Entity\Repository;

use App\Entity\User;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function getByRole(string $role)
    {
        return $this->findBy([
            'role' => $role
        ]);
    }
}