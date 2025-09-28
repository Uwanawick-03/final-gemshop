<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerReturn extends Model
{
    protected $fillable = [
        'customer_id',
        'return_number',
        'return_date',
        'currency_id',
        'total_amount',
        'status',
        'reason',
        'notes',
        'created_by',
        'updated_by',
        'exchange_rate'
    ];

    protected $casts = [
        'return_date' => 'date',
        'total_amount' => 'decimal:2',
        'exchange_rate' => 'decimal:4'
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class, 'transaction_id')
                    ->where('transaction_type', 'App\Models\CustomerReturn');
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
        return "Return {$this->return_number} - {$this->customer?->full_name}";
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            'processed' => 'info',
            'refunded' => 'primary'
        ];

        return $badges[$this->status] ?? 'secondary';
    }

    // Currency conversion methods
    public function convertAmount($amount, $fromCurrencyCode, $toCurrencyCode)
    {
        if ($fromCurrencyCode === $toCurrencyCode) {
            return $amount;
        }

        $fromCurrency = Currency::where('code', $fromCurrencyCode)->first();
        $toCurrency = Currency::where('code', $toCurrencyCode)->first();

        if (!$fromCurrency || !$toCurrency) {
            return $amount;
        }

        // Convert to LKR first, then to target currency
        $lkrAmount = $amount / $fromCurrency->exchange_rate;
        return round($lkrAmount * $toCurrency->exchange_rate, 2);
    }

    public function getConvertedTotalAmount($targetCurrencyCode = null)
    {
        if (!$targetCurrencyCode) {
            $targetCurrencyCode = $this->currency->code;
        }

        return $this->convertAmount($this->total_amount, $this->currency->code, $targetCurrencyCode);
    }
}