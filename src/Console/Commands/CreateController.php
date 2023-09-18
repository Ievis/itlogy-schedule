<?php

namespace App\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateController extends Command
{
    protected function configure()
    {
        $this->setName('make:controller');
        $this->addArgument('name', InputArgument::REQUIRED, 'Who do you want to greet?');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $contoller = $input->getArgument('name');
        $content = "<?php
        
namespace App\Controller;

class $contoller extends AbstractController
{

}";

        $file = fopen(__DIR__ . "/../../../src/Controller/$contoller.php", 'wb+');
        fputs($file, $content);
        fclose($file);

        return Command::SUCCESS;
    }
}