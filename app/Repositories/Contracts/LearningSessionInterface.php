<?php

namespace App\Repositories\Contracts;

use App\Models\LearningSession;
use Illuminate\Http\Request;

interface LearningSessionInterface
{
    public function paginate(Request $request);

    public function create(array $data);

    public function delete(LearningSession $learningSession);

    public function deleteByArray(array $ids);
}
