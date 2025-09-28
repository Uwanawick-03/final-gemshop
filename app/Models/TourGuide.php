<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TourGuide extends Model
{
    protected $fillable = [
        'guide_code', 'first_name', 'last_name', 'email', 'phone', 'address',
        'city', 'country', 'gender', 'date_of_birth', 'national_id', 'joined_date',
        'languages', 'service_areas', 'license_number', 'license_expiry', 'daily_rate',
        'employment_status', 'is_active', 'notes'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'joined_date' => 'date',
        'license_expiry' => 'date',
        'daily_rate' => 'decimal:2',
        'is_active' => 'boolean',
        'languages' => 'array',
        'service_areas' => 'array',
    ];

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getDisplayNameAttribute(): string
    {
        return "{$this->full_name} ({$this->guide_code})";
    }

    public function getEmploymentStatusBadgeAttribute(): string
    {
        $badges = [
            'active' => 'bg-success',
            'inactive' => 'bg-secondary',
            'terminated' => 'bg-danger',
            'on_leave' => 'bg-warning',
        ];
        return $badges[$this->employment_status] ?? 'bg-secondary';
    }

    public function getFormattedDailyRateAttribute(): string
    {
        return $this->daily_rate ? '$' . number_format($this->daily_rate, 2) . '/day' : 'Not set';
    }

    // Performance tracking methods
    public function getTotalToursConductedAttribute()
    {
        try {
            return $this->tours()->where('status', 'completed')->count();
        } catch (\Exception $e) {
            return 0; // Return 0 if table/columns don't exist yet
        }
    }

    public function getTotalToursInProgressAttribute()
    {
        try {
            return $this->tours()->where('status', 'in_progress')->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function getTotalToursPendingAttribute()
    {
        try {
            return $this->tours()->where('status', 'pending')->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function getTotalEarningsAttribute()
    {
        try {
            return $this->tours()->where('status', 'completed')->sum('total_amount') ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function getAverageTourRatingAttribute()
    {
        try {
            return $this->tours()->where('status', 'completed')->avg('rating') ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function getPerformanceRatingAttribute()
    {
        $completedTours = $this->total_tours_conducted;
        $avgRating = $this->average_tour_rating;
        
        // Rating based on tour completion and customer ratings
        if ($completedTours >= 100 && $avgRating >= 4.5) {
            return ['rating' => 'Master Guide', 'color' => 'success', 'icon' => 'crown'];
        } elseif ($completedTours >= 50 && $avgRating >= 4.0) {
            return ['rating' => 'Expert Guide', 'color' => 'primary', 'icon' => 'star'];
        } elseif ($completedTours >= 25 && $avgRating >= 3.5) {
            return ['rating' => 'Experienced Guide', 'color' => 'info', 'icon' => 'thumbs-up'];
        } elseif ($completedTours >= 10) {
            return ['rating' => 'Developing Guide', 'color' => 'warning', 'icon' => 'clock'];
        }
        return ['rating' => 'New Guide', 'color' => 'secondary', 'icon' => 'user'];
    }

    public function getYearsOfServiceAttribute()
    {
        if ($this->joined_date) {
            return $this->joined_date->diffInYears(now());
        }
        return 0;
    }

    public function getDaysInSystemAttribute()
    {
        if ($this->joined_date) {
            return $this->joined_date->diffInDays(now());
        }
        return 0;
    }

    public function getMonthlyEarnings($month = null, $year = null)
    {
        try {
            $month = $month ?? now()->month;
            $year = $year ?? now()->year;

            return $this->tours()
                ->where('status', 'completed')
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->sum('total_amount') ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function getFullAddressAttribute(): string
    {
        $address = (string) $this->address;
        if ($this->city) $address .= ", {$this->city}";
        if ($this->country) $address .= ", {$this->country}";
        return trim($address);
    }

    public function getLanguagesListAttribute(): string
    {
        if (is_array($this->languages)) {
            return implode(', ', $this->languages);
        }
        return $this->languages ?? '';
    }

    public function getServiceAreasListAttribute(): string
    {
        if (is_array($this->service_areas)) {
            return implode(', ', $this->service_areas);
        }
        return $this->service_areas ?? '';
    }

    public function getLicenseStatusAttribute(): string
    {
        if (!$this->license_expiry) {
            return 'No License';
        }
        
        if ($this->license_expiry->isPast()) {
            return 'Expired';
        }
        
        if ($this->license_expiry->diffInDays(now()) <= 30) {
            return 'Expiring Soon';
        }
        
        return 'Valid';
    }

    public function getLicenseStatusBadgeAttribute(): string
    {
        $status = $this->license_status;
        
        $badges = [
            'Valid' => 'bg-success',
            'Expiring Soon' => 'bg-warning',
            'Expired' => 'bg-danger',
            'No License' => 'bg-secondary'
        ];

        return $badges[$status] ?? 'bg-secondary';
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByLanguage($query, $language)
    {
        return $query->whereJsonContains('languages', $language);
    }

    public function scopeByServiceArea($query, $area)
    {
        return $query->whereJsonContains('service_areas', $area);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('employment_status', $status);
    }

    // Relationships (these will be implemented when related models exist)
    public function tours()
    {
        return $this->hasMany(Tour::class);
    }
}
