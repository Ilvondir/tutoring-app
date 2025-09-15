<?php

namespace Database\Seeders;

use App\Models\Attempt;
use App\Models\Exercise;
use App\Models\Homework;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
//        $teacherRole = Role::firstOrCreate(['name' => 'teacher']);
//        $studentRole = Role::firstOrCreate(['name' => 'student']);
//
//        User::factory()->create([
//            'name' => 'Test User',
//            'email' => 'test@example.com',
//        ])->assignRole($teacherRole);
//
//
//        User::factory(3)->create()->each(function ($user) use ($teacherRole) {
//            $user->assignRole($teacherRole);
//        });
//
//        User::factory(10)->create()->each(function ($student) use ($studentRole) {
//            $student->assignRole($studentRole);
//
//            Homework::factory()
//                ->count(random_int(1, 3))
//                ->for($student, 'student')
//                ->create([
//                    'teacher_id' => random_int(1, 4),
//                ])
//                ->each(function ($homework) {
//                    Exercise::factory()
//                        ->count(random_int(2, 5))
//                        ->for($homework)
//                        ->create()
//                        ->each(function ($exercise) {
//                            Attempt::factory()
//                                ->count(random_int(1, 3))
//                                ->for($exercise)
//                                ->create();
//                        });
//                });
//        });
    }
}
