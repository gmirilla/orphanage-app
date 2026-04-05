<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomAllocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'facility_id',
        'room_number',
        'bed_count',
        'occupied_beds',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'occupied_beds' => 'integer',
        'bed_count' => 'integer',
    ];

    // Relationships
    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class, 'facility_id');
    }

    public function childAssignments()
    {
        return $this->hasMany(ChildRoomAssignment::class, 'room_allocation_id');
    }

    public function currentAssignments()
    {
        return $this->childAssignments()->whereNull('unassigned_date');
    }

    // Accessors
    public function getAvailableBedsAttribute()
    {
        return $this->bed_count - $this->occupied_beds;
    }

    public function getOccupancyRateAttribute()
    {
        if ($this->bed_count == 0) return 0;
        return round(($this->occupied_beds / $this->bed_count) * 100, 1);
    }

    public function children()
    {
        return $this->hasManyThrough(
            Child::class,
            ChildRoomAssignment::class,
            'room_allocation_id',
            'id',
            'id',
            'child_id'
        )->whereNull('child_room_assignments.unassigned_date');
    }
}

