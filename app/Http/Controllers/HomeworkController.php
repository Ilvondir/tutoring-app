<?php

namespace App\Http\Controllers;

use App\Http\Requests\HomeworkStoreRequest;
use App\Http\Resources\HomeworkResource;
use App\Models\Homework;
use App\Repositories\Eloquent\HomeworkRepository;
use App\Services\HomeworkService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Inertia;
use Inertia\Response;

class HomeworkController extends Controller
{

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
     * @param HomeworkStoreRequest $request
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Homework $homework)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Homework $homework)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Homework $homework)
    {
        $this->homeworkService->deleteHomework($homework);
        return back();
    }
}
