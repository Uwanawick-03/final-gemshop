<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\SalesOrder;
use App\Models\Customer;
use App\Models\Item;
use App\Models\TransactionItem;
use App\Models\SalesAssistant;
use App\Models\SalesExecutive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalesReportController extends Controller
{
    /**
     * Display the main sales report dashboard
     */
    public function index()
    {
        // Get summary statistics
        $summary = $this->getSalesSummary();
        
        // Get recent sales
        $recentSales = Invoice::with(['customer', 'salesAssistant'])
            ->latest()
            ->limit(10)
            ->get();
        
        // Get top customers
        $topCustomers = $this->getTopCustomers();
        
        // Get top selling items
        $topSellingItems = $this->getTopSellingItems();
        
        // Get sales by status
        $salesByStatus = $this->getSalesByStatus();
        
        // Get monthly sales data
        $monthlySales = $this->getMonthlySalesData();
        
        // Get sales by payment method
        $salesByPaymentMethod = $this->getSalesByPaymentMethod();
        
        // Get sales performance by assistant
        $salesPerformance = $this->getSalesPerformance();

        return view('reports.sales.index', compact(
            'summary',
            'recentSales',
            'topCustomers',
            'topSellingItems',
            'salesByStatus',
            'monthlySales',
            'salesByPaymentMethod',
            'salesPerformance'
        ));
    }

    /**
     * Detailed sales report with filters
     */
    public function detailed(Request $request)
    {
        $query = Invoice::with(['customer', 'salesAssistant', 'currency']);

        // Apply filters
        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->filled('sales_assistant_id')) {
            $query->where('sales_assistant_id', $request->sales_assistant_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('invoice_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('invoice_date', '<=', $request->end_date);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('invoice_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('customer', function($customerQuery) use ($request) {
                      $customerQuery->where('first_name', 'like', '%' . $request->search . '%')
                                   ->orWhere('last_name', 'like', '%' . $request->search . '%')
                                   ->orWhere('company_name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $invoices = $query->orderBy('invoice_date', 'desc')->paginate(50);

        // Get filter options
        $customers = Customer::orderBy('first_name')->get();
        $salesAssistants = SalesAssistant::where('is_active', true)->orderBy('first_name')->get();

        return view('reports.sales.detailed', compact(
            'invoices',
            'customers',
            'salesAssistants'
        ));
    }

    /**
     * Sales analytics report
     */
    public function analytics(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        $period = $request->get('period', 'daily'); // daily, weekly, monthly

        $analytics = $this->getSalesAnalytics($startDate, $endDate, $period);

        return view('reports.sales.analytics', compact(
            'analytics',
            'startDate',
            'endDate',
            'period'
        ));
    }

    /**
     * Customer sales report
     */
    public function customers(Request $request)
    {
        $query = Customer::withCount(['invoices as total_invoices'])
            ->withSum('invoices as total_sales', 'total_amount');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%')
                  ->orWhere('company_name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $customers = $query->orderBy('total_sales', 'desc')->paginate(50);

        return view('reports.sales.customers', compact('customers'));
    }

    /**
     * Product sales report
     */
    public function products(Request $request)
    {
        $query = Item::withCount(['transactionItems as total_sales'])
            ->withSum('transactionItems as total_revenue', 'total_price');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('material')) {
            $query->where('material', $request->material);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('item_code', 'like', '%' . $request->search . '%');
            });
        }

        $items = $query->orderBy('total_revenue', 'desc')->paginate(50);

        // Get filter options
        $categories = Item::distinct()->pluck('category')->filter()->sort()->values();
        $materials = Item::distinct()->pluck('material')->filter()->sort()->values();

        return view('reports.sales.products', compact(
            'items',
            'categories',
            'materials'
        ));
    }

    /**
     * Export sales report to PDF
     */
    public function exportPdf(Request $request)
    {
        $reportType = $request->get('type', 'summary');
        
        switch ($reportType) {
            case 'detailed':
                $invoices = Invoice::with(['customer', 'salesAssistant', 'currency'])
                    ->orderBy('invoice_date', 'desc')
                    ->get();
                $pdf = \PDF::loadView('reports.sales.pdf.detailed', compact('invoices'));
                break;
            case 'analytics':
                $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
                $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
                $analytics = $this->getSalesAnalytics($startDate, $endDate, 'daily');
                $pdf = \PDF::loadView('reports.sales.pdf.analytics', compact('analytics', 'startDate', 'endDate'));
                break;
            case 'customers':
                $customers = Customer::withCount(['invoices as total_invoices'])
                    ->withSum('invoices as total_sales', 'total_amount')
                    ->orderBy('total_sales', 'desc')
                    ->get();
                $pdf = \PDF::loadView('reports.sales.pdf.customers', compact('customers'));
                break;
            case 'products':
                $items = Item::withCount(['transactionItems as total_sales'])
                    ->withSum('transactionItems as total_revenue', 'total_price')
                    ->orderBy('total_revenue', 'desc')
                    ->get();
                $pdf = \PDF::loadView('reports.sales.pdf.products', compact('items'));
                break;
            default:
                $summary = $this->getSalesSummary();
                $recentSales = Invoice::with(['customer'])->latest()->limit(20)->get();
                $pdf = \PDF::loadView('reports.sales.pdf.summary', compact('summary', 'recentSales'));
        }

        return $pdf->download("sales-report-{$reportType}-" . now()->format('Y-m-d') . ".pdf");
    }

    /**
     * Export sales report to Excel
     */
    public function exportExcel(Request $request)
    {
        $reportType = $request->get('type', 'summary');
        
        // This would typically use Laravel Excel package
        // For now, we'll return a CSV
        return $this->exportCsv($request);
    }

    /**
     * Export sales report to CSV
     */
    public function exportCsv(Request $request)
    {
        $reportType = $request->get('type', 'summary');
        
        $filename = "sales-report-{$reportType}-" . now()->format('Y-m-d') . ".csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($reportType) {
            $handle = fopen('php://output', 'w');
            
            switch ($reportType) {
                case 'detailed':
                    fputcsv($handle, ['Invoice Number', 'Customer', 'Date', 'Total Amount', 'Status', 'Payment Method']);
                    Invoice::with(['customer'])->chunk(100, function($invoices) use ($handle) {
                        foreach ($invoices as $invoice) {
                            fputcsv($handle, [
                                $invoice->invoice_number,
                                $invoice->customer->full_name ?? 'N/A',
                                $invoice->invoice_date->format('Y-m-d'),
                                $invoice->total_amount,
                                $invoice->status,
                                $invoice->payment_method ?? 'N/A'
                            ]);
                        }
                    });
                    break;
                default:
                    fputcsv($handle, ['Invoice Number', 'Customer', 'Date', 'Total Amount', 'Status']);
                    Invoice::with(['customer'])->chunk(100, function($invoices) use ($handle) {
                        foreach ($invoices as $invoice) {
                            fputcsv($handle, [
                                $invoice->invoice_number,
                                $invoice->customer->full_name ?? 'N/A',
                                $invoice->invoice_date->format('Y-m-d'),
                                $invoice->total_amount,
                                $invoice->status
                            ]);
                        }
                    });
            }
            
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get sales summary statistics
     */
    private function getSalesSummary()
    {
        $totalInvoices = Invoice::count();
        $totalSales = Invoice::sum('total_amount');
        $paidInvoices = Invoice::where('status', 'paid')->count();
        $paidAmount = Invoice::where('status', 'paid')->sum('total_amount');
        $overdueInvoices = Invoice::where('status', 'overdue')->count();
        $overdueAmount = Invoice::where('status', 'overdue')->sum('total_amount');
        
        // This month's sales
        $thisMonthSales = Invoice::whereMonth('invoice_date', now()->month)
            ->whereYear('invoice_date', now()->year)
            ->sum('total_amount');
        
        // Last month's sales
        $lastMonthSales = Invoice::whereMonth('invoice_date', now()->subMonth()->month)
            ->whereYear('invoice_date', now()->subMonth()->year)
            ->sum('total_amount');
        
        // Calculate growth
        $growth = $lastMonthSales > 0 ? (($thisMonthSales - $lastMonthSales) / $lastMonthSales) * 100 : 0;

        return [
            'total_invoices' => $totalInvoices,
            'total_sales' => $totalSales,
            'paid_invoices' => $paidInvoices,
            'paid_amount' => $paidAmount,
            'overdue_invoices' => $overdueInvoices,
            'overdue_amount' => $overdueAmount,
            'this_month_sales' => $thisMonthSales,
            'last_month_sales' => $lastMonthSales,
            'growth_percentage' => $growth,
            'average_invoice_value' => $totalInvoices > 0 ? $totalSales / $totalInvoices : 0
        ];
    }

    /**
     * Get top customers by sales
     */
    private function getTopCustomers()
    {
        return Customer::withSum('invoices as total_sales', 'total_amount')
            ->withCount('invoices as total_orders')
            ->orderBy('total_sales', 'desc')
            ->limit(10)
            ->get();
    }

    /**
     * Get top selling items
     */
    private function getTopSellingItems()
    {
        return Item::withCount(['transactionItems as total_sales'])
            ->withSum('transactionItems as total_revenue', 'total_price')
            ->orderBy('total_sales', 'desc')
            ->limit(10)
            ->get();
    }

    /**
     * Get sales by status
     */
    private function getSalesByStatus()
    {
        return Invoice::select('status', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('status')
            ->orderBy('total', 'desc')
            ->get();
    }

    /**
     * Get monthly sales data for charts
     */
    private function getMonthlySalesData()
    {
        $months = [];
        $sales = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');
            
            $monthlySales = Invoice::whereMonth('invoice_date', $date->month)
                ->whereYear('invoice_date', $date->year)
                ->sum('total_amount');
            
            $sales[] = $monthlySales;
        }

        return [
            'labels' => $months,
            'data' => $sales
        ];
    }

    /**
     * Get sales by payment method
     */
    private function getSalesByPaymentMethod()
    {
        return Invoice::select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_amount) as total'))
            ->whereNotNull('payment_method')
            ->groupBy('payment_method')
            ->orderBy('total', 'desc')
            ->get();
    }

    /**
     * Get sales performance by assistant
     */
    private function getSalesPerformance()
    {
        return SalesAssistant::withSum('invoices as total_sales', 'total_amount')
            ->withCount('invoices as total_orders')
            ->where('is_active', true)
            ->orderBy('total_sales', 'desc')
            ->limit(10)
            ->get();
    }

    /**
     * Get detailed sales analytics
     */
    private function getSalesAnalytics($startDate, $endDate, $period)
    {
        $query = Invoice::whereBetween('invoice_date', [$startDate, $endDate]);
        
        switch ($period) {
            case 'daily':
                $data = $query->select(
                    DB::raw('DATE(invoice_date) as period'),
                    DB::raw('COUNT(*) as invoices_count'),
                    DB::raw('SUM(total_amount) as total_sales'),
                    DB::raw('AVG(total_amount) as average_sale')
                )
                ->groupBy(DB::raw('DATE(invoice_date)'))
                ->orderBy('period')
                ->get();
                break;
            case 'weekly':
                $data = $query->select(
                    DB::raw('YEARWEEK(invoice_date) as period'),
                    DB::raw('COUNT(*) as invoices_count'),
                    DB::raw('SUM(total_amount) as total_sales'),
                    DB::raw('AVG(total_amount) as average_sale')
                )
                ->groupBy(DB::raw('YEARWEEK(invoice_date)'))
                ->orderBy('period')
                ->get();
                break;
            case 'monthly':
                $data = $query->select(
                    DB::raw('DATE_FORMAT(invoice_date, "%Y-%m") as period'),
                    DB::raw('COUNT(*) as invoices_count'),
                    DB::raw('SUM(total_amount) as total_sales'),
                    DB::raw('AVG(total_amount) as average_sale')
                )
                ->groupBy(DB::raw('DATE_FORMAT(invoice_date, "%Y-%m")'))
                ->orderBy('period')
                ->get();
                break;
            default:
                $data = collect();
        }

        return [
            'data' => $data,
            'summary' => [
                'total_invoices' => $data->sum('invoices_count'),
                'total_sales' => $data->sum('total_sales'),
                'average_sale' => $data->avg('average_sale'),
                'period' => $period
            ]
        ];
    }
}
