<?php

namespace App\Providers;

use App\Models\Attempt;
use App\Models\Exercise;
use App\Models\Homework;
use App\Models\LearningSession;
use App\Models\User;
use App\Policies\AttemptPolicy;
use App\Policies\ExercisePolicy;
use App\Policies\HomeworkPolicy;
use App\Policies\LearningSessionPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        LearningSession::class => LearningSessionPolicy::class,
        Exercise::class => ExercisePolicy::class,
        Homework::class => HomeworkPolicy::class,
        User::class => UserPolicy::class,
        Attempt::class => AttemptPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
