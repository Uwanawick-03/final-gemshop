<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockAdjustment extends Model
{
    use HasFactory;

    protected $fillable = [
        'adjustment_number',
        'adjustment_date',
        'reason',
        'type', // 'increase' or 'decrease'
        'status', // 'pending', 'approved', 'rejected', 'completed'
        'total_items',
        'notes',
        'approved_by',
        'approved_at',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'adjustment_date' => 'date',
        'approved_at' => 'datetime',
        'total_items' => 'integer'
    ];

    // Relationships
    public function adjustmentItems()
    {
        return $this->hasMany(StockAdjustmentItem::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
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
    public function getStatusColorAttribute()
    {
        // Status column doesn't exist yet, return default
        return 'secondary';
    }

    public function getStatusLabelAttribute()
    {
        // Status column doesn't exist yet, return default
        return 'N/A';
    }

    public function getTypeColorAttribute()
    {
        // Type column doesn't exist yet, return default
        return 'secondary';
    }

    public function getTypeLabelAttribute()
    {
        // Type column doesn't exist yet, return default
        return 'N/A';
    }

    // Scopes (disabled since columns don't exist yet)
    public function scopePending($query)
    {
        // Status column doesn't exist yet, return all records
        return $query;
    }

    public function scopeApproved($query)
    {
        // Status column doesn't exist yet, return all records
        return $query;
    }

    public function scopeCompleted($query)
    {
        // Status column doesn't exist yet, return all records
        return $query;
    }

    public function scopeByType($query, $type)
    {
        // Type column doesn't exist yet, return all records
        return $query;
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        // adjustment_date column doesn't exist yet, use created_at instead
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    public function scopeByReason($query, $reason)
    {
        // reason column doesn't exist yet, return all records
        return $query;
    }
}