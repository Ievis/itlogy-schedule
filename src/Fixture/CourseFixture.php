<?php

namespace App\Fixture;

use App\Entity\Course;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CourseFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        for ($i = 0; $i < 20; $i++) {
            $user = new Course([
                'name' => implode(' ', $faker->words(3)),
            ]);
            $manager->persist($user);
        }

        $manager->flush();
    }
}