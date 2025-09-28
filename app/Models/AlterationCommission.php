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
        'status', // 'pending', 'in_progress', 'completed', 'cancelled'
        'start_date',
        'completion_date',
        'notes'
    ];

    protected $casts = [
        'commission_date' => 'date',
        'start_date' => 'date',
        'completion_date' => 'date',
        'commission_amount' => 'decimal:2'
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

    // Accessors
    public function getDisplayNameAttribute()
    {
        return "Commission #{$this->commission_number} - {$this->customer?->full_name}";
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'warning',
            'in_progress' => 'info',
            'completed' => 'success',
            'cancelled' => 'danger'
        ];

        return $badges[$this->status] ?? 'secondary';
    }

    public function getDurationAttribute()
    {
        if ($this->start_date && $this->completion_date) {
            return $this->start_date->diffInDays($this->completion_date);
        }
        return null;
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

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }
}
