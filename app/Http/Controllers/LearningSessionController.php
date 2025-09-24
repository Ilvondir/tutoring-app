<?php

namespace App\Http\Controllers;

use App\Http\Requests\LearningSessionStoreRequest;
use App\Http\Resources\LearningSessionResource;
use App\Models\LearningSession;
use App\Repositories\Eloquent\LearningSessionRepository;
use App\Repositories\Eloquent\UserRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Inertia;
use Inertia\Response;


class LearningSessionController extends \Illuminate\Routing\Controller
{
    use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;

    protected LearningSessionRepository $learningSessionRepository;
    protected string $model;
    protected string $routePrefix;

    public function __construct(LearningSessionRepository $learningSessionRepository)
    {
        $this->learningSessionRepository = $learningSessionRepository;
        $this->model = 'LearningSession';
        $this->routePrefix = 'learning-sessions';
        $this->authorizeResource(LearningSession::class);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return AnonymousResourceCollection|Response
     */
    public function index(Request $request): AnonymousResourceCollection|Response
    {
        if (request()->has(['filter'])) {
            return LearningSessionResource::collection($this->learningSessionRepository->paginate($request));
        }
        return Inertia::render($this->model . '/Index', []);
    }


    /**
     * Store a newly created resource in storage.
     * @param LearningSessionStoreRequest $request
     * @return RedirectResponse
     */
    public function store(LearningSessionStoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $this->learningSessionRepository->create($validated);
        return back();
    }

    /**
     * @param LearningSession $learningSession
     * @return RedirectResponse
     * @throws \Exception
     */
    public function destroy(LearningSession $learningSession): RedirectResponse
    {
        $this->learningSessionRepository->delete($learningSession);
        return back();
    }


    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws \Exception
     */
    public function destroyArray(Request $request): RedirectResponse
    {
        $this->learningSessionRepository->deleteByArray($request->ids);
        return back();
    }
}
