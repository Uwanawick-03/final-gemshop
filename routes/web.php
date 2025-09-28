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
use App\Http\Controllers\CustomerReturnController;

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
    Route::get('/about', [\App\Http\Controllers\AboutController::class, 'index'])->name('about');
    Route::get('/about/system-info', [\App\Http\Controllers\AboutController::class, 'systemInfo'])->name('about.system-info');
    
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
    
// Invoice routes - specific routes must come before resource routes
Route::get('/invoices/get-by-sales-order', [\App\Http\Controllers\InvoiceController::class, 'getInvoiceBySalesOrder'])->name('invoices.get-by-sales-order');
Route::get('/invoices/create-from-sales-order/{salesOrder}', [\App\Http\Controllers\InvoiceController::class, 'createFromSalesOrder'])->name('invoices.create-from-sales-order');
Route::get('/invoices/dashboard/stats', [\App\Http\Controllers\InvoiceController::class, 'getDashboardStats'])->name('invoices.dashboard-stats');
Route::post('/invoices/bulk-status-update', [\App\Http\Controllers\InvoiceController::class, 'bulkStatusUpdate'])->name('invoices.bulk-status-update');

// Invoice resource routes
Route::resource('invoices', \App\Http\Controllers\InvoiceController::class);

// Additional invoice routes
Route::post('/invoices/{invoice}/update-status', [\App\Http\Controllers\InvoiceController::class, 'updateStatus'])->name('invoices.update-status');
Route::get('/invoices/{invoice}/export-pdf', [\App\Http\Controllers\InvoiceController::class, 'exportPdf'])->name('invoices.export-pdf');
Route::post('/invoices/{invoice}/send-email', [\App\Http\Controllers\InvoiceController::class, 'sendEmail'])->name('invoices.send-email');
Route::post('/invoices/{invoice}/duplicate', [\App\Http\Controllers\InvoiceController::class, 'duplicate'])->name('invoices.duplicate');

// Supplier Return routes - specific routes must come before resource routes
Route::get('/supplier-returns/exchange-rates', [\App\Http\Controllers\SupplierReturnController::class, 'getExchangeRates'])->name('supplier-returns.exchange-rates');
Route::post('/supplier-returns/bulk-status-update', [\App\Http\Controllers\SupplierReturnController::class, 'bulkStatusUpdate'])->name('supplier-returns.bulk-status-update');

// Supplier Return resource routes
Route::resource('supplier-returns', \App\Http\Controllers\SupplierReturnController::class);

