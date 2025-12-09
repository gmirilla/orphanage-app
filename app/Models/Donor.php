<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Donor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'donor_type',
        'tax_id',
        'preferences',
        'status',
        'managed_by',
    ];

    // Relationships
    public function managedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'managed_by');
    }

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class, 'donor_id');
    }

    public function getTotalDonationsThisYear()
    {
        return $this->donations()
            ->where('status', 'received')
            ->whereYear('donation_date', now()->year)
            ->sum('amount');
    }

    public function getTotalDonationsAllTime()
    {
        return $this->donations()
            ->where('status', 'received')
            ->sum('amount');
    }

    public function getDonationCountThisYear()
    {
        return $this->donations()
            ->where('status', 'received')
            ->whereYear('donation_date', now()->year)
            ->count();
    }

    public function getLastDonationDate()
    {
        $lastDonation = $this->donations()
            ->where('status', 'received')
            ->orderBy('donation_date', 'desc')
            ->first();

        return $lastDonation ? $lastDonation->donation_date : null;
    }

    public function getDonationFrequency()
    {
        $donations = $this->donations()
            ->where('status', 'received')
            ->orderBy('donation_date', 'asc')
            ->get();

        if ($donations->count() < 2) {
            return 'irregular';
        }

        $intervals = [];
        for ($i = 1; $i < $donations->count(); $i++) {
            $current = $donations[$i]->donation_date;
            $previous = $donations[$i-1]->donation_date;
            $intervals[] = $current->diffInDays($previous);
        }

        $averageInterval = collect($intervals)->avg();
        
        if ($averageInterval < 45) {
            return 'monthly';
        } elseif ($averageInterval < 100) {
            return 'quarterly';
        } elseif ($averageInterval < 200) {
            return 'biannually';
        } else {
            return 'annually';
        }
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function scopePreferred($query)
    {
        return $query->where('status', 'preferred');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('donor_type', $type);
    }

    public function scopeIndividuals($query)
    {
        return $query->where('donor_type', 'individual');
    }

    public function scopeOrganizations($query)
    {
        return $query->where('donor_type', 'organization');
    }

    public function scopeCorporate($query)
    {
        return $query->where('donor_type', 'corporate');
    }

    // Accessors
    public function getStatusLabelAttribute()
    {
        $statuses = [
            'active' => 'Active',
            'inactive' => 'Inactive',
            'preferred' => 'Preferred',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    public function getDonorTypeLabelAttribute()
    {
        $types = [
            'individual' => 'Individual',
            'organization' => 'Organization',
            'corporate' => 'Corporate',
        ];

        return $types[$this->donor_type] ?? $this->donor_type;
    }

    public function getFormattedPreferencesAttribute()
    {
        if (!$this->preferences) return null;
        
        // Parse JSON preferences for display
        $preferences = json_decode($this->preferences, true);
        return is_array($preferences) ? $preferences : null;
    }

    // Methods
    public function addDonation($type, $amount = null, $description = null, $date = null)
    {
        $receiptNumber = 'RCP-' . strtoupper(uniqid());
        $user=Auth::user();
        return $this->donations()->create([
            'donation_type' => $type,
            'amount' => $amount,
            'description' => $description,
            'donation_date' => $date ?? now(),
            'receipt_number' => $receiptNumber,
            'status' => 'received',
            'recorded_by' => $user->id,
        ]);
    }

    public function markAsPreferred()
    {
        $this->update(['status' => 'preferred']);
    }

    public function markAsInactive()
    {
        $this->update(['status' => 'inactive']);
    }
}