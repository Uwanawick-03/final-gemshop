<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Craftsman extends Model
{
    protected $fillable = [
        'craftsman_code',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'city',
        'country',
        'gender',
        'national_id',
        'date_of_birth',
        'joined_date',
        'primary_skill',
        'skills',
        'hourly_rate',
        'commission_rate',
        'employment_status',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'joined_date' => 'date',
        'hourly_rate' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'is_active' => 'boolean',
        'skills' => 'array',
    ];

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getDisplayNameAttribute(): string
    {
        return "{$this->full_name} ({$this->craftsman_code})";
    }

    public function getFormattedHourlyRateAttribute(): string
    {
        return $this->hourly_rate ? '$' . number_format($this->hourly_rate, 2) . '/hr' : 'Not set';
    }

    public function getFormattedCommissionRateAttribute(): string
    {
        return $this->commission_rate !== null ? number_format($this->commission_rate, 2) . '%' : 'Not set';
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

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeBySkill($query, $skill)
    {
        return $query->where('primary_skill', $skill)
            ->orWhereJsonContains('skills', $skill);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('employment_status', $status);
    }

    // Performance tracking methods
    public function getTotalJobsCompletedAttribute()
    {
        try {
            return $this->jobIssues()->where('status', 'completed')->count();
        } catch (\Exception $e) {
            return 0; // Return 0 if table/columns don't exist yet
        }
    }

    public function getTotalJobsInProgressAttribute()
    {
        try {
            return $this->jobIssues()->where('status', 'in_progress')->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function getTotalJobsPendingAttribute()
    {
        try {
            return $this->jobIssues()->where('status', 'pending')->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function getTotalCommissionsEarnedAttribute()
    {
        try {
            return $this->alterationCommissions()->sum('commission_amount') ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function getAverageJobCompletionTimeAttribute()
    {
        try {
            $completedJobs = $this->jobIssues()->where('status', 'completed')->get();
            if ($completedJobs->isEmpty()) return 0;
            
            $totalDays = $completedJobs->sum(function($job) {
                if ($job->started_at && $job->completed_at) {
                    return $job->started_at->diffInDays($job->completed_at);
                }
                return 0;
            });
            
            return $totalDays > 0 ? round($totalDays / $completedJobs->count(), 1) : 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function getPerformanceRatingAttribute()
    {
        $completedJobs = $this->total_jobs_completed;
        $avgCompletionTime = $this->average_job_completion_time;
        
        // Rating based on completion rate and speed
        if ($completedJobs >= 50 && $avgCompletionTime <= 3) {
            return ['rating' => 'Master Craftsman', 'color' => 'success', 'icon' => 'crown'];
        } elseif ($completedJobs >= 30 && $avgCompletionTime <= 5) {
            return ['rating' => 'Expert', 'color' => 'primary', 'icon' => 'star'];
        } elseif ($completedJobs >= 15 && $avgCompletionTime <= 7) {
            return ['rating' => 'Skilled', 'color' => 'info', 'icon' => 'thumbs-up'];
        } elseif ($completedJobs >= 5) {
            return ['rating' => 'Developing', 'color' => 'warning', 'icon' => 'clock'];
        }
        return ['rating' => 'Beginner', 'color' => 'secondary', 'icon' => 'user'];
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

    public function getMonthlyCommissions($month = null, $year = null)
    {
        try {
            $month = $month ?? now()->month;
            $year = $year ?? now()->year;

            return $this->alterationCommissions()
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->sum('commission_amount') ?? 0;
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

    public function getSkillsListAttribute(): string
    {
        if (is_array($this->skills)) {
            return implode(', ', $this->skills);
        }
        return $this->skills ?? '';
    }

    // Relationships (these will be implemented when related models exist)
    public function jobIssues()
    {
        return $this->hasMany(JobIssue::class);
    }

    public function alterationCommissions()
    {
        return $this->hasMany(AlterationCommission::class);
    }

    public function craftsmanReturns()
    {
        return $this->hasMany(CraftsmanReturn::class);
    }
}
