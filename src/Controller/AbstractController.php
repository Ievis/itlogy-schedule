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

    public function __construct(EntityManager $em, ValidatorInterface $validator)
    {
        $this->em = $em;
        $this->validator = $validator;
    }
}