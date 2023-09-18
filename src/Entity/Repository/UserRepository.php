<?php

namespace App\Entity\Repository;

use App\Entity\Schedule;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;

class UserRepository extends EntityRepository
{
    public function getByRole(string $role)
    {
        return $this->findBy([
            'role' => $role
        ]);
    }

    public function getSchedulesWithUsers()
    {
        $users = $this->_em->createQueryBuilder()
            ->select('u')
            ->from(User::class, 'u')
            ->getQuery()
            ->getResult();

        return $this->_em->createQueryBuilder()
            ->select('s')
            ->from(Schedule::class, 's')
            ->where('IDENTITY(s.student) IN (?1)')
            ->orWhere('IDENTITY(s.teacher) IN (?1)')
            ->setParameter(1, array_keys($users))
            ->getQuery()
            ->getResult();
    }
}