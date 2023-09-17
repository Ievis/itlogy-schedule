<?php

namespace App\Entity\Repository;

use App\Entity\Schedule;
use Doctrine\ORM\EntityRepository;

class ScheduleRepository extends EntityRepository
{
    public function findAllWithUsers()
    {
        return $this->_em->createQueryBuilder()
            ->select('s', 'u', 't')
            ->from(Schedule::class, 's')
            ->join('s.student', 'u')
            ->join('s.teacher', 't')
            ->getQuery()
            ->getArrayResult();
    }
}