<?php

namespace App\Http\Controllers;

use App\Http\Requests\HomeworkStoreRequest;
use App\Http\Requests\HomeworkUpdateRequest;
use App\Http\Resources\HomeworkResource;
use App\Models\Homework;
use App\Repositories\Eloquent\HomeworkRepository;
use App\Services\HomeworkService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Inertia;
use Inertia\Response;

class HomeworkController extends \Illuminate\Routing\Controller
{
    use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;

    protected HomeworkRepository $homeworkRepository;
    protected HomeworkService $homeworkService;
    protected string $model;
    protected string $routePrefix;

    public function __construct(HomeworkRepository $homeworkRepository, HomeworkService $homeworkService)
    {
        $this->homeworkRepository = $homeworkRepository;
        $this->homeworkService = $homeworkService;
        $this->model = 'Homework';
        $this->routePrefix = 'homeworks';
        $this->authorizeResource(Homework::class);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return AnonymousResourceCollection|Response
     */
    public function index(Request $request): AnonymousResourceCollection|Response
    {
        if (request()->has(['filter'])) {
            return HomeworkResource::collection($this->homeworkRepository->paginate($request));
        }
        return Inertia::render($this->model . '/Index', []);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render($this->model . '/Create', []);
    }

    /**
     * Store a newly created resource in storage.
     * @param HomeworkUpdateRequest $request
     * @return RedirectResponse
     */
    public function store(HomeworkStoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $homework = $this->homeworkRepository->create($validated);
        return to_route($this->routePrefix . '.edit', $homework->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Homework $homework)
    {
        return Inertia::render($this->model . '/Show', [
            'item' => new HomeworkResource($this->homeworkRepository->loadRelations($homework)),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Homework $homework)
    {
        return Inertia::render($this->model . '/Edit', [
            'item' => new HomeworkResource($this->homeworkRepository->loadRelations($homework)),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(HomeworkUpdateRequest $request, Homework $homework)
    {
        $validated = $request->validated();
        $this->homeworkRepository->update($homework, $validated);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Homework $homework)
    {
        $this->homeworkService->deleteHomework($homework);
        return back();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function destroyArray(Request $request): mixed
    {
        $this->homeworkService->deleteByArray($request->ids);
        return back();
    }
}
