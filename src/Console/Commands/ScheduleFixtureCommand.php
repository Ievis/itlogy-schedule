<?php

namespace App\Console\Commands;

use App\Config;
use App\Fixture\ScheduleFixture;
use App\Providers\EntityManagerServiceProvider;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ScheduleFixtureCommand extends Command
{
    protected function configure()
    {
        $this->setName('schedules-fixture');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        new Config();
        $em = EntityManagerServiceProvider::getEntityManager();
        $fixture = new ScheduleFixture();
        $fixture->load($em);

        return Command::SUCCESS;
    }
}