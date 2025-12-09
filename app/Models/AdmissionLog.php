<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdmissionLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'child_id',
        'intake_record',
        'source_details',
        'supporting_documents',
        'medical_history',
        'social_history',
        'processed_by',
    ];

    protected $casts = [
        'supporting_documents' => 'array',
        'medical_history' => 'array',
        'social_history' => 'array',
    ];

    // Relationships
    public function child(): BelongsTo
    {
        return $this->belongsTo(Child::class, 'child_id');
    }

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}

