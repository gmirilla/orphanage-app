<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    protected $fillable = [
        'title',
        'report_type',
        'classification',
        'content',
        'file_path',
        'file_original_name',
        'status',
        'period_start',
        'period_end',
        'submitted_by',
        'submitted_at',
        'reviewed_by',
        'reviewed_at',
        'review_notes',
    ];

    protected $casts = [
        'period_start'  => 'date',
        'period_end'    => 'date',
        'submitted_at'  => 'datetime',
        'reviewed_at'   => 'datetime',
    ];

    public static array $reportTypes = [
        'weekly'     => 'Weekly',
        'monthly'    => 'Monthly',
        'quarterly'  => 'Quarterly',
        'annual'     => 'Annual',
        'incident'   => 'Incident',
        'other'      => 'Other',
    ];

    public static array $classifications = [
        'child_welfare'  => 'Child Welfare',
        'facility'       => 'Facility',
        'financial'      => 'Financial',
        'staff'          => 'Staff',
        'volunteer'      => 'Volunteer',
        'operational'    => 'Operational',
        'other'          => 'Other',
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
}
