<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\ExerciseRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Log;

class ExerciseRepository implements ExerciseRepositoryInterface
{
    protected string $model;

    public function __construct()
    {
        $this->model = 'Exercise';
    }

    public function delete($exercise)
    {
        try {
            $exercise->delete();
            return true;
        } catch (\Exception $e) {
            Log::error('Błąd usuwania ' . $this->model, [
                'instance_id' => $exercise->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
