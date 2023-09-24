<?php

namespace App\Components\Db;

class Repository
{
    public QueryBuilder $builder;

    public function __construct(QueryBuilder $builder)
    {
        $this->builder = $builder;
    }
}