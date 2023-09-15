<?php

namespace App;
class Config
{
    public static array $data;

    public function __construct()
    {
        $config_attributes = require __DIR__ . '/../config/config.php';

        foreach ($config_attributes as $attribute_name => $attribute_value) {
            self::$data[$attribute_name] = $attribute_value;
        }
    }

    public static function get(string $attribute)
    {
        return self::$data[$attribute];
    }
}