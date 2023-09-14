<?php

use Symfony\Component\HttpFoundation\Request;

$request = Request::createFromGlobals();
dd($request);