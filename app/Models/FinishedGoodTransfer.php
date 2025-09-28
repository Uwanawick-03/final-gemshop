<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinishedGoodTransfer extends Model
{
    protected $fillable = [
        'item_id',
        'craftsman_id',
        'from_workshop',
        'to_location',
        'quantity',
        'transfer_date',
        'reference_number',
        'quality_check_passed',
        'quality_check_by',
        'status', // 'pending', 'quality_check', 'completed', 'rejected'
        'transferred_by',
        'received_by',
        'notes'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'transfer_date' => 'date',
        'quality_check_passed' => 'boolean'
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

    public function qualityCheckBy()
    {
        return $this->belongsTo(User::class, 'quality_check_by');
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
        return "Finished Goods Transfer #{$this->reference_number} - {$this->item->name}";
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'warning',
            'quality_check' => 'info',
            'completed' => 'success',
            'rejected' => 'danger'
        ];

        return $badges[$this->status] ?? 'secondary';
    }

    public function getTransferDescriptionAttribute()
    {
        return "{$this->quantity} {$this->item->name} from {$this->from_workshop} to {$this->to_location}";
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

    public function scopeQualityCheckPassed($query)
    {
        return $query->where('quality_check_passed', true);
    }
}