// Additional supplier return routes
Route::post('/supplier-returns/{supplierReturn}/update-status', [\App\Http\Controllers\SupplierReturnController::class, 'updateStatus'])->name('supplier-returns.update-status');
Route::get('/supplier-returns/{supplierReturn}/export-pdf', [\App\Http\Controllers\SupplierReturnController::class, 'exportPdf'])->name('supplier-returns.export-pdf');
    
    Route::resource('sales-orders', \App\Http\Controllers\SalesOrderController::class);
    Route::post('/sales-orders/{salesOrder}/update-status', [\App\Http\Controllers\SalesOrderController::class, 'updateStatus'])->name('sales-orders.update-status');
    
    // Stock Adjustments
    Route::resource('stock-adjustments', \App\Http\Controllers\StockAdjustmentController::class);
    Route::post('/stock-adjustments/{stockAdjustment}/approve', [\App\Http\Controllers\StockAdjustmentController::class, 'approve'])->name('stock-adjustments.approve');
    Route::post('/stock-adjustments/{stockAdjustment}/reject', [\App\Http\Controllers\StockAdjustmentController::class, 'reject'])->name('stock-adjustments.reject');
    
    // Customer Returns
    Route::resource('customer-returns', \App\Http\Controllers\CustomerReturnController::class);
    Route::post('/customer-returns/{customerReturn}/update-status', [\App\Http\Controllers\CustomerReturnController::class, 'updateStatus'])->name('customer-returns.update-status');
    Route::get('/customer-returns/{customerReturn}/export-pdf', [\App\Http\Controllers\CustomerReturnController::class, 'exportPdf'])->name('customer-returns.export-pdf');
    Route::get('/customer-returns/{customer}/items', [\App\Http\Controllers\CustomerReturnController::class, 'getCustomerItems'])->name('customer-returns.customer-items');
    Route::get('/customer-returns/exchange-rates', [\App\Http\Controllers\CustomerReturnController::class, 'getExchangeRates'])->name('customer-returns.exchange-rates');
    
    
    // Item Transfer routes - specific routes must come before resource routes
    Route::get('/item-transfers/get-item-stock', [\App\Http\Controllers\ItemTransferController::class, 'getItemStock'])->name('item-transfers.get-item-stock');
    Route::post('/item-transfers/bulk-status-update', [\App\Http\Controllers\ItemTransferController::class, 'bulkStatusUpdate'])->name('item-transfers.bulk-status-update');

    // Item Transfer resource routes
    Route::resource('item-transfers', \App\Http\Controllers\ItemTransferController::class);

    // Additional item transfer routes
    Route::post('/item-transfers/{itemTransfer}/update-status', [\App\Http\Controllers\ItemTransferController::class, 'updateStatus'])->name('item-transfers.update-status');
    Route::get('/item-transfers/{itemTransfer}/export-pdf', [\App\Http\Controllers\ItemTransferController::class, 'exportPdf'])->name('item-transfers.export-pdf');
    Route::get('/item-transfers-debug', function() { return view('item-transfers.debug'); })->name('item-transfers.debug');
    
    // Alteration Commission routes - specific routes must come before resource routes
    Route::get('/alteration-commissions/exchange-rates', [\App\Http\Controllers\AlterationCommissionController::class, 'getExchangeRates'])->name('alteration-commissions.exchange-rates');
    Route::post('/alteration-commissions/bulk-status-update', [\App\Http\Controllers\AlterationCommissionController::class, 'bulkStatusUpdate'])->name('alteration-commissions.bulk-status-update');

    // Alteration Commission resource routes
    Route::resource('alteration-commissions', \App\Http\Controllers\AlterationCommissionController::class);

    // Additional alteration commission routes
    Route::post('/alteration-commissions/{alterationCommission}/update-status', [\App\Http\Controllers\AlterationCommissionController::class, 'updateStatus'])->name('alteration-commissions.update-status');
    Route::post('/alteration-commissions/{alterationCommission}/update-payment', [\App\Http\Controllers\AlterationCommissionController::class, 'updatePayment'])->name('alteration-commissions.update-payment');
    Route::get('/alteration-commissions/{alterationCommission}/export-pdf', [\App\Http\Controllers\AlterationCommissionController::class, 'exportPdf'])->name('alteration-commissions.export-pdf');
    
    // Job Issues Routes - specific routes must come before resource routes
    Route::get('/job-issues/get-by-status/{status}', [\App\Http\Controllers\JobIssueController::class, 'getByStatus'])->name('job-issues.get-by-status');
    Route::post('/job-issues/bulk-status-update', [\App\Http\Controllers\JobIssueController::class, 'bulkStatusUpdate'])->name('job-issues.bulk-status-update');

    // Job Issues resource routes
    Route::resource('job-issues', \App\Http\Controllers\JobIssueController::class);

    // Additional job issues routes
    Route::post('/job-issues/{jobIssue}/update-status', [\App\Http\Controllers\JobIssueController::class, 'updateStatus'])->name('job-issues.update-status');
    Route::get('/job-issues/{jobIssue}/export-pdf', [\App\Http\Controllers\JobIssueController::class, 'exportPdf'])->name('job-issues.export-pdf');
    
    // Finished Good Transfers Routes - specific routes must come before resource routes
    Route::get('/finished-good-transfers/get-by-status/{status}', [\App\Http\Controllers\FinishedGoodTransferController::class, 'getByStatus'])->name('finished-good-transfers.get-by-status');
    Route::post('/finished-good-transfers/bulk-status-update', [\App\Http\Controllers\FinishedGoodTransferController::class, 'bulkStatusUpdate'])->name('finished-good-transfers.bulk-status-update');

    // Finished Good Transfers resource routes
    Route::resource('finished-good-transfers', \App\Http\Controllers\FinishedGoodTransferController::class);

    // Additional finished good transfers routes
    Route::post('/finished-good-transfers/{finishedGoodTransfer}/update-status', [\App\Http\Controllers\FinishedGoodTransferController::class, 'updateStatus'])->name('finished-good-transfers.update-status');
    Route::post('/finished-good-transfers/{finishedGoodTransfer}/quality-check', [\App\Http\Controllers\FinishedGoodTransferController::class, 'qualityCheck'])->name('finished-good-transfers.quality-check');
    Route::post('/finished-good-transfers/{finishedGoodTransfer}/complete-transfer', [\App\Http\Controllers\FinishedGoodTransferController::class, 'completeTransfer'])->name('finished-good-transfers.complete-transfer');
    Route::get('/finished-good-transfers/{finishedGoodTransfer}/export-pdf', [\App\Http\Controllers\FinishedGoodTransferController::class, 'exportPdf'])->name('finished-good-transfers.export-pdf');
    
    // Workshop Adjustments Routes - specific routes must come before resource routes
    Route::get('/workshop-adjustments/get-by-status/{status}', [\App\Http\Controllers\WorkshopAdjustmentController::class, 'getByStatus'])->name('workshop-adjustments.get-by-status');
    Route::post('/workshop-adjustments/bulk-status-update', [\App\Http\Controllers\WorkshopAdjustmentController::class, 'bulkStatusUpdate'])->name('workshop-adjustments.bulk-status-update');

    // Workshop Adjustments resource routes
    Route::resource('workshop-adjustments', \App\Http\Controllers\WorkshopAdjustmentController::class);

    // Additional workshop adjustments routes
    Route::post('/workshop-adjustments/{workshopAdjustment}/approve', [\App\Http\Controllers\WorkshopAdjustmentController::class, 'approve'])->name('workshop-adjustments.approve');
    Route::post('/workshop-adjustments/{workshopAdjustment}/reject', [\App\Http\Controllers\WorkshopAdjustmentController::class, 'reject'])->name('workshop-adjustments.reject');
    Route::get('/workshop-adjustments/{workshopAdjustment}/export-pdf', [\App\Http\Controllers\WorkshopAdjustmentController::class, 'exportPdf'])->name('workshop-adjustments.export-pdf');
    
    // Craftsman Returns Routes - specific routes must come before resource routes
    Route::get('/craftsman-returns/get-by-status/{status}', [\App\Http\Controllers\CraftsmanReturnController::class, 'getByStatus'])->name('craftsman-returns.get-by-status');
    Route::get('/craftsman-returns/get-by-craftsman/{craftsmanId}', [\App\Http\Controllers\CraftsmanReturnController::class, 'getByCraftsman'])->name('craftsman-returns.get-by-craftsman');
    Route::post('/craftsman-returns/bulk-status-update', [\App\Http\Controllers\CraftsmanReturnController::class, 'bulkStatusUpdate'])->name('craftsman-returns.bulk-status-update');

    // Craftsman Returns resource routes
    Route::resource('craftsman-returns', \App\Http\Controllers\CraftsmanReturnController::class);

    // Additional craftsman returns routes
    Route::post('/craftsman-returns/{craftsmanReturn}/approve', [\App\Http\Controllers\CraftsmanReturnController::class, 'approve'])->name('craftsman-returns.approve');
    Route::post('/craftsman-returns/{craftsmanReturn}/reject', [\App\Http\Controllers\CraftsmanReturnController::class, 'reject'])->name('craftsman-returns.reject');
    Route::post('/craftsman-returns/{craftsmanReturn}/complete', [\App\Http\Controllers\CraftsmanReturnController::class, 'complete'])->name('craftsman-returns.complete');
    Route::get('/craftsman-returns/{craftsmanReturn}/export-pdf', [\App\Http\Controllers\CraftsmanReturnController::class, 'exportPdf'])->name('craftsman-returns.export-pdf');
    
    Route::get('/mtcs', function () {
        return view('mtcs.index');
    })->name('mtcs.index');
    
    // Reports Routes
    Route::prefix('reports')->name('reports.')->group(function () {
        // Stocks Report Routes
        Route::get('/stocks', [\App\Http\Controllers\StockReportController::class, 'index'])->name('stocks.index');
        Route::get('/stocks/detailed', [\App\Http\Controllers\StockReportController::class, 'detailed'])->name('stocks.detailed');
        Route::get('/stocks/movements', [\App\Http\Controllers\StockReportController::class, 'movements'])->name('stocks.movements');
        Route::get('/stocks/valuation', [\App\Http\Controllers\StockReportController::class, 'valuation'])->name('stocks.valuation');
        Route::get('/stocks/export-pdf', [\App\Http\Controllers\StockReportController::class, 'exportPdf'])->name('stocks.export-pdf');
        Route::get('/stocks/export-excel', [\App\Http\Controllers\StockReportController::class, 'exportExcel'])->name('stocks.export-excel');
        Route::get('/stocks/export-csv', [\App\Http\Controllers\StockReportController::class, 'exportCsv'])->name('stocks.export-csv');
    });
    
    // Sales Report Routes
    Route::prefix('reports')->name('reports.')->group(function () {
        // Sales Report Routes
        Route::get('/sales', [\App\Http\Controllers\SalesReportController::class, 'index'])->name('sales.index');
        Route::get('/sales/detailed', [\App\Http\Controllers\SalesReportController::class, 'detailed'])->name('sales.detailed');
        Route::get('/sales/analytics', [\App\Http\Controllers\SalesReportController::class, 'analytics'])->name('sales.analytics');
        Route::get('/sales/customers', [\App\Http\Controllers\SalesReportController::class, 'customers'])->name('sales.customers');
        Route::get('/sales/products', [\App\Http\Controllers\SalesReportController::class, 'products'])->name('sales.products');
        Route::get('/sales/export-pdf', [\App\Http\Controllers\SalesReportController::class, 'exportPdf'])->name('sales.export-pdf');
        Route::get('/sales/export-excel', [\App\Http\Controllers\SalesReportController::class, 'exportExcel'])->name('sales.export-excel');
        Route::get('/sales/export-csv', [\App\Http\Controllers\SalesReportController::class, 'exportCsv'])->name('sales.export-csv');
    });
    
    // Inventory Report Routes
    Route::prefix('reports')->name('reports.')->group(function () {
        // Inventory Report Routes
        Route::get('/inventory', [\App\Http\Controllers\InventoryReportController::class, 'index'])->name('inventory.index');
        Route::get('/inventory/detailed', [\App\Http\Controllers\InventoryReportController::class, 'detailed'])->name('inventory.detailed');
        Route::get('/inventory/movements', [\App\Http\Controllers\InventoryReportController::class, 'movements'])->name('inventory.movements');
        Route::get('/inventory/valuation', [\App\Http\Controllers\InventoryReportController::class, 'valuation'])->name('inventory.valuation');
        Route::get('/inventory/adjustments', [\App\Http\Controllers\InventoryReportController::class, 'adjustments'])->name('inventory.adjustments');
        Route::get('/inventory/transfers', [\App\Http\Controllers\InventoryReportController::class, 'transfers'])->name('inventory.transfers');
        Route::get('/inventory/export-pdf', [\App\Http\Controllers\InventoryReportController::class, 'exportPdf'])->name('inventory.export-pdf');
        Route::get('/inventory/export-excel', [\App\Http\Controllers\InventoryReportController::class, 'exportExcel'])->name('inventory.export-excel');
        Route::get('/inventory/export-csv', [\App\Http\Controllers\InventoryReportController::class, 'exportCsv'])->name('inventory.export-csv');
    });
    
    // Workshop Report Routes
    Route::prefix('reports')->name('reports.')->group(function () {
        // Workshop Report Routes
        Route::get('/workshop', [\App\Http\Controllers\WorkshopReportController::class, 'index'])->name('workshop.index');
        Route::get('/workshop/detailed', [\App\Http\Controllers\WorkshopReportController::class, 'detailed'])->name('workshop.detailed');
        Route::get('/workshop/job-issues', [\App\Http\Controllers\WorkshopReportController::class, 'jobIssues'])->name('workshop.job-issues');
        Route::get('/workshop/adjustments', [\App\Http\Controllers\WorkshopReportController::class, 'adjustments'])->name('workshop.adjustments');
        Route::get('/workshop/transfers', [\App\Http\Controllers\WorkshopReportController::class, 'transfers'])->name('workshop.transfers');
        Route::get('/workshop/returns', [\App\Http\Controllers\WorkshopReportController::class, 'returns'])->name('workshop.returns');
        Route::get('/workshop/mtcs', [\App\Http\Controllers\WorkshopReportController::class, 'mtcs'])->name('workshop.mtcs');
        Route::get('/workshop/export-pdf', [\App\Http\Controllers\WorkshopReportController::class, 'exportPdf'])->name('workshop.export-pdf');
        Route::get('/workshop/export-excel', [\App\Http\Controllers\WorkshopReportController::class, 'exportExcel'])->name('workshop.export-excel');
        Route::get('/workshop/export-csv', [\App\Http\Controllers\WorkshopReportController::class, 'exportCsv'])->name('workshop.export-csv');
    });
    
    // Guide Listing Report Routes
    Route::prefix('reports')->name('reports.')->group(function () {
        // Guide Listing Report Routes
        Route::get('/guide-listing', [\App\Http\Controllers\GuideListingController::class, 'index'])->name('guide-listing');
        Route::get('/guide-listing/detailed', [\App\Http\Controllers\GuideListingController::class, 'detailed'])->name('guide-listing.detailed');
        Route::get('/guide-listing/performance', [\App\Http\Controllers\GuideListingController::class, 'performance'])->name('guide-listing.performance');
        Route::get('/guide-listing/compliance', [\App\Http\Controllers\GuideListingController::class, 'compliance'])->name('guide-listing.compliance');
        Route::get('/guide-listing/export-pdf', [\App\Http\Controllers\GuideListingController::class, 'exportPdf'])->name('guide-listing.export-pdf');
        Route::get('/guide-listing/export-excel', [\App\Http\Controllers\GuideListingController::class, 'exportExcel'])->name('guide-listing.export-excel');
        Route::get('/guide-listing/export-csv', [\App\Http\Controllers\GuideListingController::class, 'exportCsv'])->name('guide-listing.export-csv');
    });

    // MTC Routes
    Route::post('/mtcs/bulk-status-update', [\App\Http\Controllers\MtcController::class, 'bulkStatusUpdate'])->name('mtcs.bulk-status-update');
    Route::resource('mtcs', \App\Http\Controllers\MtcController::class);
    Route::post('/mtcs/{mtc}/update-status', [\App\Http\Controllers\MtcController::class, 'updateStatus'])->name('mtcs.update-status');
    Route::get('/mtcs/{mtc}/export-pdf', [\App\Http\Controllers\MtcController::class, 'exportPdf'])->name('mtcs.export-pdf');
    
    // User Management Routes
    Route::resource('users', \App\Http\Controllers\UserController::class);
    
    Route::resource('permissions', PermissionController::class)->except(['show']);
    Route::resource('roles', RoleController::class)->except(['show']);
});
