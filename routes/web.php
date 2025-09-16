<?php

use App\Http\Controllers\AttemptController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\HomeworkController;
use App\Http\Controllers\LearningSessionController;
use App\Http\Controllers\UserController;
use App\Repositories\Eloquent\AttemptRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return to_route('dashboard');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard', []);
    })->name('dashboard');

    Route::resource('homeworks', HomeworkController::class);
    Route::resource('exercises', ExerciseController::class)->only(['store', 'show', 'destroy', 'update']);
    Route::resource('users', UserController::class);
    Route::resource('learning-sessions', LearningSessionController::class)->only(['store', 'index']);

    Route::get('/attempts/{exercise}', AttemptController::class)->name('attempts.index');

    Route::get('students', [UserController::class, 'getStudentsToSelect'])->name('users.getStudentsToSelect');

    Route::get('homeworks/destroyArray/ids', [HomeworkController::class, 'destroyArray'])->name('homeworks.destroyArray');
    Route::get('users/destroyArray/ids', [UserController::class, 'destroyArray'])->name('users.destroyArray');

    Route::patch('exercises/{exercise}/move', [ExerciseController::class, 'move'])->name('exercises.move');
    Route::patch('exercises/{exercise}/check', [ExerciseController::class, 'check'])->name('exercises.check');
});
