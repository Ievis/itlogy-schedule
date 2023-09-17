<?php

namespace App\Fixture;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UserFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        for ($i = 0; $i < 20; $i++) {
            $user = new User([
                'email' => $faker->email(),
                'first_name' => $faker->name(),
                'last_name' => $faker->name(),
                'surname' => $faker->name(),
                'phone' => $faker->phoneNumber(),
                'role' => ['student', 'teacher'][random_int(0, 1)]
            ]);
            $manager->persist($user);
        }

        $manager->flush();
    }
}