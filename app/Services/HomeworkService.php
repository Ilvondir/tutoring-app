<?php

namespace App\Services;

use App\Models\Homework;
use App\Repositories\Eloquent\ExerciseRepository;
use App\Repositories\Eloquent\HomeworkRepository;
use Illuminate\Support\Facades\Log;

class HomeworkService
{
    protected HomeworkRepository $homeworkRepository;
    protected ExerciseRepository $exerciseRepository;

    public function __construct(HomeworkRepository $homeworkRepository, ExerciseRepository $exerciseRepository)
    {
        $this->homeworkRepository = $homeworkRepository;
        $this->exerciseRepository = $exerciseRepository;
    }

    /**
     * @throws \Exception
     */
    public function deleteHomework(Homework $homework): true
    {
        $exercises = $homework->exercises;
        foreach ($exercises as $exercise) {
            $this->exerciseRepository->delete($exercise);
        }

        $this->homeworkRepository->delete($homework);

        return true;
    }
}
