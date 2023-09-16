<?php

require '../vendor/autoload.php';

use App\Application;
use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\HttpFoundation\Request;
Debug::enable();

$request = Request::createFromGlobals();
$app = new Application($request);
$response = $app->handle();

$app->terminate($request, $response);


