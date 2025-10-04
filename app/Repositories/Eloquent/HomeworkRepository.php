<?php

namespace App\Repositories\Eloquent;

use App\Http\Resources\UserResource;
use App\Models\Homework;
use App\Repositories\Contracts\HomeworkRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HomeworkRepository implements HomeworkRepositoryInterface
{
    protected string $model;

    public function __construct()
    {
        $this->model = 'Homework';
    }

    public function getAll()
    {
        return Homework::get();
    }

    public function paginate(Request $request)
    {
        $rows = Homework::query()->with('exercises');

        $user = Auth::user();
        $roles = $user->roles->map(function ($item) {
            return $item->name;
        })->toArray();

        if (in_array('teacher', $roles)) {
            $rows->where('teacher_id', $user->id);
        } else {
            $rows->where('student_id', $user->id);
        }

        $rows->where(function ($query) use ($request) {
            $query->orWhere('homeworks.id', 'LIKE', '%' . $request->filter . '%')
                ->orWhere('homeworks.title', 'LIKE', '%' . $request->filter . '%');
        });

        $rows->orderBy($request->order, $request->sort);

        return $rows->paginate($request->limit);
    }

    public function findById(int $id)
    {
        return Homework::findOrFail($id);
    }

    public function loadRelations(Homework $homework)
    {
        return $homework->load(['exercises.attempts', 'teacher', 'student']);
    }

    public function create(array $data)
    {
        try {
            return Homework::create($data + ['teacher_id' => Auth::id()]);
        } catch (\Exception $e) {
            Log::error('Błąd podczas tworzenia ' . $this->model, [
                'data' => $data,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function update(Homework $homework, array $data): mixed
    {
        try {
            $homework->update($data);
            return $homework->fresh();
        } catch (\Exception $e) {
            Log::error('Błąd podczas aktualizacji ' . $this->model, [
                'data' => $data,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function delete(Homework $homework)
    {
        try {
            $homework->delete();
            return true;
        } catch (\Exception $e) {
            Log::error('Błąd usuwania ' . $this->model, [
                'instance_id' => $homework->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function hasFinishedAllExercises(Homework $homework): bool
    {
        return $homework
                ->exercises()
                ->whereNull('complete_date')
                ->count() === 0;
    }
}
