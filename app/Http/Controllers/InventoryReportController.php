<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\StockAdjustment;
use App\Models\ItemTransfer;
use App\Models\Grn;
use App\Models\Invoice;
use App\Models\TransactionItem;
use App\Models\Supplier;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class InventoryReportController extends Controller
{
    /**
     * Display the main inventory report dashboard
     */
    public function index()
    {
        // Get summary statistics
        $summary = $this->getInventorySummary();
        
        // Get inventory alerts
        $inventoryAlerts = $this->getInventoryAlerts();
        
        // Get recent stock movements
        $recentMovements = $this->getRecentStockMovements();
        
        // Get inventory by category
        $inventoryByCategory = $this->getInventoryByCategory();
        
        // Get inventory by material
        $inventoryByMaterial = $this->getInventoryByMaterial();
        
        // Get stock adjustments summary
        $stockAdjustmentsSummary = $this->getStockAdjustmentsSummary();
        
        // Get item transfers summary
        $itemTransfersSummary = $this->getItemTransfersSummary();
        
        // Get inventory valuation
        $inventoryValuation = $this->getInventoryValuation();

        return view('reports.inventory.index', compact(
            'summary',
            'inventoryAlerts',
            'recentMovements',
            'inventoryByCategory',
            'inventoryByMaterial',
            'stockAdjustmentsSummary',
            'itemTransfersSummary',
            'inventoryValuation'
        ));
    }

    /**
     * Detailed inventory report with filters
     */
    public function detailed(Request $request)
    {
        $query = Item::query();

        // Apply filters
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('material')) {
            $query->where('material', $request->material);
        }

        if ($request->filled('stock_status')) {
            switch ($request->stock_status) {
                case 'low_stock':
                    $query->whereRaw('current_stock <= minimum_stock');
                    break;
                case 'out_of_stock':
                    $query->where('current_stock', '<=', 0);
                    break;
                case 'in_stock':
                    $query->whereRaw('current_stock > minimum_stock');
                    break;
                case 'overstock':
                    $query->whereRaw('current_stock > maximum_stock');
                    break;
            }
        }

        if ($request->filled('supplier_id')) {
            $query->whereHas('suppliers', function($q) use ($request) {
                $q->where('suppliers.id', $request->supplier_id);
            });
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('item_code', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $items = $query->orderBy('name')->paginate(50);

        // Get filter options
        $categories = Item::distinct()->pluck('category')->filter()->sort()->values();
        $materials = Item::distinct()->pluck('material')->filter()->sort()->values();
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();

        return view('reports.inventory.detailed', compact(
            'items',
            'categories',
            'materials',
            'suppliers'
        ));
    }

    /**
     * Stock movements report
     */
    public function movements(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        $itemId = $request->get('item_id');
        $movementType = $request->get('movement_type');

        $movements = $this->getStockMovements($startDate, $endDate, $itemId, $movementType);

        $items = Item::where('is_active', true)->orderBy('name')->get();

        return view('reports.inventory.movements', compact(
            'movements',
            'items',
            'startDate',
            'endDate',
            'itemId',
            'movementType'
        ));
    }

    /**
     * Inventory valuation report
     */
    public function valuation(Request $request)
    {
        $valuationMethod = $request->get('method', 'cost'); // cost, selling, wholesale
        
        $items = Item::where('is_active', true)
            ->get()
            ->map(function($item) use ($valuationMethod) {
                $item->valuation_price = $this->getValuationPrice($item, $valuationMethod);
                $item->total_value = $item->current_stock * $item->valuation_price;
                return $item;
            })
            ->sortByDesc('total_value');

        $totalValue = $items->sum('total_value');
        $totalItems = $items->count();
        $totalStock = $items->sum('current_stock');

        return view('reports.inventory.valuation', compact(
            'items',
            'totalValue',
            'totalItems',
            'totalStock',
            'valuationMethod'
        ));
    }

    /**
     * Stock adjustments report
     */
    public function adjustments(Request $request)
    {
        $query = StockAdjustment::query();

        // Check if columns exist before filtering
        $hasStatusColumn = Schema::hasColumn('stock_adjustments', 'status');
        $hasTypeColumn = Schema::hasColumn('stock_adjustments', 'type');
        $hasAdjustmentDateColumn = Schema::hasColumn('stock_adjustments', 'adjustment_date');

        if ($request->filled('status') && $hasStatusColumn) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type') && $hasTypeColumn) {
            $query->where('type', $request->type);
        }

        if ($request->filled('start_date') && $hasAdjustmentDateColumn) {
            $query->whereDate('adjustment_date', '>=', $request->start_date);
        } elseif ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date') && $hasAdjustmentDateColumn) {
            $query->whereDate('adjustment_date', '<=', $request->end_date);
        } elseif ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $adjustments = $query->orderBy($hasAdjustmentDateColumn ? 'adjustment_date' : 'created_at', 'desc')->paginate(50);

        return view('reports.inventory.adjustments', compact('adjustments'));
    }

    /**
     * Item transfers report
     */
    public function transfers(Request $request)
    {
        $query = ItemTransfer::with(['item', 'transferredBy', 'receivedBy']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('reason')) {
            $query->where('reason', $request->reason);
        }

        if ($request->filled('item_id')) {
            $query->where('item_id', $request->item_id);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('transfer_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('transfer_date', '<=', $request->end_date);
        }

        $transfers = $query->orderBy('transfer_date', 'desc')->paginate(50);

        $items = Item::where('is_active', true)->orderBy('name')->get();

        return view('reports.inventory.transfers', compact(
            'transfers',
            'items'
        ));
    }

    /**
     * Export inventory report to PDF
     */
    public function exportPdf(Request $request)
    {
        $reportType = $request->get('type', 'summary');
        
        switch ($reportType) {
            case 'detailed':
                $items = Item::orderBy('name')->get();
                $pdf = \PDF::loadView('reports.inventory.pdf.detailed', compact('items'));
                break;
            case 'valuation':
                $items = Item::where('is_active', true)->get();
                $pdf = \PDF::loadView('reports.inventory.pdf.valuation', compact('items'));
                break;
            case 'movements':
                $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
                $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
                $movements = $this->getStockMovements($startDate, $endDate);
                $pdf = \PDF::loadView('reports.inventory.pdf.movements', compact('movements', 'startDate', 'endDate'));
                break;
            case 'adjustments':
                $adjustments = StockAdjustment::with(['adjustmentItems.item'])->get();
                $pdf = \PDF::loadView('reports.inventory.pdf.adjustments', compact('adjustments'));
                break;
            case 'transfers':
                $transfers = ItemTransfer::with(['item'])->get();
                $pdf = \PDF::loadView('reports.inventory.pdf.transfers', compact('transfers'));
                break;
            default:
                $summary = $this->getInventorySummary();
                $inventoryAlerts = $this->getInventoryAlerts();
                $pdf = \PDF::loadView('reports.inventory.pdf.summary', compact('summary', 'inventoryAlerts'));
        }

        return $pdf->download("inventory-report-{$reportType}-" . now()->format('Y-m-d') . ".pdf");
    }

    /**
     * Export inventory report to Excel
     */
    public function exportExcel(Request $request)
    {
        $reportType = $request->get('type', 'summary');
        
        // This would typically use Laravel Excel package
        // For now, we'll return a CSV
        return $this->exportCsv($request);
    }

    /**
     * Export inventory report to CSV
     */
    public function exportCsv(Request $request)
    {
        $reportType = $request->get('type', 'summary');
        
        $filename = "inventory-report-{$reportType}-" . now()->format('Y-m-d') . ".csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($reportType) {
            $handle = fopen('php://output', 'w');
            
            switch ($reportType) {
                case 'detailed':
                    fputcsv($handle, ['Item Code', 'Name', 'Category', 'Material', 'Current Stock', 'Min Stock', 'Max Stock', 'Cost Price', 'Selling Price', 'Status']);
                    Item::chunk(100, function($items) use ($handle) {
                        foreach ($items as $item) {
                            fputcsv($handle, [
                                $item->item_code,
                                $item->name,
                                $item->category,
                                $item->material,
                                $item->current_stock,
                                $item->minimum_stock,
                                $item->maximum_stock,
                                $item->cost_price,
                                $item->selling_price,
                                $item->stock_status
                            ]);
                        }
                    });
                    break;
                default:
                    fputcsv($handle, ['Item Code', 'Name', 'Current Stock', 'Min Stock', 'Status', 'Value']);
                    Item::chunk(100, function($items) use ($handle) {
                        foreach ($items as $item) {
                            fputcsv($handle, [
                                $item->item_code,
                                $item->name,
                                $item->current_stock,
                                $item->minimum_stock,
                                $item->stock_status,
                                $item->current_stock * $item->cost_price
                            ]);
                        }
                    });
            }
            
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get inventory summary statistics
     */
    private function getInventorySummary()
    {
        $totalItems = Item::count();
        $activeItems = Item::where('is_active', true)->count();
        $totalStockValue = Item::sum(DB::raw('current_stock * cost_price'));
        $lowStockItems = Item::whereRaw('current_stock <= minimum_stock')->count();
        $outOfStockItems = Item::where('current_stock', '<=', 0)->count();
        $overstockItems = Item::whereRaw('current_stock > maximum_stock')->count();
        $totalQuantity = Item::sum('current_stock');
        $categoriesCount = Item::distinct('category')->count();
        $materialsCount = Item::distinct('material')->count();

        return [
            'total_items' => $totalItems,
            'active_items' => $activeItems,
            'total_stock_value' => $totalStockValue,
            'low_stock_items' => $lowStockItems,
            'out_of_stock_items' => $outOfStockItems,
            'overstock_items' => $overstockItems,
            'total_quantity' => $totalQuantity,
            'categories_count' => $categoriesCount,
            'materials_count' => $materialsCount,
        ];
    }

    /**
     * Get inventory alerts
     */
    private function getInventoryAlerts()
    {
        return [
            'low_stock' => Item::whereRaw('current_stock <= minimum_stock')
                ->where('current_stock', '>', 0)
                ->orderBy('current_stock', 'asc')
                ->limit(10)
                ->get(),
            'out_of_stock' => Item::where('current_stock', '<=', 0)
                ->orderBy('name')
                ->limit(10)
                ->get(),
            'overstock' => Item::whereRaw('current_stock > maximum_stock')
                ->orderBy('current_stock', 'desc')
                ->limit(10)
                ->get()
        ];
    }

    /**
     * Get recent stock movements
     */
    private function getRecentStockMovements()
    {
        // This would typically query a stock_movements table
        // For now, we'll simulate with recent transactions
        return collect([
            (object)[
                'item' => 'Gold Ring',
                'type' => 'Purchase',
                'quantity' => 10,
                'date' => now()->subHours(2),
                'reference' => 'GRN-001'
            ],
            (object)[
                'item' => 'Silver Chain',
                'type' => 'Sale',
                'quantity' => -2,
                'date' => now()->subHours(4),
                'reference' => 'INV-001'
            ],
            (object)[
                'item' => 'Diamond Earrings',
                'type' => 'Adjustment',
                'quantity' => 1,
                'date' => now()->subHours(6),
                'reference' => 'ADJ-001'
            ]
        ]);
    }

    /**
     * Get inventory by category
     */
    private function getInventoryByCategory()
    {
        return Item::select('category', DB::raw('SUM(current_stock) as total_stock'), DB::raw('COUNT(*) as item_count'))
            ->where('is_active', true)
            ->groupBy('category')
            ->orderBy('total_stock', 'desc')
            ->get();
    }

    /**
     * Get inventory by material
     */
    private function getInventoryByMaterial()
    {
        return Item::select('material', DB::raw('SUM(current_stock) as total_stock'), DB::raw('COUNT(*) as item_count'))
            ->where('is_active', true)
            ->groupBy('material')
            ->orderBy('total_stock', 'desc')
            ->get();
    }

    /**
     * Get stock adjustments summary
     */
    private function getStockAdjustmentsSummary()
    {
        // Check if status column exists
        $hasStatusColumn = Schema::hasColumn('stock_adjustments', 'status');
        
        $summary = [
            'total_adjustments' => StockAdjustment::count(),
            'recent_adjustments' => StockAdjustment::with(['createdBy'])->latest()->limit(5)->get()
        ];

        if ($hasStatusColumn) {
            $summary['pending_adjustments'] = StockAdjustment::where('status', 'pending')->count();
            $summary['approved_adjustments'] = StockAdjustment::where('status', 'approved')->count();
        } else {
            $summary['pending_adjustments'] = 0;
            $summary['approved_adjustments'] = 0;
        }

        return $summary;
    }

    /**
     * Get item transfers summary
     */
    private function getItemTransfersSummary()
    {
        return [
            'total_transfers' => ItemTransfer::count(),
            'pending_transfers' => ItemTransfer::where('status', 'pending')->count(),
            'completed_transfers' => ItemTransfer::where('status', 'completed')->count(),
            'recent_transfers' => ItemTransfer::with(['item'])
                ->whereNotNull('item_id')
                ->whereHas('item')
                ->latest()
                ->limit(5)
                ->get()
        ];
    }

    /**
     * Get inventory valuation
     */
    private function getInventoryValuation()
    {
        $items = Item::where('is_active', true)->get();
        
        return [
            'cost_value' => $items->sum(function($item) {
                return $item->current_stock * $item->cost_price;
            }),
            'selling_value' => $items->sum(function($item) {
                return $item->current_stock * $item->selling_price;
            }),
            'wholesale_value' => $items->sum(function($item) {
                return $item->current_stock * $item->wholesale_price;
            })
        ];
    }

    /**
     * Get stock movements for a specific period
     */
    private function getStockMovements($startDate, $endDate, $itemId = null, $movementType = null)
    {
        // This would typically query a stock_movements table
        // For now, we'll return empty collection
        return collect();
    }

    /**
     * Get valuation price based on method
     */
    private function getValuationPrice($item, $method)
    {
        switch ($method) {
            case 'selling':
                return $item->selling_price;
            case 'wholesale':
                return $item->wholesale_price;
            default:
                return $item->cost_price;
        }
    }
}
