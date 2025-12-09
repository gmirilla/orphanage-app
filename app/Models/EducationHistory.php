<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EducationHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'child_id',
        'school_name',
        'education_level',
        'academic_progress',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // Relationships
    public function child(): BelongsTo
    {
        return $this->belongsTo(Child::class, 'child_id');
    }

    // Scopes
    public function scopeCurrent($query)
    {
        return $query->where('status', 'enrolled');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeByLevel($query, $level)
    {
        return $query->where('education_level', $level);
    }

    // Accessors
    public function getStatusLabelAttribute()
    {
        $statuses = [
            'enrolled' => 'Currently Enrolled',
            'completed' => 'Completed',
            'dropped' => 'Dropped Out',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    public function getDurationAttribute()
    {
        return $this->start_date->diffInYears($this->end_date ?? now()) . ' years';
    }
}

