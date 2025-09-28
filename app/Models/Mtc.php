<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mtc extends Model
{
    protected $fillable = [
        'mtc_number',
        'item_id',
        'customer_id',
        'sales_assistant_id',
        'issue_date',
        'expiry_date',
        'purchase_price',
        'selling_price',
        'status', // 'active', 'expired', 'used', 'cancelled'
        'notes'
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'purchase_price' => 'decimal:2',
        'selling_price' => 'decimal:2'
    ];

    // Relationships
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function salesAssistant()
    {
        return $this->belongsTo(SalesAssistant::class);
    }

    // Accessors
    public function getDisplayNameAttribute()
    {
        return "MTC #{$this->mtc_number} - {$this->item->name}";
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'active' => 'success',
            'expired' => 'warning',
            'used' => 'info',
            'cancelled' => 'danger'
        ];

        return $badges[$this->status] ?? 'secondary';
    }

    public function getDaysUntilExpiryAttribute()
    {
        if ($this->expiry_date) {
            $days = now()->diffInDays($this->expiry_date, false);
            return $days;
        }
        return null;
    }

    public function getIsExpiredAttribute()
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    public function getIsExpiringSoonAttribute()
    {
        return $this->expiry_date && $this->expiry_date->isFuture() && $this->expiry_date->diffInDays(now()) <= 30;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'expired');
    }

    public function scopeExpiringSoon($query)
    {
        return $query->where('expiry_date', '<=', now()->addDays(30))
                    ->where('expiry_date', '>', now())
                    ->where('status', 'active');
    }
}
