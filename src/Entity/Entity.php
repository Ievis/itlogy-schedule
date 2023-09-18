<?php

namespace App\Entity;

use Symfony\Component\Validator\Validator\ValidatorInterface;

class Entity
{
    public function validated(ValidatorInterface $validator)
    {
        $errors = $validator->validate($this);
        if ($errors->count()) {
            foreach ($errors as $error) {
                $errorBag[$error->getPropertyPath()] = $error->getMessage();
            }
        }

        return $errorBag ?? [];
    }
}