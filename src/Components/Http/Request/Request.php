<?php

namespace App\Components\Http\Request;

class Request
{
    public array $globals = [];

    public function __construct()
    {
        $this->globals['GET'] = $_GET;
        $this->globals['POST'] = $_POST;
        $this->globals['SERVER'] = $_SERVER;
    }

    public static function createFromGlobals()
    {
        return new self();
    }

    public function getRequestUri()
    {
        return $this->globals['SERVER']['REQUEST_URI'];
    }

    public function getRequestMethod()
    {
        return $this->globals['SERVER']['REQUEST_METHOD'];
    }
}