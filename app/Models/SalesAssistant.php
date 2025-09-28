<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesAssistant extends Model
{
    protected $fillable = [
        'assistant_code',
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
        return $this->hasMany(SalesOrder::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getNameAttribute()
    {
        return $this->full_name;
    }

    public function getDisplayNameAttribute()
    {
        return "{$this->full_name} ({$this->assistant_code})";
    }

    public function getFullAddressAttribute()
    {
        $address = $this->address;
        if ($this->city) $address .= ", {$this->city}";
        if ($this->country) $address .= ", {$this->country}";
        return $address;
    }

    public function getEmploymentStatusBadgeAttribute()
    {
        $badges = [
            'active' => 'bg-success',
            'inactive' => 'bg-secondary',
            'terminated' => 'bg-danger',
            'on_leave' => 'bg-warning'
        ];

        return $badges[$this->employment_status] ?? 'bg-secondary';
    }

    public function getFormattedSalaryAttribute()
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
        $totalSales = $this->total_sales;
        
        if ($totalSales >= 100000) return ['rating' => 'Excellent', 'color' => 'success', 'icon' => 'star'];
        if ($totalSales >= 50000) return ['rating' => 'Good', 'color' => 'info', 'icon' => 'thumbs-up'];
        if ($totalSales >= 25000) return ['rating' => 'Average', 'color' => 'warning', 'icon' => 'minus'];
        return ['rating' => 'Needs Improvement', 'color' => 'danger', 'icon' => 'exclamation-triangle'];
    }
}
