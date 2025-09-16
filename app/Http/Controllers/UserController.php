<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\Eloquent\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends \Illuminate\Routing\Controller
{
    use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;

    protected UserRepository $userRepository;
    protected string $model;
    protected string $routePrefix;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->model = 'User';
        $this->routePrefix = 'users';
        $this->authorizeResource(User::class);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return AnonymousResourceCollection|Response
     */
    public function index(Request $request): AnonymousResourceCollection|Response
    {
        if (request()->has(['filter'])) {
            return UserResource::collection($this->userRepository->paginate($request));
        }
        return Inertia::render($this->model . '/Index', []);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render($this->model . '/Create', []);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreRequest $request)
    {
        $data = $request->validated();
        $user = $this->userRepository->create($data);
        return to_route($this->routePrefix . '.show', $user->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return Inertia::render($this->model . '/Show', [
            'item' => (new UserResource($user)),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return Inertia::render($this->model . '/Edit', [
            'item' => new UserResource($user),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        $validated = $request->validated();
        $this->userRepository->update($user, $validated);
        return to_route($this->routePrefix . '.show', $user->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $this->userRepository->delete($user);
        return back();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function destroyArray(Request $request): mixed
    {
        $this->userRepository->deleteByArray($request->ids);
        return back();
    }

    /**
     * @return mixed
     */
    public function getStudentsToSelect(): mixed
    {
        return $this->userRepository->getStudentsToSelect();
    }
}
