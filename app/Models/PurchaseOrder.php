<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'po_number',
        'supplier_id',
        'user_id',
        'order_date',
        'expected_delivery_date',
        'status',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'currency_id',
        'exchange_rate',
        'notes',
        'terms_conditions',
    ];

    protected $casts = [
        'order_date' => 'date',
        'expected_delivery_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'exchange_rate' => 'decimal:4',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactionItems()
    {
        return $this->morphMany(TransactionItem::class, 'transaction');
    }

    public function getDisplayTotalAttribute()
    {
        return number_format($this->total_amount, 2);
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'draft' => 'bg-secondary',
            'pending' => 'bg-warning',
            'approved' => 'bg-info',
            'partially_received' => 'bg-primary',
            'completed' => 'bg-success',
            'cancelled' => 'bg-danger'
        ];
        return $badges[$this->status] ?? 'bg-secondary';
    }

    public function getStatusTextAttribute()
    {
        return ucwords(str_replace('_', ' ', $this->status));
    }

    public function getIsOverdueAttribute()
    {
        return $this->expected_delivery_date && 
               $this->expected_delivery_date->isPast() && 
               $this->status !== 'completed';
    }

    public function getIsDueSoonAttribute()
    {
        return $this->expected_delivery_date && 
               $this->expected_delivery_date->diffInDays(now()) <= 3 && 
               $this->status !== 'completed';
    }

    public function getDaysUntilDeliveryAttribute()
    {
        if (!$this->expected_delivery_date) {
            return null;
        }
        return now()->diffInDays($this->expected_delivery_date, false);
    }

    public function getItemCountAttribute()
    {
        return $this->transactionItems()->count();
    }

    public function getFormattedOrderDateAttribute()
    {
        return $this->order_date?->format('M d, Y');
    }

    public function getFormattedDeliveryDateAttribute()
    {
        return $this->expected_delivery_date?->format('M d, Y');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeOverdue($query)
    {
        return $query->where('expected_delivery_date', '<', now())
                    ->whereNotIn('status', ['completed', 'cancelled']);
    }

    public function scopeDueSoon($query)
    {
        return $query->whereBetween('expected_delivery_date', [now(), now()->addDays(3)])
                    ->whereNotIn('status', ['completed', 'cancelled']);
    }

    public function scopeBySupplier($query, $supplierId)
    {
        return $query->where('supplier_id', $supplierId);
    }

    public function scopeByDateRange($query, $from, $to)
    {
        return $query->whereBetween('order_date', [$from, $to]);
    }
}