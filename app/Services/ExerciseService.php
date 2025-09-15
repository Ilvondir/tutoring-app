<?php

namespace App\Services;

use App\Models\Exercise;
use App\Models\Homework;
use App\Repositories\Eloquent\AttemptRepository;
use App\Repositories\Eloquent\ExerciseRepository;
use App\Repositories\Eloquent\HomeworkRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ExerciseService
{
    protected HomeworkRepository $homeworkRepository;
    protected ExerciseRepository $exerciseRepository;
    protected AttemptRepository $attemptRepository;
    public string $model;

    public function __construct(HomeworkRepository $homeworkRepository, ExerciseRepository $exerciseRepository, AttemptRepository $attemptRepository)
    {
        $this->homeworkRepository = $homeworkRepository;
        $this->exerciseRepository = $exerciseRepository;
        $this->attemptRepository = $attemptRepository;
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

    public function checkAnswer(Exercise $exercise, string $answer): bool
    {
        try {
            $userAnswer = Str::lower(Str::trim($answer));
            $exerciseAnswer = Str::lower(Str::trim($exercise->answer));

            $attemptData = [
                'exercise_id' => $exercise->id,
                'value' => $answer,
            ];

            if ($exerciseAnswer === $userAnswer) {
                $attemptData['is_correct'] = true;
                $this->attemptRepository->create($attemptData);
                $this->exerciseRepository->update($exercise, ['complete_date' => now()]);
                return true;
            }

            $attemptData['is_correct'] = false;
            $this->attemptRepository->create($attemptData);
            return false;

        } catch (\Exception $e) {
            Log::error('Błąd podczas sprawdzenia odpowiedzi modelu ' . $this->model, [
                'data' => $exercise,
                'answer' => $answer,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
