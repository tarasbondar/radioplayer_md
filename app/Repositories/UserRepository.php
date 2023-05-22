<?php

namespace App\Repositories;


use App\Models\User;

class UserRepository extends AbstractRepository
{
    public function create(array $data)
    {
        return User::create($data);
    }

    public function getAll()
    {
        return User::query()->get();
    }

    public function getUser(int $id)
    {
        return User::query()
            ->where('id', $id)
            ->first();
    }
}
