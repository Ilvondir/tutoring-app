<?php

namespace App\Repositories\Eloquent;

use App\Models\Exercise;
use App\Models\Homework;
use App\Models\User;
use App\Repositories\Contracts\ExerciseRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class ExerciseRepository implements ExerciseRepositoryInterface
{
    protected string $model;

    public function __construct()
    {
        $this->model = 'Exercise';
    }


    public function getHomeworkExercises(Homework $homework): Collection
    {
        return $homework
            ->exercises()
            ->get();
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

    public function create(array $data)
    {
        try {
            $maxOrder = Exercise::whereHomeworkId($data['homework_id'])->max('order');
            dd($maxOrder);
            return Exercise::create($data + ['order' => $maxOrder + 1]);
        } catch (\Exception $e) {
            Log::error('Błąd podczas tworzenia ' . $this->model, [
                'data' => $data,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function update(Exercise $exercise, array $data): mixed
    {
        try {
            $exercise->update($data);
            return $exercise->fresh();
        } catch (\Exception $e) {
            Log::error('Błąd podczas aktualizacji ' . $this->model, [
                'data' => $data,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function loadRelations(Exercise $exercise)
    {
        return $exercise->load(['homework']);
    }

    public function getExerciseInHomeworkByOrder(Homework $homework, int $order)
    {
        return $homework->exercises()->whereOrder($order)->first();
    }
}
