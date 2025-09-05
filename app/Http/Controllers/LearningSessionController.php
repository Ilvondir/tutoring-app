<?php

namespace App\Http\Controllers;

use App\Http\Requests\LearningSessionStoreRequest;
use App\Models\LearningSession;
use App\Repositories\Eloquent\LearningSessionRepository;
use App\Repositories\Eloquent\UserRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LearningSessionController extends Controller
{
    protected LearningSessionRepository $learningSessionRepository;
    protected string $model;
    protected string $routePrefix;

    public function __construct(LearningSessionRepository $learningSessionRepository)
    {
        $this->learningSessionRepository = $learningSessionRepository;
        $this->model = 'LearningSession';
        $this->routePrefix = 'learning-sessions';
    }


    /**
     * Store a newly created resource in storage.
     * @param LearningSessionStoreRequest $request
     * @return RedirectResponse
     */
    public function store(LearningSessionStoreRequest $request)
    {
        $validated = $request->validated();
        $this->learningSessionRepository->create($validated);
        return back();
    }
}
