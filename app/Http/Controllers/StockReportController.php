<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\TransactionItem;
use App\Models\StockAdjustment;
use App\Models\WorkshopAdjustment;
use App\Models\CraftsmanReturn;
use App\Models\FinishedGoodTransfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StockReportController extends Controller
{
    /**
     * Display the main stocks report dashboard
     */
    public function index()
    {
        // Get summary statistics
        $summary = $this->getStockSummary();
        
        // Get low stock items
        $lowStockItems = Item::whereRaw('current_stock <= minimum_stock')
            ->orderBy('current_stock', 'asc')
            ->limit(20)
            ->get();
        
        // Get out of stock items
        $outOfStockItems = Item::where('current_stock', '<=', 0)
            ->orderBy('name')
            ->get();
        
        // Get recent stock movements
        $recentMovements = $this->getRecentStockMovements();
        
        // Get stock by category
        $stockByCategory = $this->getStockByCategory();
        
        // Get stock by material
        $stockByMaterial = $this->getStockByMaterial();
        
        // Get stock value analysis
        $stockValueAnalysis = $this->getStockValueAnalysis();

        return view('reports.stocks.index', compact(
            'summary',
            'lowStockItems',
            'outOfStockItems',
            'recentMovements',
            'stockByCategory',
            'stockByMaterial',
            'stockValueAnalysis'
        ));
    }

    /**
     * Detailed stock report with filters
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
            }
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('item_code', 'like', '%' . $request->search . '%');
            });
        }

        $items = $query->orderBy('name')->paginate(50);

        // Get filter options
        $categories = Item::distinct()->pluck('category')->filter()->sort()->values();
        $materials = Item::distinct()->pluck('material')->filter()->sort()->values();

        return view('reports.stocks.detailed', compact(
            'items',
            'categories',
            'materials'
        ));
    }

    /**
     * Stock movement report
     */
    public function movements(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        $itemId = $request->get('item_id');

        $movements = $this->getStockMovements($startDate, $endDate, $itemId);

        $items = Item::where('is_active', true)->orderBy('name')->get();

        return view('reports.stocks.movements', compact(
            'movements',
            'items',
            'startDate',
            'endDate',
            'itemId'
        ));
    }

    /**
     * Stock valuation report
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

        return view('reports.stocks.valuation', compact(
            'items',
            'totalValue',
            'totalItems',
            'totalStock',
            'valuationMethod'
        ));
    }

    /**
     * Export stock report to PDF
     */
    public function exportPdf(Request $request)
    {
        $reportType = $request->get('type', 'summary');
        
        switch ($reportType) {
            case 'detailed':
                $items = Item::orderBy('name')->get();
                $pdf = \PDF::loadView('reports.stocks.pdf.detailed', compact('items'));
                break;
            case 'valuation':
                $items = Item::where('is_active', true)->get();
                $pdf = \PDF::loadView('reports.stocks.pdf.valuation', compact('items'));
                break;
            case 'movements':
                $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
                $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
                $movements = $this->getStockMovements($startDate, $endDate);
                $pdf = \PDF::loadView('reports.stocks.pdf.movements', compact('movements', 'startDate', 'endDate'));
                break;
            default:
                $summary = $this->getStockSummary();
                $lowStockItems = Item::whereRaw('current_stock <= minimum_stock')->get();
                $pdf = \PDF::loadView('reports.stocks.pdf.summary', compact('summary', 'lowStockItems'));
        }

        return $pdf->download("stock-report-{$reportType}-" . now()->format('Y-m-d') . ".pdf");
    }

    /**
     * Export stock report to Excel
     */
    public function exportExcel(Request $request)
    {
        $reportType = $request->get('type', 'summary');
        
        // This would typically use Laravel Excel package
        // For now, we'll return a CSV
        return $this->exportCsv($request);
    }

    /**
     * Export stock report to CSV
     */
    public function exportCsv(Request $request)
    {
        $reportType = $request->get('type', 'summary');
        
        $filename = "stock-report-{$reportType}-" . now()->format('Y-m-d') . ".csv";
        
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
     * Get stock summary statistics
     */
    private function getStockSummary()
    {
        return [
            'total_items' => Item::count(),
            'active_items' => Item::where('is_active', true)->count(),
            'total_stock_value' => Item::sum(DB::raw('current_stock * cost_price')),
            'low_stock_items' => Item::whereRaw('current_stock <= minimum_stock')->count(),
            'out_of_stock_items' => Item::where('current_stock', '<=', 0)->count(),
            'total_quantity' => Item::sum('current_stock'),
            'categories_count' => Item::distinct('category')->count(),
            'materials_count' => Item::distinct('material')->count(),
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
                'reference' => 'PO-001'
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
     * Get stock by category
     */
    private function getStockByCategory()
    {
        return Item::select('category', DB::raw('SUM(current_stock) as total_stock'), DB::raw('COUNT(*) as item_count'))
            ->where('is_active', true)
            ->groupBy('category')
            ->orderBy('total_stock', 'desc')
            ->get();
    }

    /**
     * Get stock by material
     */
    private function getStockByMaterial()
    {
        return Item::select('material', DB::raw('SUM(current_stock) as total_stock'), DB::raw('COUNT(*) as item_count'))
            ->where('is_active', true)
            ->groupBy('material')
            ->orderBy('total_stock', 'desc')
            ->get();
    }

    /**
     * Get stock value analysis
     */
    private function getStockValueAnalysis()
    {
        return [
            'high_value_items' => Item::where('is_active', true)
                ->orderBy(DB::raw('current_stock * cost_price'), 'desc')
                ->limit(10)
                ->get(),
            'low_value_items' => Item::where('is_active', true)
                ->orderBy(DB::raw('current_stock * cost_price'), 'asc')
                ->limit(10)
                ->get(),
            'zero_value_items' => Item::where('is_active', true)
                ->where('current_stock', 0)
                ->get()
        ];
    }

    /**
     * Get stock movements for a specific period
     */
    private function getStockMovements($startDate, $endDate, $itemId = null)
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
