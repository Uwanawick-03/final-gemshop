<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\CurrencyService;

class SupplierReturn extends Model
{
    protected $fillable = [
        'supplier_id',
        'return_number',
        'return_date',
        'currency_id',
        'total_amount',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'exchange_rate',
        'status', // 'pending', 'approved', 'completed', 'rejected'
        'reason',
        'notes',
        'processed_by',
        'approved_by',
        'approved_at',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'return_date' => 'date',
        'total_amount' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'exchange_rate' => 'decimal:4',
        'approved_at' => 'datetime'
    ];

    // Relationships
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class, 'transaction_id')
                    ->where('transaction_type', 'App\Models\SupplierReturn');
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
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
        return "Return {$this->return_number} - {$this->supplier?->company_name}";
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'warning',
            'approved' => 'info',
            'completed' => 'success',
            'rejected' => 'danger',
            default => 'secondary'
        };
    }

    public function getStatusLabelAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->status));
    }

    public function getReasonLabelAttribute()
    {
        return match($this->reason) {
            'defective' => 'Defective Items',
            'wrong_item' => 'Wrong Item',
            'overstock' => 'Overstock',
            'damaged' => 'Damaged in Transit',
            'quality_issue' => 'Quality Issue',
            'other' => 'Other',
            default => ucfirst($this->reason ?? 'Not specified')
        };
    }

    public function getReasonColorAttribute()
    {
        return match($this->reason) {
            'defective' => 'danger',
            'wrong_item' => 'warning',
            'overstock' => 'info',
            'damaged' => 'danger',
            'quality_issue' => 'warning',
            'other' => 'secondary',
            default => 'secondary'
        };
    }

    public function getIsOverdueAttribute()
    {
        return $this->return_date && $this->return_date < now() && $this->status === 'pending';
    }

    public function getDaysOverdueAttribute()
    {
        if (!$this->is_overdue) {
            return 0;
        }
        
        return now()->diffInDays($this->return_date);
    }

    // Currency conversion methods
    public function convertAmount($amount, $fromCurrencyCode, $toCurrencyCode)
    {
        $currencyService = app(CurrencyService::class);
        return $currencyService->convertAmount($amount, $fromCurrencyCode, $toCurrencyCode);
    }

    public function getConvertedTotalAmount($toCurrencyCode = 'LKR')
    {
        if (!$this->currency) {
            return $this->total_amount;
        }
        
        return $this->convertAmount($this->total_amount, $this->currency->code, $toCurrencyCode);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeBySupplier($query, $supplierId)
    {
        return $query->where('supplier_id', $supplierId);
    }

    public function scopeByReason($query, $reason)
    {
        return $query->where('reason', $reason);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('return_date', [$startDate, $endDate]);
    }

    public function scopeOverdue($query)
    {
        return $query->where('return_date', '<', now())
                    ->where('status', 'pending');
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByCurrency($query, $currencyId)
    {
        return $query->where('currency_id', $currencyId);
    }
}