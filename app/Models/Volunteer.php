<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Volunteer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'volunteer_id',
        'registration_date',
        'approval_date',
        'status',
        'skills',
        'availability',
        'background_check_info',
        'approved_by',
    ];

    protected $casts = [
        'registration_date' => 'date',
        'approval_date' => 'date',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(VolunteerTask::class, 'volunteer_id');
    }

    public function getTasksThisWeek()
    {
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();

        return $this->tasks()
            ->whereBetween('scheduled_date', [$startOfWeek, $endOfWeek])
            ->orderBy('scheduled_date')
            ->get();
    }

    public function getCompletedTasksThisMonth()
    {
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        return $this->tasks()
            ->where('status', 'completed')
            ->whereBetween('scheduled_date', [$startOfMonth, $endOfMonth])
            ->get();
    }

    public function getTotalTasksCompleted()
    {
        return $this->tasks()->where('status', 'completed')->count();
    }

    public function getAverageRating()
    {
        $tasksWithRating = $this->tasks()->whereNotNull('rating')->get();
        
        if ($tasksWithRating->count() == 0) return 0;
        
        return round($tasksWithRating->avg('rating'), 1);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['approved']);
    }

    public function scopeSuspended($query)
    {
        return $query->where('status', 'suspended');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return $this->user->name;
    }

    public function getStatusLabelAttribute()
    {
        $statuses = [
            'pending' => 'Pending Approval',
            'approved' => 'Approved',
            'suspended' => 'Suspended',
            'inactive' => 'Inactive',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    public function getIsActiveAttribute()
    {
        return $this->status === 'approved';
    }

    // Methods
    public function approve($approvedBy)
    {
        $this->update([
            'status' => 'approved',
            'approval_date' => now(),
            'approved_by' => $approvedBy,
        ]);
    }

    public function suspend()
    {
        $this->update(['status' => 'suspended']);
    }

    public function reactivate()
    {
        $this->update(['status' => 'approved']);
    }

    public function deactivate()
    {
        $this->update(['status' => 'inactive']);
    }
}