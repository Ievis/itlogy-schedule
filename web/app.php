<?php

require '../vendor/autoload.php';

use App\Application;
use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\HttpFoundation\Request;

session_start();
Debug::enable();

$request = Request::createFromGlobals();
$app = new Application($request);
$response = $app->handle();

$app->terminate($response);


