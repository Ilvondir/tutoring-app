<?php

namespace Database\Factories;

use App\Models\Homework;
use App\Models\Exercise;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExerciseFactory extends Factory
{
    protected $model = Exercise::class;

    public function definition(): array
    {
        return [
            'assignment' => $this->faker->sentence(10),
            'answer' => $this->faker->word(),
            'order' => $this->faker->numberBetween(1, 10),
            'complete_date' => $this->faker->optional()->dateTime(),
            'homework_id' => Homework::factory(),
        ];
    }
}
