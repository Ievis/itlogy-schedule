<?php

namespace App\Console\Commands;

use App\Components\Console\Command;
use App\Components\Db\QueryBuilder;
use Faker\Factory;

class ScheduleSeederCommand extends Command
{
    public function name()
    {
        return 'schedule-seed';
    }

    public function body(QueryBuilder $builder)
    {
        $students = $builder->table('users', 'u')
            ->select(['user_id'])
            ->where('role', '=', 'student')
            ->get();
        $teachers = $builder->table('users', 'u')
            ->select(['user_id'])
            ->where('role', '=', 'teacher')
            ->get();

        $faker = Factory::create();
        for ($i = 0; $i < 20; $i++) {
            $builder->table('schedules', 's')
                ->insert([
                    'will_at' => ((array)($faker->dateTimeBetween('now', '1 year', 'Europe/Moscow')))['date'],
                    'student_id' => $students[array_rand($students)]['user_id'],
                    'teacher_id' => $teachers[array_rand($teachers)]['user_id'],
                ])
                ->flush();
        }
    }

}