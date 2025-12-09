<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Child extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'gender',
        'date_of_birth',
        'background_summary',
        'admission_date',
        'admission_source',
        'guardianship_status',
        'guardian_info',
        'profile_photo',
        'blood_group',
        'height_cm',
        'weight_kg',
        'special_needs',
        'is_active',
        'admitted_by',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'admission_date' => 'date',
        'height_cm' => 'decimal:2',
        'weight_kg' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function admittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admitted_by');
    }

    public function educationHistories(): HasMany
    {
        return $this->hasMany(EducationHistory::class, 'child_id');
    }

    public function talentsInterests(): HasMany
    {
        return $this->hasMany(TalentsInterest::class, 'child_id');
    }

    public function admissionLog(): HasMany
    {
        return $this->hasMany(AdmissionLog::class, 'child_id');
    }

    public function milestones(): HasMany
    {
        return $this->hasMany(Milestone::class, 'child_id');
    }

    public function currentRoomAssignment()
    {
        return $this->hasOne(ChildRoomAssignment::class, 'child_id')->whereNull('unassigned_date');
    }

    public function roomAssignments(): HasMany
    {
        return $this->hasMany(ChildRoomAssignment::class, 'child_id');
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'related');
    }

    // Accessors & Mutators
    public function getAgeAttribute()
    {
        return $this->date_of_birth->age;
    }

    public function getCurrentEducationAttribute()
    {
        return $this->educationHistories()
            ->where('status', 'enrolled')
            ->latest()
            ->first();
    }

    public function getActiveTalentsAttribute()
    {
        return $this->talentsInterests()->where('is_active', true)->get();
    }

    public function getRecentMilestonesAttribute()
    {
        return $this->milestones()
            ->orderBy('date_recorded', 'desc')
            ->limit(10)
            ->get();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByGender($query, $gender)
    {
        return $query->where('gender', $gender);
    }

    public function scopeByAge($query, $minAge, $maxAge = null)
    {
        $query->where('date_of_birth', '<=', now()->subYears($minAge));

        if ($maxAge) {
            $query->where('date_of_birth', '>=', now()->subYears($maxAge + 1));
        }

        return $query;
    }

    public function scopeByAdmissionDate($query, $startDate, $endDate = null)
    {
        $query->where('admission_date', '>=', $startDate);

        if ($endDate) {
            $query->where('admission_date', '<=', $endDate);
        }

        return $query;
    }

    // Methods
    public function updatePhysicalMeasurements($height, $weight)
    {
        $this->update([
            'height_cm' => $height,
            'weight_kg' => $weight,
        ]);

        // Create milestone for this update
        $user=Auth::user();
        $this->milestones()->create([
            'type' => 'growth',
            'title' => 'Physical Measurements Updated',
            'description' => "Height: {$height}cm, Weight: {$weight}kg",
            'date_recorded' => now(),
            'data' => [
                'height' => $height,
                'weight' => $weight,
            ],
            'recorded_by' => $user->id,
        ]);
    }

    public function recordMilestone($type, $title, $description, $data = null)
    {
        $user=Auth::user();
        return $this->milestones()->create([
            'type' => $type,
            'title' => $title,
            'description' => $description,
            'date_recorded' => now(),
            'data' => $data,
            'recorded_by' => $user->id,
        ]);
    }

    public function assignTalent($category, $talentName, $description = null, $level = 'beginner')
    {
        $user=Auth::user();
        return $this->talentsInterests()->create([
            'category' => $category,
            'talent_name' => $talentName,
            'description' => $description,
            'level' => $level,
            'recorded_by' => $user->id,
        ]);
    }
}