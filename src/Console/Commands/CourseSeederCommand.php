<?php

namespace App\Console\Commands;

use App\Components\Console\Command;
use App\Components\Db\QueryBuilder;
use Faker\Factory;

class CourseSeederCommand extends Command
{
    public function name()
    {
        return 'course-seed';
    }

    public function body(QueryBuilder $builder)
    {
        $faker = Factory::create();
        for ($i = 0; $i < 20; $i++) {
            $builder->table('courses', 'c')
                ->insert([
                    'name' => $faker->name(),
                ])
                ->flush();
        }
    }

}