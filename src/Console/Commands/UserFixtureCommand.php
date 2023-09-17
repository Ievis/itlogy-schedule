<?php

namespace App\Console\Commands;

use App\Config;
use App\Fixture\UserFixture;
use App\Providers\EntityManagerServiceProvider;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UserFixtureCommand extends Command
{
    protected function configure()
    {
        $this->setName('users-fixture');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        new Config();
        $em = EntityManagerServiceProvider::getEntityManager();
        $fixture = new UserFixture();
        $fixture->load($em);

        return Command::SUCCESS;
    }
}