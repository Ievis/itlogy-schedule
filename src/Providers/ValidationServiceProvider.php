<?php

namespace App\Providers;

use Symfony\Component\Validator\Validation;

class ValidationServiceProvider extends ServiceProvider implements ProviderInterface
{
    public function process(): array
    {
        return [Validation::createValidatorBuilder()
            ->addYamlMapping(__DIR__ . '/../config/Validatior/validation.yml')
            ->getValidator()];
    }
}