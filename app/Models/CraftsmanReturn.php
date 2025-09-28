<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CraftsmanReturn extends Model
{
    protected $fillable = [
        'craftsman_id',
        'item_id',
        'return_number',
        'return_date',
        'return_type', // 'defective', 'unused_material', 'excess', 'quality_issue'
        'quantity',
        'reason',
        'status', // 'pending', 'approved', 'completed', 'rejected'
        'processed_by',
        'approved_by',
        'notes'
    ];

    protected $casts = [
        'return_date' => 'date',
        'quantity' => 'integer'
    ];

    // Relationships
    public function craftsman()
    {
        return $this->belongsTo(Craftsman::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Accessors
    public function getDisplayNameAttribute()
    {
        return "Craftsman Return #{$this->return_number} - {$this->craftsman?->name}";
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'warning',
            'approved' => 'info',
            'completed' => 'success',
            'rejected' => 'danger'
        ];

        return $badges[$this->status] ?? 'secondary';
    }

    public function getReturnDescriptionAttribute()
    {
        return "{$this->quantity} {$this->item->name} - {$this->return_type}";
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

    public function scopeByCraftsman($query, $craftsmanId)
    {
        return $query->where('craftsman_id', $craftsmanId);
    }
}
