<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkshopAdjustment extends Model
{
    protected $fillable = [
        'item_id',
        'workshop_location',
        'adjustment_type', // 'material_used', 'scrap', 'defective', 'correction'
        'quantity',
        'adjustment_date',
        'reference_number',
        'reason',
        'craftsman_id',
        'approved_by',
        'status', // 'pending', 'approved', 'rejected'
        'notes'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'adjustment_date' => 'date'
    ];

    // Relationships
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function craftsman()
    {
        return $this->belongsTo(Craftsman::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Accessors
    public function getDisplayNameAttribute()
    {
        return "Workshop Adjustment #{$this->reference_number} - {$this->item->name}";
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger'
        ];

        return $badges[$this->status] ?? 'secondary';
    }

    public function getAdjustmentDescriptionAttribute()
    {
        return "{$this->adjustment_type} - {$this->quantity} {$this->item->name} at {$this->workshop_location}";
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

    public function scopeByWorkshop($query, $workshop)
    {
        return $query->where('workshop_location', $workshop);
    }
}
