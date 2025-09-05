<?php

namespace App\Repositories\Eloquent;

use App\Models\LearningSession;
use App\Models\User;
use App\Repositories\Contracts\LearningSessionInterface;
use Illuminate\Http\Request;
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

    public function paginate(Request $request)
    {
        $rows = LearningSession::query()->with('user');

        $rows->join('users', 'users.id', '=', 'learning_sessions.user_id')
            ->where(function ($query) use ($request) {
                $query->orWhere('learning_sessions.id', 'LIKE', '%' . $request->filter . '%')
                    ->orWhere('learning_sessions.created_at', 'LIKE', '%' . $request->filter . '%')
                    ->orWhere('users.name', 'LIKE', '%' . $request->filter . '%');
            });

        $order = $request->order === 'id' ? 'learning_sessions.id' : $request->order;

        $rows->orderBy($order, $request->sort);

        return $rows->paginate($request->limit);
    }
}
