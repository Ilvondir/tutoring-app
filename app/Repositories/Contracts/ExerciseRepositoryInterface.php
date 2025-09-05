<?php

namespace App\Repositories\Contracts;

use App\Models\Exercise;
use App\Models\Homework;

interface ExerciseRepositoryInterface
{
    public function create(array $data);

    public function update(Exercise $exercise, array $data);

    public function getHomeworkExercises(Homework $homework);

    public function loadRelations(Exercise $exercise);

    public function delete($exercise);

}
