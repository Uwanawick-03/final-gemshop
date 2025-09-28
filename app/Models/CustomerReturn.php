<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerReturn extends Model
{
    protected $fillable = [
        'customer_id',
        'return_number',
        'return_date',
        'total_amount',
        'status',
        'reason',
        'notes'
    ];

    protected $casts = [
        'return_date' => 'date',
        'total_amount' => 'decimal:2'
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function transactionItems()
    {
        return $this->morphMany(TransactionItem::class, 'transaction');
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
}