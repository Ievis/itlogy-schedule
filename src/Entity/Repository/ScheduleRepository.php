<?php

namespace App\Entity\Repository;

use App\Components\Db\Repository;
use PDO;

class ScheduleRepository extends Repository
{
    public function paginateWithUsers(int $page)
    {
        $schedules_students = $this->builder->table('schedules', 'sch')
            ->select()
            ->join('users', 's', 'sch.student_id = s.user_id')
            ->limit(10)
            ->offset(($page - 1) * 10)
            ->get();
        $schedules_teachers = $this->builder->table('schedules', 'sch')
            ->select()
            ->join('users', 't', 'sch.teacher_id = t.user_id')
            ->limit(10)
            ->offset(($page - 1) * 10)
            ->get();

        $schedules = [];
        array_walk($schedules_students, function ($schedule, $schedule_number) use ($schedules_teachers, &$schedules) {
            $schedule_student = $schedules_teachers[$schedule_number];
            $student = [
                'id' => $schedule['user_id'],
                'email' => $schedule['email'],
                'first_name' => $schedule['first_name'],
                'last_name' => $schedule['last_name'],
                'surname' => $schedule['surname'],
                'phone' => $schedule['phone'],
                'role' => $schedule['role']
            ];
            $teacher = [
                'id' => $schedule_student['user_id'],
                'email' => $schedule_student['email'],
                'first_name' => $schedule_student['first_name'],
                'last_name' => $schedule_student['last_name'],
                'surname' => $schedule_student['surname'],
                'phone' => $schedule_student['phone'],
                'role' => $schedule_student['role']
            ];

            $schedules[] = [
                'schedule_id' => $schedule['schedule_id'],
                'student_id' => $schedule['student_id'],
                'teacher_id' => $schedule['teacher_id'],
                'will_at' => $schedule['will_at'],
                'student' => $student,
                'teacher' => $teacher
            ];
        });

        return $schedules;
    }

    public function countAll()
    {
        return $this->builder->table('schedules', 's')
            ->select(['COUNT(s.schedule_id)'])
            ->get()[0]['COUNT(s.schedule_id)'];
    }

    public function insert(array $data)
    {
        return $this->builder->table('schedules', 's')
            ->insert($data)
            ->flush();
    }

    public function findBy(array $data)
    {
        $query = $this->builder->table('schedules', 's')
        ->select();
        foreach($data as $key => $value) {
            $query->where($key, '=', $value);
        }

        return $query->get();
    }
}