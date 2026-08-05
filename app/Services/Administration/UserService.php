<?php

namespace App\Services\Administration;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UserService
{
    public function all()
    {
        return User::with('roles')
            ->orderBy('name')
            ->paginate(15);
    }

    public function roles()
    {
        return Role::orderBy('name')->get();
    }

    public function create(array $data): User
    {
        return DB::transaction(function () use ($data) {

            $role = $data['role'];
            unset($data['role']);

            $user = User::create($data);

            $user->syncRoles([$role]);

            return $user;
        });
    }

    public function update(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {

            $role = $data['role'];
            unset($data['role']);

            if (empty($data['password'])) {
                unset($data['password']);
            }

            $user->update($data);

            $user->syncRoles([$role]);

            return $user->refresh();
        });
    }
}
