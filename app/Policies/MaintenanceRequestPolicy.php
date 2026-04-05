<?php

namespace App\Policies;

use App\Models\MaintenanceRequest;
use App\Models\User;

class MaintenanceRequestPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // All staff can view their own; admins see all (filtered in controller)
    }

    public function view(User $user, MaintenanceRequest $request): bool
    {
        return $user->role === 'admin'
            || $user->role === 'manager'
            || $request->requested_by === $user->id
            || $request->assigned_to === $user->id;
    }

    public function create(User $user): bool
    {
        return true; // Any authenticated user can submit a request
    }

    public function update(User $user, MaintenanceRequest $request): bool
    {
        return in_array($user->role, ['admin', 'manager'])
            || $request->requested_by === $user->id;
    }

    public function delete(User $user, MaintenanceRequest $request): bool
    {
        return $user->role === 'admin';
    }

    public function assign(User $user, MaintenanceRequest $request): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }

    public function updateStatus(User $user, MaintenanceRequest $request): bool
    {
        return in_array($user->role, ['admin', 'manager'])
            || $request->assigned_to === $user->id;
    }
}
