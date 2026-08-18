<?php

namespace App\Services\Administration;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UserService
{
    public function all()
    {
        return User::with([
            'roles',
            'politicalParties',
        ])
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
            $politicalPartyId = $data['political_party_id'] ?? null;

            unset(
                $data['role'],
                $data['political_party_id']
            );

            $user = User::create($data);

            $user->syncRoles([$role]);

            if ($role === 'Political Party Officer' && $politicalPartyId) {
                $user->politicalParties()->sync([
                    $politicalPartyId => [
                        'is_active' => true,
                    ],
                ]);
            }

            return $user->refresh();
        });
    }

    public function update(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {

            $role = $data['role'];
            $politicalPartyId = $data['political_party_id'] ?? null;

            unset(
                $data['role'],
                $data['political_party_id']
            );

            if (empty($data['password'])) {
                unset($data['password']);
            }

            $user->update($data);

            $user->syncRoles([$role]);

            if ($role === 'Political Party Officer') {

                if ($politicalPartyId) {
                    $user->politicalParties()->sync([
                        $politicalPartyId => [
                            'is_active' => true,
                        ],
                    ]);
                } else {
                    $user->politicalParties()->detach();
                }

            } else {
                // Non-party users should not remain attached to a political party.
                $user->politicalParties()->detach();
            }

            return $user->refresh();
        });
    }
}
