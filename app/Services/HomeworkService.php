<?php

namespace App\Services;

use App\Models\Homework;
use App\Repositories\Eloquent\ExerciseRepository;
use App\Repositories\Eloquent\HomeworkRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HomeworkService
{
    protected HomeworkRepository $homeworkRepository;
    protected ExerciseRepository $exerciseRepository;
    public string $model;

    public function __construct(HomeworkRepository $homeworkRepository, ExerciseRepository $exerciseRepository)
    {
        $this->homeworkRepository = $homeworkRepository;
        $this->exerciseRepository = $exerciseRepository;
        $this->model = 'Homework';
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

    public function deleteByArray(array $ids)
    {
        if (empty($ids) || isset($ids['__v_isShallow']) || isset($ids['__v_isRef'])) {
            Log::warning('Próba usunięcia ' . $this->model . ' bez poprawnej listy ID', ['ids' => $ids]);
            return false;
        }

        try {
            DB::transaction(function () use ($ids) {
                $homeworks = Homework::whereIn('id', $ids)->get();
                foreach ($homeworks as $homework) {
                    $this->deleteHomework($homework);
                }
            });
            return true;
        } catch (\Exception $e) {
            Log::error('Błąd masowego usuwania ' . $this->model, [
                'ids' => $ids,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
