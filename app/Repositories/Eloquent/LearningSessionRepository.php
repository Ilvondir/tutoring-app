<?php

namespace App\Repositories\Eloquent;

use App\Models\LearningSession;
use App\Models\User;
use App\Repositories\Contracts\LearningSessionInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LearningSessionRepository implements LearningSessionInterface
{
    protected string $model;

    public function __construct()
    {
        $this->model = 'LearningSession';
    }

    public function create(array $data)
    {
        try {
            return LearningSession::create($data + ['user_id' => Auth::id()]);
        } catch (\Exception $e) {
            Log::error('Błąd podczas tworzenia ' . $this->model, [
                'data' => $data,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
