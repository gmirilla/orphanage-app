<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'file_path',
        'file_name',
        'file_size',
        'mime_type',
        'description',
        'tags',
        'related_type',
        'related_id',
        'visibility',
        'uploaded_by',
    ];

    protected $casts = [
        'tags' => 'array',
    ];

    // Relationships
    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // Scopes
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeForRelated($query, $type, $id)
    {
        return $query->where('related_type', $type)
                    ->where('related_id', $id);
    }

    public function scopePublic($query)
    {
        return $query->where('visibility', 'public');
    }

    public function scopePrivate($query)
    {
        return $query->where('visibility', 'private');
    }
}
