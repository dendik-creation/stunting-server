<?php

namespace App\Policies;

use App\Models\User;

class PenyakitPolicy
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
        return $user->role === 'admin';
    }

    public function update(User $user)
    {
        return $user->role === 'admin';
    }

    public function delete(User $user)
    {
        return $user->role === 'admin';
    }
}
