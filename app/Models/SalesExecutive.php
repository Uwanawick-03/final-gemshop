<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesExecutive extends Model
{
    protected $fillable = [
        'executive_code',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'city',
        'country',
        'date_of_birth',
        'gender',
        'national_id',
        'hire_date',
        'salary',
        'department',
        'position',
        'employment_status',
        'notes',
        'is_active'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'hire_date' => 'date',
        'salary' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function salesOrders()
    {
        return $this->hasMany(SalesOrder::class, 'sales_assistant_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'sales_assistant_id');
    }

    // Accessors
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getDisplayNameAttribute(): string
    {
        return "{$this->full_name} ({$this->executive_code})";
    }

    public function getFullAddressAttribute(): string
    {
        $address = (string) $this->address;
        if ($this->city) $address .= ", {$this->city}";
        if ($this->country) $address .= ", {$this->country}";
        return trim($address);
    }

    public function getEmploymentStatusBadgeAttribute(): string
    {
        $badges = [
            'active' => 'bg-success',
            'inactive' => 'bg-secondary',
            'terminated' => 'bg-danger',
            'on_leave' => 'bg-warning'
        ];

        return $badges[$this->employment_status] ?? 'bg-secondary';
    }

    public function getFormattedSalaryAttribute(): string
    {
        return $this->salary ? '$' . number_format($this->salary, 2) : 'Not set';
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByDepartment($query, $department)
    {
        return $query->where('department', $department);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('employment_status', $status);
    }

    // Performance tracking methods
    public function getTotalSalesAttribute()
    {
        return $this->salesOrders()->sum('total_amount') ?? 0;
    }

    public function getTotalInvoicesAttribute()
    {
        return $this->invoices()->sum('total_amount') ?? 0;
    }

    public function getSalesCountAttribute()
    {
        return $this->salesOrders()->count();
    }

    public function getInvoiceCountAttribute()
    {
        return $this->invoices()->count();
    }

    public function getAverageSaleAttribute()
    {
        $count = $this->sales_count;
        return $count > 0 ? $this->total_sales / $count : 0;
    }

    public function getMonthlySales($month = null, $year = null)
    {
        $month = $month ?? now()->month;
        $year = $year ?? now()->year;

        return $this->salesOrders()
            ->whereMonth('order_date', $month)
            ->whereYear('order_date', $year)
            ->sum('total_amount') ?? 0;
    }

    public function getMonthlyInvoices($month = null, $year = null)
    {
        $month = $month ?? now()->month;
        $year = $year ?? now()->year;

        return $this->invoices()
            ->whereMonth('invoice_date', $month)
            ->whereYear('invoice_date', $year)
            ->sum('total_amount') ?? 0;
    }

    public function getPerformanceRatingAttribute()
    {
        $totalSales = $this->total_sales + $this->total_invoices; // Combined sales and invoices for rating

        if ($totalSales >= 200000) return ['rating' => 'Exceptional', 'color' => 'success', 'icon' => 'star'];
        if ($totalSales >= 150000) return ['rating' => 'Excellent', 'color' => 'primary', 'icon' => 'trophy'];
        if ($totalSales >= 100000) return ['rating' => 'Good', 'color' => 'info', 'icon' => 'thumbs-up'];
        if ($totalSales >= 50000) return ['rating' => 'Average', 'color' => 'warning', 'icon' => 'minus'];
        return ['rating' => 'Needs Improvement', 'color' => 'danger', 'icon' => 'exclamation-triangle'];
    }

    public function getYearsOfServiceAttribute()
    {
        if ($this->hire_date) {
            return $this->hire_date->diffInYears(now());
        }
        return 0;
    }

    public function getDaysInSystemAttribute()
    {
        if ($this->hire_date) {
            return $this->hire_date->diffInDays(now());
        }
        return 0;
    }
}
