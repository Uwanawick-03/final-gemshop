<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'customer_code',
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
        'credit_limit',
        'current_balance',
        'customer_type',
        'is_active',
        'notes'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'credit_limit' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function salesOrders()
    {
        return $this->hasMany(SalesOrder::class);
    }

    public function customerReturns()
    {
        return $this->hasMany(CustomerReturn::class);
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getDisplayNameAttribute()
    {
        return "{$this->full_name} ({$this->customer_code})";
    }

    public function getFullAddressAttribute()
    {
        $address = $this->address;
        if ($this->city) $address .= ", {$this->city}";
        if ($this->country) $address .= ", {$this->country}";
        return $address;
    }
}
