<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChildRoomAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'child_id',
        'room_allocation_id',
        'assigned_date',
        'unassigned_date',
        'notes',
        'assigned_by',
    ];

    protected $casts = [
        'assigned_date' => 'date',
        'unassigned_date' => 'date',
    ];

    // Relationships
    public function child(): BelongsTo
    {
        return $this->belongsTo(Child::class, 'child_id');
    }

    public function roomAllocation(): BelongsTo
    {
        return $this->belongsTo(RoomAllocation::class, 'room_allocation_id');
    }

    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    // Accessors
    public function getIsCurrentAttribute()
    {
        return is_null($this->unassigned_date);
    }
}


