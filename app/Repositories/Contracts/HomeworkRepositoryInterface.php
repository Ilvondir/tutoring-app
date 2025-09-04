<?php

namespace App\Repositories\Contracts;

use App\Models\Homework;
use Illuminate\Http\Request;

interface HomeworkRepositoryInterface
{
    public function getAll();

    public function paginate(Request $request);

    public function findById(int $id);

    public function loadRelations(Homework $homework);

    public function create(array $data);

    public function update(Homework $homework, array $data);

    public function delete(Homework $homework);

    public function deleteByArray(array $ids);
}
