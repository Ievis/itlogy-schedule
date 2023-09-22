<?php

namespace App\Controller;

use App\Resource\JsonResource;
use App\View\View;
use Doctrine\ORM\EntityManager;
use PDO;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AbstractController
{
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
}