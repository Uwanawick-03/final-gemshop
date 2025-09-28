<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'item_code',
        'name',
        'description',
        'category',
        'subcategory',
        'material',
        'gemstone',
        'weight',
        'size',
        'purity',
        'cost_price',
        'selling_price',
        'wholesale_price',
        'current_stock',
        'minimum_stock',
        'maximum_stock',
        'unit',
        'barcode',
        'image',
        'is_active',
        'is_taxable',
        'tax_rate',
        'notes'
    ];

    protected $casts = [
        'weight' => 'decimal:3',
        'purity' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'wholesale_price' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'is_active' => 'boolean',
        'is_taxable' => 'boolean'
    ];

    // Relationships
    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function stockAdjustments()
    {
        return $this->hasMany(StockAdjustment::class);
    }

    // Accessors
    public function getDisplayNameAttribute()
    {
        return "{$this->name} ({$this->item_code})";
    }

    public function getStockStatusAttribute()
    {
        if ($this->current_stock <= 0) {
            return 'out_of_stock';
        } elseif ($this->current_stock <= $this->minimum_stock) {
            return 'low_stock';
        } else {
            return 'in_stock';
        }
    }

    public function getStockStatusColorAttribute()
    {
        return match($this->stock_status) {
            'out_of_stock' => 'danger',
            'low_stock' => 'warning',
            'in_stock' => 'success',
            default => 'secondary'
        };
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeLowStock($query)
    {
        return $query->whereRaw('current_stock <= minimum_stock');
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('current_stock', '<=', 0);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByMaterial($query, $material)
    {
        return $query->where('material', $material);
    }

    // Image helper methods
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return 'data:image/svg+xml;base64,' . base64_encode('<svg width="200" height="200" xmlns="http://www.w3.org/2000/svg"><rect width="200" height="200" fill="#f8f9fa" stroke="#dee2e6" stroke-width="2"/><text x="100" y="100" text-anchor="middle" dominant-baseline="middle" font-family="Arial, sans-serif" font-size="14" fill="#6c757d">No Image</text><text x="100" y="120" text-anchor="middle" dominant-baseline="middle" font-family="Arial, sans-serif" font-size="12" fill="#adb5bd">Available</text></svg>');
    }

    public function hasImage()
    {
        return !empty($this->image);
    }

    // Performance and Analytics Methods
    public function getTotalSalesAttribute()
    {
        return $this->transactionItems()
            ->whereHas('transaction', function($query) {
                $query->whereIn('transaction_type', ['App\Models\Invoice', 'App\Models\SalesOrder']);
            })
            ->sum('total_price') ?? 0;
    }

    public function getSalesCountAttribute()
    {
        return $this->transactionItems()
            ->whereHas('transaction', function($query) {
                $query->whereIn('transaction_type', ['App\Models\Invoice', 'App\Models\SalesOrder']);
            })
            ->count();
    }

    public function getTotalQuantitySoldAttribute()
    {
        return $this->transactionItems()
            ->whereHas('transaction', function($query) {
                $query->whereIn('transaction_type', ['App\Models\Invoice', 'App\Models\SalesOrder']);
            })
            ->sum('quantity') ?? 0;
    }

    public function getAverageSalePriceAttribute()
    {
        $count = $this->sales_count;
        return $count > 0 ? $this->total_sales / $this->total_quantity_sold : 0;
    }

    public function getProfitMarginAttribute()
    {
        if ($this->selling_price > 0 && $this->cost_price > 0) {
            return (($this->selling_price - $this->cost_price) / $this->selling_price) * 100;
        }
        return 0;
    }

    public function getStockValueAttribute()
    {
        return $this->current_stock * $this->cost_price;
    }

    public function getPerformanceRatingAttribute()
    {
        $totalSales = $this->total_sales;
        
        if ($totalSales >= 100000) return ['rating' => 'Excellent', 'color' => 'success', 'icon' => 'star'];
        if ($totalSales >= 50000) return ['rating' => 'Good', 'color' => 'info', 'icon' => 'thumbs-up'];
        if ($totalSales >= 25000) return ['rating' => 'Average', 'color' => 'warning', 'icon' => 'minus'];
        if ($totalSales >= 10000) return ['rating' => 'Below Average', 'color' => 'secondary', 'icon' => 'arrow-down'];
        return ['rating' => 'Poor', 'color' => 'danger', 'icon' => 'exclamation-triangle'];
    }

    public function getMonthlySales($month = null, $year = null)
    {
        $month = $month ?? now()->month;
        $year = $year ?? now()->year;
        
        return $this->transactionItems()
            ->whereHas('transaction', function($query) use ($month, $year) {
                $query->whereIn('transaction_type', ['App\Models\Invoice', 'App\Models\SalesOrder'])
                      ->whereMonth('created_at', $month)
                      ->whereYear('created_at', $year);
            })
            ->sum('total_price') ?? 0;
    }

    public function getStockTurnoverRateAttribute()
    {
        if ($this->current_stock > 0 && $this->total_quantity_sold > 0) {
            return ($this->total_quantity_sold / $this->current_stock) * 100;
        }
        return 0;
    }
}
