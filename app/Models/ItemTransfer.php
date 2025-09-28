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
        'received_at',
        'notes',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'quantity' => 'decimal:3',
        'transfer_date' => 'date',
        'received_at' => 'datetime'
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

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Accessors
    public function getDisplayNameAttribute()
    {
        return "Transfer #{$this->reference_number} - {$this->item->name}";
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'warning',
            'in_transit' => 'info',
            'completed' => 'success',
            'cancelled' => 'danger',
            default => 'secondary'
        };
    }

    public function getStatusLabelAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->status));
    }

    public function getReasonLabelAttribute()
    {
        return match($this->reason) {
            'restock' => 'Restock',
            'sale_transfer' => 'Sale Transfer',
            'repair' => 'Repair',
            'display' => 'Display',
            'storage' => 'Storage',
            'damage' => 'Damage',
            'other' => 'Other',
            default => ucfirst($this->reason ?? 'Not specified')
        };
    }

    public function getReasonColorAttribute()
    {
        return match($this->reason) {
            'restock' => 'success',
            'sale_transfer' => 'info',
            'repair' => 'warning',
            'display' => 'primary',
            'storage' => 'secondary',
            'damage' => 'danger',
            'other' => 'dark',
            default => 'secondary'
        };
    }

    public function getTransferDescriptionAttribute()
    {
        return "{$this->quantity} {$this->item->name} from {$this->from_location} to {$this->to_location}";
    }

    public function getIsOverdueAttribute()
    {
        return $this->transfer_date && $this->transfer_date < now() && $this->status === 'pending';
    }

    public function getDaysOverdueAttribute()
    {
        if (!$this->is_overdue) {
            return 0;
        }
        
        return now()->diffInDays($this->transfer_date);
    }

    public function getIsInTransitAttribute()
    {
        return $this->status === 'in_transit';
    }

    public function getIsCompletedAttribute()
    {
        return $this->status === 'completed';
    }

    public function getIsPendingAttribute()
    {
        return $this->status === 'pending';
    }

    public function getIsCancelledAttribute()
    {
        return $this->status === 'cancelled';
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInTransit($query)
    {
        return $query->where('status', 'in_transit');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeByItem($query, $itemId)
    {
        return $query->where('item_id', $itemId);
    }

    public function scopeByFromLocation($query, $location)
    {
        return $query->where('from_location', $location);
    }

    public function scopeByToLocation($query, $location)
    {
        return $query->where('to_location', $location);
    }

    public function scopeByReason($query, $reason)
    {
        return $query->where('reason', $reason);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('transfer_date', [$startDate, $endDate]);
    }

    public function scopeOverdue($query)
    {
        return $query->where('transfer_date', '<', now())
                    ->where('status', 'pending');
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByTransferredBy($query, $userId)
    {
        return $query->where('transferred_by', $userId);
    }

    public function scopeByReceivedBy($query, $userId)
    {
        return $query->where('received_by', $userId);
    }
}