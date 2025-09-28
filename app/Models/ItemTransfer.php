<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemTransfer extends Model
{
    protected $fillable = [
        'item_id',
        'from_location',
        'to_location',
        'quantity',
        'transfer_date',
        'reference_number',
        'reason',
        'status', // 'pending', 'in_transit', 'completed', 'cancelled'
        'transferred_by',
        'received_by',
        'notes'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'transfer_date' => 'date'
    ];

    // Relationships
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function transferredBy()
    {
        return $this->belongsTo(User::class, 'transferred_by');
    }

    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    // Accessors
    public function getDisplayNameAttribute()
    {
        return "Transfer #{$this->reference_number} - {$this->item->name}";
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'warning',
            'in_transit' => 'info',
            'completed' => 'success',
            'cancelled' => 'danger'
        ];

        return $badges[$this->status] ?? 'secondary';
    }

    public function getTransferDescriptionAttribute()
    {
        return "{$this->quantity} {$this->item->name} from {$this->from_location} to {$this->to_location}";
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
}
