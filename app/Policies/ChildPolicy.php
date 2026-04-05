<?php

namespace App\Policies;

use App\Models\Child;
use App\Models\User;

class ChildPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'caregiver', 'nurse', 'teacher', 'manager']);
    }

    public function view(User $user, Child $child): bool
    {
        return in_array($user->role, ['admin', 'caregiver', 'nurse', 'teacher', 'manager']);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'caregiver', 'manager']);
    }

    public function update(User $user, Child $child): bool
    {
        return in_array($user->role, ['admin', 'caregiver', 'manager']);
    }

    public function delete(User $user, Child $child): bool
    {
        return $user->role === 'admin';
    }
}
