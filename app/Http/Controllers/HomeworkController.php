<?php

namespace App\Http\Controllers;

use App\Http\Resources\HomeworkResource;
use App\Models\Homework;
use App\Repositories\Eloquent\HomeworkRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Inertia;
use Inertia\Response;

class HomeworkController extends Controller
{

    protected HomeworkRepository $homeworkRepository;
    protected string $model;
    protected string $routePrefix;

    public function __construct(HomeworkRepository $homeworkRepository)
    {
        $this->homeworkRepository = $homeworkRepository;
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
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }
}
