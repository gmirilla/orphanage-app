<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Facility extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'description',
        'capacity',
        'is_active',
        'managed_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'capacity' => 'integer',
    ];

    // Relationships
    public function managedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'managed_by');
    }

    public function roomAllocations(): HasMany
    {
        return $this->hasMany(RoomAllocation::class, 'facility_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(ChildRoomAssignment::class, 'facility_id')->where('unassigned_date', null);
    }


    public function maintenanceRequests(): HasMany
    {
        return $this->hasMany(MaintenanceRequest::class, 'facility_id');
    }

    public function activeRoomAllocations()
    {
        return $this->roomAllocations()->where('is_active', true);
    }

    public function getTotalBedsAttribute()
    {
        return $this->activeRoomAllocations()->sum('bed_count');
    }

    public function getOccupiedBedsAttribute()
    {
        return $this->activeRoomAllocations()->sum('occupied_beds');
    }

    public function getAvailableBedsAttribute()
    {
        return $this->total_beds - $this->occupied_beds;
    }

    public function getOccupancyRateAttribute()
    {
        if ($this->total_beds == 0) return 0;
        return round(($this->occupied_beds / $this->total_beds) * 100, 1);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeDormitories($query)
    {
        return $query->where('type', 'dormitory');
    }

    public function scopeClassrooms($query)
    {
        return $query->where('type', 'classroom');
    }

    public function scopeMedicalRooms($query)
    {
        return $query->where('type', 'medical_room');
    }

    // Methods
    public function getMaintenanceRequests($status = null)
    {
        $query = $this->maintenanceRequests();

        if ($status) {
            $query->where('status', $status);
        }

        return $query->orderBy('priority', 'desc')
                    ->orderBy('requested_date', 'desc')
                    ->get();
    }

    public function getPendingMaintenanceRequests()
    {
        return $this->getMaintenanceRequests('pending');
    }

    public function getUrgentMaintenanceRequests()
    {
        return $this->maintenanceRequests()
            ->where('priority', 'urgent')
            ->where('status', '!=', 'completed')
            ->orderBy('requested_date', 'desc')
            ->get();
    }
}