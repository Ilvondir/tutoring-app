<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExerciseUpdateRequest;
use App\Models\Exercise;
use App\Repositories\Eloquent\ExerciseRepository;
use App\Repositories\Eloquent\HomeworkRepository;
use App\Services\ExerciseService;
use Illuminate\Http\Request;

class ExerciseController extends \Illuminate\Routing\Controller
{
    use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;

    protected ExerciseRepository $exerciseRepository;
    protected ExerciseService $exerciseService;
    protected HomeworkRepository $homeworkRepository;
    protected string $model;
    protected string $routePrefix;

    public function __construct(ExerciseRepository $exerciseRepository, ExerciseService $exerciseService, HomeworkRepository $homeworkRepository)
    {
        $this->exerciseRepository = $exerciseRepository;
        $this->exerciseService = $exerciseService;
        $this->homeworkRepository = $homeworkRepository;
        $this->model = 'Exercise';
        $this->routePrefix = 'exercises';
        $this->authorizeResource(Exercise::class);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ExerciseUpdateRequest $request)
    {
        $data = $request->validated();
        $this->exerciseRepository->create($data);
        return back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ExerciseUpdateRequest $request, Exercise $exercise)
    {
        $data = $request->validated();
        $this->exerciseRepository->update($exercise, $data);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Exercise $exercise)
    {
        $this->exerciseRepository->delete($exercise);
        return back();
    }

    public function move(Exercise $exercise)
    {
        $direction = request()->input('direction');
        $this->exerciseService->move($exercise, $direction);
        return back();
    }

    public function check(Exercise $exercise)
    {
        $answer = request()->input('answer');
        $isCorrect = $this->exerciseService->checkAnswer($exercise, $answer);

        if ($isCorrect) {
            $homework = $exercise->homework;
            if ($this->homeworkRepository->hasFinishedAllExercises($homework)) {
                $this->homeworkRepository->update($homework, ['complete_date' => now()]);
            }

            return back();
        } else {
            return back()->withErrors([
                'answer' => 'Odpowiedź błędna!'
            ]);
        }
    }
}
