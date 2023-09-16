<?php

use App\Config;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

require_once __DIR__ . "/../vendor/autoload.php";

$config = ORMSetup::createAttributeMetadataConfiguration(
    [__DIR__ . "/../src/Entity"],
    true
);

$connection = DriverManager::getConnection([
    'dbname' => Config::get('db_name'),
    'user' => Config::get('db_user'),
    'password' => Config::get('db_password'),
    'driver' => Config::get('db_driver'),
    'host' => Config::get('db_host'),
    'path' => Config::get('db_path'),
], $config);


return $entityManager = new EntityManager($connection, $config);