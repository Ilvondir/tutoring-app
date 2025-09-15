<?php

namespace App\Repositories\Eloquent;

use App\Models\Attempt;
use App\Models\Exercise;
use App\Models\Homework;
use App\Models\User;
use App\Repositories\Contracts\AttemptRepositoryInterface;
use App\Repositories\Contracts\ExerciseRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AttemptRepository implements AttemptRepositoryInterface
{
    protected string $model;

    public function __construct()
    {
        $this->model = 'Attempt';
    }

    public function create(array $data)
    {
        try {
            return Attempt::create($data);
        } catch (\Exception $e) {
            Log::error('Błąd podczas tworzenia ' . $this->model, [
                'data' => $data,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
