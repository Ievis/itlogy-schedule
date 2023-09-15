<?php

require '../vendor/autoload.php';

use App\Application;
use Symfony\Component\HttpFoundation\Request;

$request = Request::createFromGlobals();
$app = new Application($request);
$response = $app->handle();

$app->terminate($request, $response);


