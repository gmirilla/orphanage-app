<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TalentsInterest extends Model
{
    use HasFactory;

    protected $fillable = [
        'child_id',
        'category',
        'talent_name',
        'description',
        'level',
        'is_active',
        'recorded_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
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
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    // Accessors
    public function getCategoryLabelAttribute()
    {
        $categories = [
            'art' => 'Art & Crafts',
            'music' => 'Music',
            'sports' => 'Sports & Physical',
            'academics' => 'Academics',
            'technical' => 'Technical Skills',
            'social' => 'Social Skills',
        ];

        return $categories[$this->category] ?? ucfirst($this->category);
    }

    public function getLevelLabelAttribute()
    {
        $levels = [
            'beginner' => 'Beginner',
            'intermediate' => 'Intermediate',
            'advanced' => 'Advanced',
        ];

        return $levels[$this->level] ?? ucfirst($this->level);
    }
}