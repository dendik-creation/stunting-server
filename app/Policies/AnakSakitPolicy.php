<?php

namespace App\Policies;

use App\Models\User;

class AnakSakitPolicy
{
    public function viewAny(User $user)
    {
        return $user->role === 'admin' || $user->role == 'operator';
    }

    public function view(User $user)
    {
        return $user->role === 'admin' || $user->role == 'operator';
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
