<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Requisition extends Model
{
    protected $fillable = [
        'title', 'requisition_type', 'description', 'justification',
        'amount', 'currency', 'priority', 'needed_by_date', 'status',
        'submitted_by', 'submitted_at',
        'reviewed_by', 'reviewed_at', 'review_notes',
    ];

    protected $casts = [
        'amount'         => 'decimal:2',
        'needed_by_date' => 'date',
        'submitted_at'   => 'datetime',
        'reviewed_at'    => 'datetime',
    ];

    public static array $types = [
        'office_supplies'    => 'Office Supplies',
        'equipment'          => 'Equipment / Technology',
        'maintenance_repair' => 'Maintenance & Repairs',
        'travel'             => 'Travel & Transport',
        'training'           => 'Training & Development',
        'medical'            => 'Medical & Healthcare',
        'food_provisions'    => 'Food & Provisions',
        'clothing'           => 'Clothing & Uniforms',
        'utilities'          => 'Utilities',
        'other'              => 'Other',
    ];

    public static array $priorities = [
        'low'    => 'Low',
        'medium' => 'Medium',
        'high'   => 'High',
        'urgent' => 'Urgent',
    ];

    // Relationships
    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(RequisitionDocument::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(RequisitionAuditLog::class)->latest();
    }

    // Status helpers
    public function isDraft(): bool     { return $this->status === 'draft'; }
    public function isSubmitted(): bool { return $this->status === 'submitted'; }
    public function isApproved(): bool  { return $this->status === 'approved'; }
    public function isRejected(): bool  { return $this->status === 'rejected'; }

    public function isEditable(): bool
    {
        return in_array($this->status, ['draft', 'rejected']);
    }

    // Scopes
    public function scopePendingReview($query)
    {
        return $query->where('status', 'submitted');
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeOwnedBy($query, int $userId)
    {
        return $query->where('submitted_by', $userId);
    }

    // Audit helper
    public function logAction(string $action, int $performedBy, ?string $oldStatus = null, ?string $newStatus = null, ?string $notes = null, ?string $ip = null): void
    {
        $this->auditLogs()->create([
            'performed_by' => $performedBy,
            'action'       => $action,
            'old_status'   => $oldStatus,
            'new_status'   => $newStatus,
            'notes'        => $notes,
            'ip_address'   => $ip,
        ]);
    }
}
