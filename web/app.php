<?php

require '../vendor/autoload.php';

use App\Application;
use App\Components\Http\Request\Request;
use Symfony\Component\ErrorHandler\Debug;

session_start();
Debug::enable();

$request = Request::createFromGlobals();
$app = new Application($request);
$response = $app->handle();

$app->terminate($response);


