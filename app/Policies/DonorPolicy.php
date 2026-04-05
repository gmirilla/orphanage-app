<?php

namespace App\Policies;

use App\Models\Donor;
use App\Models\User;

class DonorPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }

    public function view(User $user, Donor $donor): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }

    public function update(User $user, Donor $donor): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }

    public function delete(User $user, Donor $donor): bool
    {
        return $user->role === 'admin';
    }
}
