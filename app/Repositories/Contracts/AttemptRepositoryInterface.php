<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Http\Request;

interface AttemptRepositoryInterface
{
    public function create(array $data);
}
