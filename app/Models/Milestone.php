<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Milestone extends Model
{
    use HasFactory;

    protected $fillable = [
        'child_id',
        'type',
        'title',
        'description',
        'date_recorded',
        'data',
        'requires_attention',
        'recorded_by',
    ];

    protected $casts = [
        'date_recorded' => 'date',
        'data' => 'array',
        'requires_attention' => 'boolean',
    ];

    // Relationships
    public function child(): BelongsTo
    {
        return $this->belongsTo(Child::class, 'child_id');
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    // Scopes
    public function scopeGrowth($query)
    {
        return $query->where('type', 'growth');
    }

    public function scopeAchievement($query)
    {
        return $query->where('type', 'achievement');
    }

    public function scopeMedical($query)
    {
        return $query->where('type', 'medical');
    }

    public function scopeBehavioral($query)
    {
        return $query->where('type', 'behavioral');
    }

    public function scopeRequiresAttention($query)
    {
        return $query->where('requires_attention', true);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date_recorded', [$startDate, $endDate]);
    }

    // Accessors
    public function getTypeLabelAttribute()
    {
        $types = [
            'growth' => 'Growth & Development',
            'achievement' => 'Achievement',
            'medical' => 'Medical',
            'behavioral' => 'Behavioral',
        ];

        return $types[$this->type] ?? $this->type;
    }

    public function getFormattedDataAttribute()
    {
        if (!$this->data) return null;
        
        $formatted = [];
        foreach ($this->data as $key => $value) {
            $formatted[ucwords(str_replace('_', ' ', $key))] = $value;
        }
        
        return $formatted;
    }
}