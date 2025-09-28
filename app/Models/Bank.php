<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $fillable = [
        'bank_code', 'name', 'branch', 'swift_code', 'account_number', 'account_name', 'currency',
        'phone', 'email', 'address', 'city', 'country', 'is_active', 'notes'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getDisplayNameAttribute(): string
    {
        $parts = [$this->name];
        if ($this->branch) $parts[] = $this->branch;
        return implode(' - ', $parts);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCurrency($query, $currency)
    {
        return $query->where('currency', $currency);
    }
}
