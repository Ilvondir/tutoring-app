<?php

use App\Http\Controllers\HomeworkController;
use App\Http\Controllers\UserController;
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

    Route::get('students', [UserController::class, 'getStudentsToSelect'])->name('users.getStudentsToSelect');
});
