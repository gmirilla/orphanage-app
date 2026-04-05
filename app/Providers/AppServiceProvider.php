<?php

namespace App\Providers;

use App\Models\Child;
use App\Models\Donor;
use App\Models\Facility;
use App\Models\MaintenanceRequest;
use App\Observers\AuditObserver;
use App\Policies\ChildPolicy;
use App\Policies\DonorPolicy;
use App\Policies\FacilityPolicy;
use App\Policies\MaintenanceRequestPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Register audit observers for key models
        Child::observe(AuditObserver::class);
        Donor::observe(AuditObserver::class);
        Facility::observe(AuditObserver::class);
        MaintenanceRequest::observe(AuditObserver::class);

        Gate::policy(Child::class, ChildPolicy::class);
        Gate::policy(Donor::class, DonorPolicy::class);
        Gate::policy(Facility::class, FacilityPolicy::class);
        Gate::policy(MaintenanceRequest::class, MaintenanceRequestPolicy::class);

        // Admin gate for convenience
        Gate::define('admin', fn($user) => $user->role === 'admin');
        Gate::define('admin-or-manager', fn($user) => in_array($user->role, ['admin', 'manager']));
    }
}
