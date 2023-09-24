<?php

namespace App\Console\Commands;

use App\Components\Console\Command;
use App\Components\Db\QueryBuilder;

class MigrateCommand extends Command
{
    public function name()
    {
        return 'db-migrate';
    }

    public function props()
    {
        return [
            'up',
            'down'
        ];
    }

    public function body(QueryBuilder $builder)
    {
        $ddl = file_get_contents(__DIR__ . '/../../../config/' . $this->getProp() . '.sql');
        $builder->executeDDL($ddl);
    }
}