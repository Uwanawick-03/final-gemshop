<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\GrnController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SalesAssistantController;
use App\Http\Controllers\SalesExecutiveController;
use App\Http\Controllers\CraftsmanController;
use App\Http\Controllers\TourGuideController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\CalculatorController;

// Authentication Routes
Auth::routes();

// Redirect root to dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Public API Routes (no authentication required)
Route::get('/currency/active', [CurrencyController::class, 'getActiveCurrencies'])->name('currency.active');

// Protected Routes (require authentication)
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/home', [DashboardController::class, 'index'])->name('home');
    
    // System Routes
    Route::get('/about', function () {
        return view('about');
    })->name('about');
    
    // Calculator Routes
    Route::get('/calculator', [CalculatorController::class, 'index'])->name('calculator');
    Route::post('/calculator/jewelry', [CalculatorController::class, 'calculateJewelry'])->name('calculator.jewelry');
    Route::post('/calculator/gemstone', [CalculatorController::class, 'calculateGemstone'])->name('calculator.gemstone');
    Route::post('/calculator/profit', [CalculatorController::class, 'calculateProfitMargin'])->name('calculator.profit');
    Route::post('/calculator/tax', [CalculatorController::class, 'calculateTax'])->name('calculator.tax');
    Route::post('/calculator/discount', [CalculatorController::class, 'calculateDiscount'])->name('calculator.discount');
    Route::post('/calculator/installment', [CalculatorController::class, 'calculateInstallment'])->name('calculator.installment');
    Route::post('/calculator/convert', [CalculatorController::class, 'convertUnits'])->name('calculator.convert');
    Route::get('/calculator/history', [CalculatorController::class, 'getCalculationHistory'])->name('calculator.history');
    Route::post('/calculator/save', [CalculatorController::class, 'saveCalculation'])->name('calculator.save');
    
    Route::get('/backup', function () {
        return view('backup');
    })->name('backup');
    
    // Profile Management Routes
    Route::resource('currencies', CurrencyController::class);
    
    // Currency display routes
    Route::post('/currency/set-display', [CurrencyController::class, 'setDisplayCurrency'])->name('currency.set-display');
    Route::get('/currency/display', [CurrencyController::class, 'getDisplayCurrency'])->name('currency.display');
    Route::post('/currency/convert', [CurrencyController::class, 'convertAmount'])->name('currency.convert');
    Route::post('/currency/update-rates', [CurrencyController::class, 'updateRatesFromAPI'])->name('currency.update-rates');
    Route::resource('suppliers', SupplierController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('items', ItemController::class);
    
    // Sales Assistant Management
    Route::resource('sales-assistants', SalesAssistantController::class);
    
    // Sales Executive Management
    Route::resource('sales-executives', SalesExecutiveController::class);
    
    // Craftsman Management
    Route::resource('craftsmen', CraftsmanController::class);

    // Tour Guide Management
    Route::resource('tour-guides', TourGuideController::class);
    
    // Bank Management
    Route::resource('banks', BankController::class);
    
    // Purchase Orders
    Route::resource('purchase-orders', PurchaseOrderController::class);
    
    Route::resource('grns', GrnController::class);
    Route::post('/grns/{grn}/update-status', [GrnController::class, 'updateStatus'])->name('grns.update-status');
    Route::get('/grns/{grn}/export-pdf', [GrnController::class, 'exportPdf'])->name('grns.export-pdf');
    Route::post('/grns/bulk-status-update', [GrnController::class, 'bulkStatusUpdate'])->name('grns.bulk-status-update');
    Route::get('/grns/get-by-po', [GrnController::class, 'getGrnByPo'])->name('grns.get-by-po');
    
    Route::resource('invoices', \App\Http\Controllers\InvoiceController::class);
    Route::post('/invoices/{invoice}/update-status', [\App\Http\Controllers\InvoiceController::class, 'updateStatus'])->name('invoices.update-status');
    Route::get('/invoices/{invoice}/export-pdf', [\App\Http\Controllers\InvoiceController::class, 'exportPdf'])->name('invoices.export-pdf');
    Route::post('/invoices/bulk-status-update', [\App\Http\Controllers\InvoiceController::class, 'bulkStatusUpdate'])->name('invoices.bulk-status-update');
    Route::get('/invoices/get-by-sales-order', [\App\Http\Controllers\InvoiceController::class, 'getInvoiceBySalesOrder'])->name('invoices.get-by-sales-order');
    Route::post('/invoices/{invoice}/send-email', [\App\Http\Controllers\InvoiceController::class, 'sendEmail'])->name('invoices.send-email');
    Route::post('/invoices/{invoice}/duplicate', [\App\Http\Controllers\InvoiceController::class, 'duplicate'])->name('invoices.duplicate');
    Route::get('/invoices/dashboard/stats', [\App\Http\Controllers\InvoiceController::class, 'getDashboardStats'])->name('invoices.dashboard-stats');
    
    Route::resource('sales-orders', \App\Http\Controllers\SalesOrderController::class);
    Route::post('/sales-orders/{salesOrder}/update-status', [\App\Http\Controllers\SalesOrderController::class, 'updateStatus'])->name('sales-orders.update-status');
    
    // Stock Adjustments
    Route::resource('stock-adjustments', \App\Http\Controllers\StockAdjustmentController::class);
    Route::post('/stock-adjustments/{stockAdjustment}/approve', [\App\Http\Controllers\StockAdjustmentController::class, 'approve'])->name('stock-adjustments.approve');
    Route::post('/stock-adjustments/{stockAdjustment}/reject', [\App\Http\Controllers\StockAdjustmentController::class, 'reject'])->name('stock-adjustments.reject');
    
    Route::get('/customer-returns', function () {
        return view('customer-returns.index');
    })->name('customer-returns.index');
    
    Route::get('/supplier-returns', function () {
        return view('supplier-returns.index');
    })->name('supplier-returns.index');
    
    Route::get('/item-transfers', function () {
        return view('item-transfers.index');
    })->name('item-transfers.index');
    
    Route::get('/alteration-commissions', function () {
        return view('alteration-commissions.index');
    })->name('alteration-commissions.index');
    
    // Workshop Routes
    Route::get('/job-issues', function () {
        return view('job-issues.index');
    })->name('job-issues.index');
    
    Route::get('/finished-good-transfers', function () {
        return view('finished-good-transfers.index');
    })->name('finished-good-transfers.index');
    
    Route::get('/workshop-adjustments', function () {
        return view('workshop-adjustments.index');
    })->name('workshop-adjustments.index');
    
    Route::get('/craftsman-returns', function () {
        return view('craftsman-returns.index');
    })->name('craftsman-returns.index');
    
    Route::get('/mtcs', function () {
        return view('mtcs.index');
    })->name('mtcs.index');
    
    // Reports Routes
    Route::get('/reports/stocks', function () {
        return view('reports.stocks');
    })->name('reports.stocks');
    
    Route::get('/reports/sales', function () {
        return view('reports.sales');
    })->name('reports.sales');
    
    Route::get('/reports/inventory', function () {
        return view('reports.inventory');
    })->name('reports.inventory');
    
    Route::get('/reports/workshop', function () {
        return view('reports.workshop');
    })->name('reports.workshop');
    
    Route::get('/reports/guide-listing', function () {
        return view('reports.guide-listing');
    })->name('reports.guide-listing');
    
    // User Management Routes
    Route::resource('users', \App\Http\Controllers\UserController::class);
    
    Route::resource('permissions', PermissionController::class)->except(['show']);
    Route::resource('roles', RoleController::class)->except(['show']);
});
