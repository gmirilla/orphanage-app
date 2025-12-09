<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'donor_id',
        'donation_type',
        'amount',
        'description',
        'currency',
        'donation_date',
        'status',
        'receipt_number',
        'notes',
        'recorded_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'donation_date' => 'date',
    ];

    // Relationships
    public function donor(): BelongsTo
    {
        return $this->belongsTo(Donor::class, 'donor_id');
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    // Scopes
    public function scopeReceived($query)
    {
        return $query->where('status', 'received');
    }

    public function scopePledged($query)
    {
        return $query->where('status', 'pledged');
    }

    public function scopeThisYear($query)
    {
        return $query->whereYear('donation_date', now()->year);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('donation_date', now()->month)
                    ->whereYear('donation_date', now()->year);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('donation_type', $type);
    }

    // Accessors
    public function getFormattedAmountAttribute()
    {
        return '$' . number_format($this->amount, 2);
    }

    public function getStatusLabelAttribute()
    {
        $statuses = [
            'pledged' => 'Pledged',
            'received' => 'Received',
            'cancelled' => 'Cancelled',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    public function getDonationTypeLabelAttribute()
    {
        $types = [
            'cash' => 'Cash',
            'material' => 'Materials/Goods',
            'service' => 'Services',
        ];

        return $types[$this->donation_type] ?? $this->donation_type;
    }
}