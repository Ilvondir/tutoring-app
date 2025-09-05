<?php

namespace App\Services;

use App\Models\Exercise;
use App\Models\Homework;
use App\Repositories\Eloquent\ExerciseRepository;
use App\Repositories\Eloquent\HomeworkRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExerciseService
{
    protected HomeworkRepository $homeworkRepository;
    protected ExerciseRepository $exerciseRepository;
    public string $model;

    public function __construct(HomeworkRepository $homeworkRepository, ExerciseRepository $exerciseRepository)
    {
        $this->homeworkRepository = $homeworkRepository;
        $this->exerciseRepository = $exerciseRepository;
        $this->model = 'Exercise';
    }

    public function move(Exercise $exercise, int $direction)
    {
        try {
            $homework = $this->exerciseRepository->loadRelations($exercise)->homework;

            $currentOrder = $exercise->order;
            $newOrder = $direction ? $currentOrder - 1 : $currentOrder + 1;

            $secondExercise = $this->exerciseRepository->getExerciseInHomeworkByOrder($homework, $newOrder);

            $exercise->update(['order' => $newOrder]);
            $secondExercise->update(['order' => $currentOrder]);

            return true;
        } catch (\Exception $e) {
            Log::error('Błąd podczas przesuwania ' . $this->model, [
                'exercise' => $exercise,
                'direction' => $direction,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }


}
