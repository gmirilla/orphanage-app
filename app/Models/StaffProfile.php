<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'employee_id',
        'position',
        'department',
        'hire_date',
        'employment_type',
        'salary',
        'qualifications',
        'responsibilities',
        'emergency_contact_name',
        'emergency_contact_phone',
    ];

    protected $casts = [
        'hire_date' => 'date',
        'salary' => 'decimal:2',
    ];

   // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function shiftSchedules()
    {
        return $this->hasMany(ShiftSchedule::class, 'staff_id');
    }

    public function getShiftsThisWeek()
    {
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();

        return $this->user->shiftSchedules()
            ->whereBetween('shift_date', [$startOfWeek, $endOfWeek])
            ->orderBy('shift_date')
            ->get();
    }

    public function getCompletedShiftsThisWeek()
    {
        return $this->getShiftsThisWeek()->where('status', 'completed');
    }

    public function getHoursWorkedThisWeek()
    {
        $completedShifts = $this->getCompletedShiftsThisWeek();
        $totalMinutes = 0;

        foreach ($completedShifts as $shift) {
            $start = \Carbon\Carbon::parse($shift->shift_date->format('Y-m-d') . ' ' . $shift->start_time);
            $end = \Carbon\Carbon::parse($shift->shift_date->format('Y-m-d') . ' ' . $shift->end_time);
            $totalMinutes += $end->diffInMinutes($start);
        }

        return round($totalMinutes / 60, 1); // Return hours as decimal
    }

    // Scopes
    public function scopeByDepartment($query, $department)
    {
        return $query->where('department', $department);
    }

    public function scopeByPosition($query, $position)
    {
        return $query->where('position', 'like', '%' . $position . '%');
    }

    public function scopeFullTime($query)
    {
        return $query->where('employment_type', 'full_time');
    }

    public function scopePartTime($query)
    {
        return $query->where('employment_type', 'part_time');
    }

    public function scopeContract($query)
    {
        return $query->where('employment_type', 'contract');
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return $this->user->name;
    }

    public function getYearsOfServiceAttribute()
    {
        return $this->hire_date->diffInYears(now());
    }

    public function getEmploymentTypeLabelAttribute()
    {
        $types = [
            'full_time' => 'Full Time',
            'part_time' => 'Part Time',
            'contract' => 'Contract',
        ];

        return $types[$this->employment_type] ?? $this->employment_type;
    }
}