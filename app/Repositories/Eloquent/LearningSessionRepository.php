<?php

namespace App\Repositories\Eloquent;

use App\Models\LearningSession;
use App\Models\User;
use App\Repositories\Contracts\LearningSessionInterface;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LearningSessionRepository implements LearningSessionInterface
{
    protected string $model;

    public function __construct()
    {
        $this->model = 'LearningSession';
    }

    /**
     * @param array $data
     * @return mixed
     * @throws \Exception
     */
    public function create(array $data): mixed
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

    /**
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function paginate(Request $request): LengthAwarePaginator
    {
        $rows = LearningSession::query()
            ->with('user')
            ->where(function ($query) use ($request) {
                $query->orWhere('learning_sessions.id', 'LIKE', '%' . $request->filter . '%')
                    ->orWhere('learning_sessions.created_at', 'LIKE', '%' . $request->filter . '%')
                    ->orWhereHas('user', function ($q) use ($request) {
                        $q->where('name', 'LIKE', '%' . $request->filter . '%');
                    });
            });

        $order = $request->order === 'id' ? 'learning_sessions.id' : $request->order;

        $rows->orderBy($order, $request->sort);

        return $rows->paginate($request->limit);
    }

    /**
     * @param LearningSession $learningSession
     * @return true
     */
    public function delete(LearningSession $learningSession): true
    {
        try {
            $learningSession->delete();
            return true;
        } catch (\Exception $e) {
            Log::error('Błąd usuwania ' . $this->model, [
                'learning_session' => $learningSession->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * @param array $ids
     * @return bool
     * @throws \Exception
     */
    public function deleteByArray(array $ids): bool
    {
        if (empty($ids) || isset($ids['__v_isShallow']) || isset($ids['__v_isRef'])) {
            Log::warning('Próba usunięcia ' . $this->model . ' bez poprawnej listy ID', ['ids' => $ids]);
            return false;
        }

        try {
            DB::transaction(function () use ($ids) {
                $items = LearningSession::whereIn('id', $ids)->get();
                foreach ($items as $item) {
                    $this->delete($item);
                }
            });
            return true;
        } catch (\Exception $e) {
            Log::error('Błąd masowego usuwania ' . $this->model, [
                'ids' => $ids,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
