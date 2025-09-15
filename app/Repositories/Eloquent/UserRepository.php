<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UserRepository implements UserRepositoryInterface
{
    protected string $model;

    public function __construct()
    {
        $this->model = 'User';
    }

    public function getStudentsToSelect()
    {
        return User::whereHas('roles', function ($query) {
            $query->where('name', 'student');
        })
            ->select('id', 'name')
            ->get()
            ->toArray();
    }

    /**
     * @return mixed
     */
    public function getAll(): mixed
    {
        return User::get();
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function findById(int $id): mixed
    {
        return User::findOrFail($id);
    }

    /**
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function paginate(Request $request): LengthAwarePaginator
    {
        $rows = User::query();

        $rows->where(function ($query) use ($request) {
            $query->orWhere('users.id', 'LIKE', '%' . $request->filter . '%')
                ->orWhere('users.name', 'LIKE', '%' . $request->filter . '%')
                ->orWhere('users.email', 'LIKE', '%' . $request->filter . '%');
        });

        $rows->orderBy($request->order, $request->sort);

        return $rows->paginate($request->limit);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data): mixed
    {
        try {
            $role = Role::firstOrCreate(['name' => $data['role']]);
            unset($data['role']);

            $user = User::create($data);
            $user->assignRole($role);

            return $user;
        } catch (\Exception $e) {
            Log::error('Błąd podczas tworzenia ' . $this->model, [
                'data' => $data,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * @param User $user
     * @param array $data
     * @return mixed
     */
    public function update(User $user, array $data): mixed
    {
        try {
            if (!isset($data['password'])) {
                unset($data['password']);
            }

            $role = Role::firstOrCreate(['name' => $data['role']]);
            unset($data['role']);

            $user->update($data);
            $user->syncRoles($role);

            return $user->fresh();
        } catch (\Exception $e) {
            Log::error('Błąd podczas aktualizacji ' . $this->model, [
                'data' => $data,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function delete(User $user)
    {
        try {
            $user->delete();
            return true;
        } catch (\Exception $e) {
            Log::error('Błąd usuwania ' . $this->model, [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * @param array $ids
     * @return mixed
     */
    public function deleteByArray(array $ids): mixed
    {
        if (empty($ids) || isset($ids['__v_isShallow']) || isset($ids['__v_isRef'])) {
            Log::warning('Próba usunięcia ' . $this->model . ' bez poprawnej listy ID', ['ids' => $ids]);
            return false;
        }

        try {
            DB::transaction(function () use ($ids) {
                $users = User::whereIn('id', $ids)->get();
                foreach ($users as $user) {
                    $this->delete($user);
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
