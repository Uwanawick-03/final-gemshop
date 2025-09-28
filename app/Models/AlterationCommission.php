<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlterationCommission extends Model
{
    protected $fillable = [
        'customer_id',
        'sales_assistant_id',
        'craftsman_id',
        'item_id',
        'commission_number',
        'commission_date',
        'alteration_type',
        'description',
        'commission_amount',
        'currency_id',
        'exchange_rate',
        'status', // 'pending', 'in_progress', 'completed', 'cancelled'
        'start_date',
        'completion_date',
        'payment_status', // 'unpaid', 'partial', 'paid'
        'paid_amount',
        'payment_date',
        'notes',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'commission_date' => 'date',
        'start_date' => 'date',
        'completion_date' => 'date',
        'payment_date' => 'date',
        'commission_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'exchange_rate' => 'decimal:4'
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function salesAssistant()
    {
        return $this->belongsTo(SalesAssistant::class);
    }

    public function craftsman()
    {
        return $this->belongsTo(Craftsman::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Accessors
    public function getDisplayNameAttribute()
    {
        return "Commission #{$this->commission_number} - {$this->customer?->full_name}";
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'warning',
            'in_progress' => 'info',
            'completed' => 'success',
            'cancelled' => 'danger',
            default => 'secondary'
        };
    }

    public function getStatusLabelAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->status));
    }

    public function getPaymentStatusColorAttribute()
    {
        return match($this->payment_status) {
            'unpaid' => 'danger',
            'partial' => 'warning',
            'paid' => 'success',
            default => 'secondary'
        };
    }

    public function getPaymentStatusLabelAttribute()
    {
        return ucfirst($this->payment_status ?? 'Not specified');
    }

    public function getAlterationTypeLabelAttribute()
    {
        return match($this->alteration_type) {
            'resize' => 'Resize',
            'repair' => 'Repair',
            'polish' => 'Polish',
            'engrave' => 'Engrave',
            'design_change' => 'Design Change',
            'stone_setting' => 'Stone Setting',
            'cleaning' => 'Cleaning',
            'other' => 'Other',
            default => ucfirst($this->alteration_type ?? 'Not specified')
        };
    }

    public function getAlterationTypeColorAttribute()
    {
        return match($this->alteration_type) {
            'resize' => 'primary',
            'repair' => 'warning',
            'polish' => 'info',
            'engrave' => 'success',
            'design_change' => 'secondary',
            'stone_setting' => 'dark',
            'cleaning' => 'light',
            'other' => 'muted',
            default => 'secondary'
        };
    }

    public function getDurationAttribute()
    {
        if ($this->start_date && $this->completion_date) {
            return $this->start_date->diffInDays($this->completion_date);
        }
        return null;
    }

    public function getIsOverdueAttribute()
    {
        if (!$this->start_date || $this->status === 'completed' || $this->status === 'cancelled') {
            return false;
        }
        
        // Consider overdue if more than 7 days have passed since start date
        return $this->start_date->addDays(7) < now();
    }

    public function getDaysOverdueAttribute()
    {
        if (!$this->is_overdue) {
            return 0;
        }
        
        return $this->start_date->addDays(7)->diffInDays(now());
    }

    public function getIsPendingAttribute()
    {
        return $this->status === 'pending';
    }

    public function getIsInProgressAttribute()
    {
        return $this->status === 'in_progress';
    }

    public function getIsCompletedAttribute()
    {
        return $this->status === 'completed';
    }

    public function getIsCancelledAttribute()
    {
        return $this->status === 'cancelled';
    }

    public function getIsPaidAttribute()
    {
        return $this->payment_status === 'paid';
    }

    public function getIsUnpaidAttribute()
    {
        return $this->payment_status === 'unpaid';
    }

    public function getIsPartiallyPaidAttribute()
    {
        return $this->payment_status === 'partial';
    }

    public function getRemainingAmountAttribute()
    {
        return $this->commission_amount - ($this->paid_amount ?? 0);
    }

    public function getProgressPercentageAttribute()
    {
        if ($this->commission_amount <= 0) {
            return 0;
        }
        
        return min(100, round(($this->paid_amount ?? 0) / $this->commission_amount * 100, 2));
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPaymentStatus($query, $paymentStatus)
    {
        return $query->where('payment_status', $paymentStatus);
    }

    public function scopeByAlterationType($query, $type)
    {
        return $query->where('alteration_type', $type);
    }

    public function scopeByCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    public function scopeByCraftsman($query, $craftsmanId)
    {
        return $query->where('craftsman_id', $craftsmanId);
    }

    public function scopeBySalesAssistant($query, $salesAssistantId)
    {
        return $query->where('sales_assistant_id', $salesAssistantId);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('commission_date', [$startDate, $endDate]);
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'in_progress')
                    ->where('start_date', '<', now()->subDays(7));
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopeUnpaid($query)
    {
        return $query->where('payment_status', 'unpaid');
    }

    public function scopePartiallyPaid($query)
    {
        return $query->where('payment_status', 'partial');
    }
}