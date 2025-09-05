<?php

namespace App\Repositories\Contracts;

use Illuminate\Http\Request;

interface LearningSessionInterface
{
    public function paginate(Request $request);

    public function create(array $data);
}
