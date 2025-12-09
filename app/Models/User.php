<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'address',
        'date_of_birth',
        'gender',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }
      // Relationships
    public function children()
    {
        return $this->hasMany(Child::class, 'admitted_by');
    }

    public function talentsInterests()
    {
        return $this->hasMany(TalentsInterest::class, 'recorded_by');
    }

    public function admissionLogs()
    {
        return $this->hasMany(AdmissionLog::class, 'processed_by');
    }

    public function milestones()
    {
        return $this->hasMany(Milestone::class, 'recorded_by');
    }

    public function facilitiesManaged()
    {
        return $this->hasMany(Facility::class, 'managed_by');
    }

    public function childRoomAssignments()
    {
        return $this->hasMany(ChildRoomAssignment::class, 'assigned_by');
    }

    public function maintenanceRequests()
    {
        return $this->hasMany(MaintenanceRequest::class, 'requested_by');
    }

    public function maintenanceRequestsAssigned()
    {
        return $this->hasMany(MaintenanceRequest::class, 'assigned_to');
    }

    public function staffProfile()
    {
        return $this->hasOne(StaffProfile::class, 'user_id');
    }

    public function shiftSchedules()
    {
        return $this->hasMany(ShiftSchedule::class, 'staff_id');
    }

    public function shiftSchedulesScheduled()
    {
        return $this->hasMany(ShiftSchedule::class, 'scheduled_by');
    }

    public function volunteerProfile()
    {
        return $this->hasOne(Volunteer::class, 'user_id');
    }

    public function volunteerTasks()
    {
        return $this->hasMany(VolunteerTask::class, 'volunteer_id');
    }

    public function volunteerTasksAssigned()
    {
        return $this->hasMany(VolunteerTask::class, 'assigned_by');
    }

    public function donorsManaged()
    {
        return $this->hasMany(Donor::class, 'managed_by');
    }

    public function donations()
    {
        return $this->hasMany(Donation::class, 'recorded_by');
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'uploaded_by');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class, 'user_id');
    }

    // Role-based access control
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isCaregiver()
    {
        return $this->role === 'caregiver';
    }

    public function isNurse()
    {
        return $this->role === 'nurse';
    }

    public function isTeacher()
    {
        return $this->role === 'teacher';
    }

    public function isVolunteer()
    {
        return $this->role === 'volunteer';
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeCaregivers($query)
    {
        return $query->where('role', 'caregiver');
    }

    public function scopeNurses($query)
    {
        return $query->where('role', 'nurse');
    }

    public function scopeTeachers($query)
    {
        return $query->where('role', 'teacher');
    }

    public function scopeVolunteers($query)
    {
        return $query->where('role', 'volunteer');
    }
}
