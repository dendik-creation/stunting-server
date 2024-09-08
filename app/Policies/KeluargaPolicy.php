<?php

namespace App\Policies;

use App\Models\User;

class KeluargaPolicy
{
    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user)
    {
        return true;
    }

    public function create(User $user)
    {
        return $user->role === 'admin' || $user->role == 'operator';
    }

    public function update(User $user)
    {
        return $user->role === 'admin' || $user->role == 'operator';
    }

    public function delete(User $user)
    {
        return $user->role === 'admin' || $user->role == 'operator';
    }
}
