<?php

namespace App\Policies;

use App\Models\Facility;
use App\Models\User;

class FacilityPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'manager', 'caregiver', 'nurse', 'teacher']);
    }

    public function view(User $user, Facility $facility): bool
    {
        return in_array($user->role, ['admin', 'manager', 'caregiver', 'nurse', 'teacher']);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }

    public function update(User $user, Facility $facility): bool
    {
        return in_array($user->role, ['admin', 'manager'])
            || $facility->managed_by === $user->id;
    }

    public function delete(User $user, Facility $facility): bool
    {
        return $user->role === 'admin';
    }
}
