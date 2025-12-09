<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'facility_id',
        'title',
        'description',
        'priority',
        'status',
        'resolution',
        'estimated_cost',
        'actual_cost',
        'requested_date',
        'due_date',
        'completed_date',
        'requested_by',
        'assigned_to',
    ];

    protected $casts = [
        'requested_date' => 'date',
        'due_date' => 'date',
        'completed_date' => 'date',
        'estimated_cost' => 'decimal:2',
        'actual_cost' => 'decimal:2',
    ];

    // Relationships
    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class, 'facility_id');
    }

    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeUrgent($query)
    {
        return $query->where('priority', 'urgent');
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                    ->where('status', '!=', 'completed');
    }

    // Accessors
    public function getPriorityLabelAttribute()
    {
        $priorities = [
            'low' => 'Low',
            'medium' => 'Medium',
            'high' => 'High',
            'urgent' => 'Urgent',
        ];

        return $priorities[$this->priority] ?? $this->priority;
    }

    public function getStatusLabelAttribute()
    {
        $statuses = [
            'pending' => 'Pending',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    public function getIsOverdueAttribute()
    {
        return $this->due_date && $this->due_date < now() && $this->status !== 'completed';
    }

    public function getFormattedEstimatedCostAttribute()
    {
        return $this->estimated_cost ? '$' . number_format($this->estimated_cost, 2) : 'TBD';
    }

    public function getFormattedActualCostAttribute()
    {
        return $this->actual_cost ? '$' . number_format($this->actual_cost, 2) : 'N/A';
    }
}