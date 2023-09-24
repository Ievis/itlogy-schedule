<?php

namespace App\Console\Commands;

use App\Components\Console\Command;
use App\Components\Db\QueryBuilder;
use Faker\Factory;

class UserSeederCommand extends Command
{
    public function name()
    {
        return 'user-seed';
    }

    public function body(QueryBuilder $builder)
    {
        $faker = Factory::create();
        for ($i = 0; $i < 20; $i++) {
            $builder->table('users', 'u')
                ->insert([
                    'email' => $faker->email(),
                    'first_name' => $faker->name(),
                    'last_name' => $faker->name(),
                    'surname' => $faker->name(),
                    'phone' => $faker->phoneNumber(),
                    'role' => ['student', 'teacher'][random_int(0, 1)]
                ])
                ->flush();
        }
    }

}