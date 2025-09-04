<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Homework;
use Illuminate\Database\Eloquent\Factories\Factory;

class HomeworkFactory extends Factory
{
    protected $model = Homework::class;

    public function definition(): array
    {
        return [
            'complete_date' => $this->faker->optional()->dateTime(),
            'student_id' => User::factory(),
            'title' => $this->faker->word(),
        ];
    }
}
