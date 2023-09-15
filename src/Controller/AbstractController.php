<?php

namespace App\Controller;

use App\View\View;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AbstractController
{
    protected EntityManager $em;
    protected ValidatorInterface $validator;
    protected View $view;

    public function __construct()
    {
//        $this->em = require __DIR__ . '/../../config/bootstrap.php';
//        $this->validator = Validation::createValidatorBuilder()
//            ->addYamlMapping(__DIR__ . '/../../config/Validatior/validation.yml')
//            ->getValidator();
        $this->view = new View();
    }


}