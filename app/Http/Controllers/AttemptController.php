<?php

namespace App\Http\Controllers;

use App\Http\Resources\AttemptResource;
use App\Models\Attempt;
use App\Models\Exercise;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AttemptController extends \Illuminate\Routing\Controller
{
    use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;


    public function __construct()
    {
        $this->authorizeResource(Attempt::class);
    }

    /**
     * @param Exercise $exercise
     * @return AnonymousResourceCollection
     */
    public function __invoke(Exercise $exercise): AnonymousResourceCollection
    {
        return AttemptResource::collection($exercise->attempts);
    }
}
