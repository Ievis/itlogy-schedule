<?php

namespace App\Fixture;

use App\Entity\Schedule;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ScheduleFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $user_repo = $manager->getRepository(User::class);
        $students = $user_repo->findBy(['role' => 'student']);
        $teachers = $user_repo->findBy(['role' => 'teacher']);

        for ($i = 0; $i < 20; $i++) {
            $schedule = new Schedule();
            $schedule->setWillAt($faker->dateTimeBetween('now', '1 year', 'Europe/Moscow'));
            $schedule->setStudent($students[array_rand($students)]);
            $schedule->setTeacher($teachers[array_rand($teachers)]);

            $manager->persist($schedule);
        }

        $manager->flush();
    }
}