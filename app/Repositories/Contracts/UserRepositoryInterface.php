<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Http\Request;

interface UserRepositoryInterface
{
    public function getStudentsToSelect();

    public function getAll();

    public function paginate(Request $request);

    public function findById(int $id);

    public function create(array $data);

    public function update(User $user, array $data);

    public function delete(User $user);

    public function deleteByArray(array $ids);
}
