<?php

namespace App\Exception;

use App\Components\Http\Response\Response;
use Exception;

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