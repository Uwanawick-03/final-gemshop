<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\Invoice;
use App\Models\PurchaseOrder;

class DashboardController extends Controller
{
    public function index()
    {
        // Get dashboard statistics
        $totalItems = Item::count();
        $totalCustomers = Customer::count();
        $totalSuppliers = Supplier::count();
        $lowStockItems = Item::lowStock()->count();
        
        // Get low stock items list
        $lowStockItemsList = Item::lowStock()
            ->limit(10)
            ->get();
        
        // Get recent sales
        $recentSales = Invoice::with('customer')
            ->latest()
            ->limit(10)
            ->get();
        
        // Get recent purchase orders
        $recentPurchaseOrders = PurchaseOrder::with('supplier')
            ->latest()
            ->limit(10)
            ->get();
        
        // Calculate monthly sales data (mock data for now)
        $monthlySales = $this->getMonthlySalesData();
        
        return view('dashboard', compact(
            'totalItems',
            'totalCustomers', 
            'totalSuppliers',
            'lowStockItems',
            'lowStockItemsList',
            'recentSales',
            'recentPurchaseOrders',
            'monthlySales'
        ));
    }
    
    private function getMonthlySalesData()
    {
        // This would typically query the database for actual sales data
        // For now, returning mock data
        return [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'data' => [12000, 19000, 15000, 25000, 22000, 30000, 28000, 35000, 32000, 40000, 38000, 45000]
        ];
    }
}
