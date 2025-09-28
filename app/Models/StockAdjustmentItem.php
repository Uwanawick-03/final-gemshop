<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockAdjustmentItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_adjustment_id',
        'item_id',
        'item_code',
        'item_name',
        'current_quantity',
        'adjusted_quantity',
        'difference_quantity',
        'unit_cost',
        'reason',
        'notes'
    ];

    protected $casts = [
        'current_quantity' => 'decimal:2',
        'adjusted_quantity' => 'decimal:2',
        'difference_quantity' => 'decimal:2',
        'unit_cost' => 'decimal:2'
    ];

    // Relationships
    public function stockAdjustment()
    {
        return $this->belongsTo(StockAdjustment::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // Accessors
    public function getTotalValueAttribute()
    {
        return $this->adjusted_quantity * $this->unit_cost;
    }

    public function getDifferenceValueAttribute()
    {
        return $this->difference_quantity * $this->unit_cost;
    }

    public function getAdjustmentTypeAttribute()
    {
        if ($this->difference_quantity > 0) {
            return 'increase';
        } elseif ($this->difference_quantity < 0) {
            return 'decrease';
        }
        return 'no_change';
    }

    // Scopes
    public function scopeByItem($query, $itemId)
    {
        return $query->where('item_id', $itemId);
    }

    public function scopeIncreases($query)
    {
        return $query->where('difference_quantity', '>', 0);
    }

    public function scopeDecreases($query)
    {
        return $query->where('difference_quantity', '<', 0);
    }
}
