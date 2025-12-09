<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VolunteerTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'volunteer_id',
        'title',
        'description',
        'scheduled_date',
        'start_time',
        'end_time',
        'status',
        'feedback',
        'rating',
        'assigned_by',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'rating' => 'integer',
    ];

    // Relationships
    public function volunteer(): BelongsTo
    {
        return $this->belongsTo(Volunteer::class, 'volunteer_id');
    }

    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
