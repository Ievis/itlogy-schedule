<?php

namespace App\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class ValidationException extends Exception
{
    private Response $response;

    public function __construct(Response $response)
    {
        parent::__construct();

        $this->response = $response;
    }

    public function getResponse()
    {
        return $this->response;
    }
}