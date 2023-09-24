<?php

namespace App\Entity\Repository;

use App\Components\Db\Repository;

class UserRepository extends Repository
{
    public function getByRole(string $role)
    {
        return $this->builder->table('users', 'u')
            ->select()
            ->where('role', '=', $role)
            ->get();
    }

    public function find(int $id)
    {
        return $this->builder->table('users')
            ->select()
            ->where('user_id', '=', $id)
            ->get()[0];
    }
}