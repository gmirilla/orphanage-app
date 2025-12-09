<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShiftSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id',
        'shift_date',
        'start_time',
        'end_time',
        'shift_type',
        'status',
        'notes',
        'scheduled_by',
    ];

    protected $casts = [
        'shift_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    // Relationships
    public function staff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function scheduledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'scheduled_by');
    }
}
