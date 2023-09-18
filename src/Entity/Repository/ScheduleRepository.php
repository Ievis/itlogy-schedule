<?php

namespace App\Entity\Repository;

use App\Entity\Schedule;
use Doctrine\ORM\EntityRepository;

class ScheduleRepository extends EntityRepository
{
    public function paginateWithUsers($page)
    {
        $page = max($page, 1);

        return $this->_em->createQueryBuilder()
            ->select('s', 'u', 't')
            ->from(Schedule::class, 's')
            ->join('s.student', 'u')
            ->join('s.teacher', 't')
            ->setFirstResult(($page - 1) * 10)
            ->setMaxResults(10)
            ->getQuery()
            ->getArrayResult();
    }

    public function countAll()
    {
        return $this->_em->createQueryBuilder()
            ->select('count(s.id)')
            ->from(Schedule::class, 's')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function insert(array $data)
    {
        $schedule = new Schedule([
            'student' => $data['student'],
            'teacher' => $data['teacher'],
            'will_at' => $data['will_at']
        ]);

        $this->_em->persist($schedule);
        $this->_em->flush();
    }
}