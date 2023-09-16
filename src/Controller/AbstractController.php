<?php

namespace App\Controller;

use App\Resource\JsonResource;
use App\View\View;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AbstractController
{
    protected EntityManager $em;
    protected ValidatorInterface $validator;
    protected View $view;

    public function __construct(EntityManager $em, ValidatorInterface $validator, View $view)
    {
        $this->em = $em;
        $this->validator = $validator;
        $this->view = $view;
    }
}