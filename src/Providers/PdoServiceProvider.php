<?php

namespace App\Providers;

use App\Config;
use PDO;

class PdoServiceProvider extends ServiceProvider implements ProviderInterface
{
    public function process(): array
    {
        $dsn = 'mysql:host=' . Config::get('db_host') . ';'
            . 'dbname=' . Config::get('db_name');
        return [new PDO($dsn, Config::get('db_user'), Config::get('db_password'))];
    }
}