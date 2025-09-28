<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    protected $fillable = [
        'customer_id',
        'sales_assistant_id',
        'order_number',
        'order_date',
        'delivery_date',
        'total_amount',
        'status',
        'notes'
    ];

    protected $casts = [
        'order_date' => 'date',
        'delivery_date' => 'date',
        'total_amount' => 'decimal:2'
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

    public function transactionItems()
    {
        return $this->morphMany(TransactionItem::class, 'transaction');
    }

    // Accessors
    public function getDisplayNameAttribute()
    {
        return "Order {$this->order_number} - {$this->customer?->full_name}";
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'warning',
            'confirmed' => 'info',
            'processing' => 'primary',
            'shipped' => 'success',
            'delivered' => 'success',
            'cancelled' => 'danger'
        ];

        return $badges[$this->status] ?? 'secondary';
    }
}