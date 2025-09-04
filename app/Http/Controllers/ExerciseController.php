<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExerciseUpdateRequest;
use App\Models\Exercise;
use App\Repositories\Eloquent\ExerciseRepository;
use Illuminate\Http\Request;

class ExerciseController extends Controller
{
    protected ExerciseRepository $exerciseRepository;
    protected string $model;
    protected string $routePrefix;

    public function __construct(ExerciseRepository $exerciseRepository)
    {
        $this->exerciseRepository = $exerciseRepository;
        $this->model = 'Exercise';
        $this->routePrefix = 'exercises';
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
     * Display the specified resource.
     */
    public function show(Exercise $exercise)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Exercise $exercise)
    {
        //
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
}
