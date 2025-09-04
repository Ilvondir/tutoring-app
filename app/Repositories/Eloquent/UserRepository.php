<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    protected string $model;

    public function __construct()
    {
        $this->model = 'User';
    }

    public function getStudentsToSelect()
    {
        return User::whereHas('roles', function ($query) {
            $query->where('name', 'student');
        })
            ->select('id', 'name')
            ->get()
            ->toArray();
    }
}
