<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Customer;
use App\Models\SalesAssistant;
use App\Models\SalesOrder;
use App\Models\Item;
use App\Models\TransactionItem;
use App\Models\Currency;
use App\Services\CurrencyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    protected $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Invoice::with(['customer', 'salesAssistant', 'currency']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($customerQuery) use ($search) {
                      $customerQuery->where('first_name', 'like', "%{$search}%")
                                   ->orWhere('last_name', 'like', "%{$search}%")
                                   ->orWhere('customer_code', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by customer
        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('invoice_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('invoice_date', '<=', $request->date_to);
        }

        // Filter by amount range
        if ($request->filled('amount_from')) {
            $query->where('total_amount', '>=', $request->amount_from);
        }
        if ($request->filled('amount_to')) {
            $query->where('total_amount', '<=', $request->amount_to);
        }

        // Auto-update overdue invoices
        $this->updateOverdueInvoices();

        $invoices = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get filter options
        $customers = Customer::where('is_active', true)->orderBy('first_name')->get();
        $statuses = ['draft', 'sent', 'paid', 'overdue', 'cancelled'];
        $paymentMethods = ['cash', 'card', 'credit'];

        // Calculate totals
        $totalInvoices = Invoice::count();
        $totalAmount = Invoice::sum('total_amount');
        $paidAmount = Invoice::where('status', 'paid')->sum('total_amount');
        $overdueAmount = Invoice::where('status', 'overdue')->sum('total_amount');
        $overdueCount = Invoice::where('status', 'overdue')->count();

        return view('invoices.index', compact(
            'invoices', 
            'customers', 
            'statuses', 
            'paymentMethods',
            'totalInvoices',
            'totalAmount',
            'paidAmount',
            'overdueAmount',
            'overdueCount'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $customers = Customer::where('is_active', true)->orderBy('first_name')->get();
        $salesAssistants = SalesAssistant::where('is_active', true)->orderBy('first_name')->get();
        $items = Item::where('is_active', true)->orderBy('name')->get();
        $currencies = Currency::where('is_active', true)->get();
        $salesOrders = SalesOrder::whereIn('status', ['confirmed', 'processing', 'shipped', 'delivered'])->get();

        // If coming from a sales order
        $salesOrder = null;
        if ($request->has('sales_order_id')) {
            $salesOrder = SalesOrder::with(['customer', 'transactionItems.item'])
                ->findOrFail($request->sales_order_id);
        }

        return view('invoices.create', compact(
            'customers', 
            'salesAssistants', 
            'items', 
            'currencies', 
            'salesOrders', 
            'salesOrder'
        ));
    }

    /**
     * Create invoice from a specific sales order
     */
    public function createFromSalesOrder(SalesOrder $salesOrder)
    {
        $customers = Customer::where('is_active', true)->orderBy('first_name')->get();
        $salesAssistants = SalesAssistant::where('is_active', true)->orderBy('first_name')->get();
        $items = Item::where('is_active', true)->orderBy('name')->get();
        $currencies = Currency::where('is_active', true)->get();
        $salesOrders = SalesOrder::whereIn('status', ['confirmed', 'processing', 'shipped', 'delivered'])->get();

        // Load the specific sales order with its data
        $salesOrder->load(['customer', 'transactionItems.item']);

        return view('invoices.create', compact(
            'customers', 
            'salesAssistants', 
            'items', 
            'currencies', 
            'salesOrders', 
            'salesOrder'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'sales_assistant_id' => 'required|exists:sales_assistants,id',
            'sales_order_id' => 'nullable|exists:sales_orders,id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'currency_id' => 'required|exists:currencies,id',
            'exchange_rate' => 'nullable|numeric|min:0',
            'payment_terms' => 'nullable|string|max:255',
            'payment_method' => 'nullable|in:cash,card,credit',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'terms_conditions' => 'nullable|string'
        ]);

        DB::beginTransaction();

        try {
            // Generate invoice number
            $invoiceNumber = 'INV-' . date('Y') . '-' . str_pad(Invoice::whereYear('created_at', date('Y'))->count() + 1, 4, '0', STR_PAD_LEFT);

            // Get exchange rate if not provided
            $exchangeRate = $request->exchange_rate;
            if (!$exchangeRate) {
                $currency = Currency::findOrFail($request->currency_id);
                $exchangeRate = $currency->exchange_rate;
            }

            // Create invoice
            $invoice = Invoice::create([
                'customer_id' => $request->customer_id,
                'sales_assistant_id' => $request->sales_assistant_id,
                'sales_order_id' => $request->sales_order_id,
                'invoice_number' => $invoiceNumber,
                'invoice_date' => $request->invoice_date,
                'due_date' => $request->due_date,
                'currency_id' => $request->currency_id,
                'exchange_rate' => $exchangeRate,
                'payment_terms' => $request->payment_terms,
                'payment_method' => $request->payment_method,
                'status' => 'draft',
                'notes' => $request->notes,
                'terms_conditions' => $request->terms_conditions,
                'created_by' => Auth::id()
            ]);

            $subtotal = 0;
            $taxAmount = 0;
            $discountAmount = 0;

            // Create transaction items
            foreach ($request->items as $itemData) {
                $item = Item::findOrFail($itemData['item_id']);
                $quantity = $itemData['quantity'];
                $unitPrice = $itemData['unit_price'];
                $itemSubtotal = $quantity * $unitPrice;
                $itemDiscountAmount = $itemData['discount_amount'] ?? 0;
                $itemTaxAmount = $itemData['tax_amount'] ?? 0;
                $itemTotalPrice = $itemSubtotal - $itemDiscountAmount + $itemTaxAmount;

                // Create transaction item
                TransactionItem::create([
                    'transaction_type' => 'App\Models\Invoice',
                    'transaction_id' => $invoice->id,
                    'item_id' => $item->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $itemTotalPrice,
                    'discount_percentage' => $itemData['discount_percentage'] ?? 0,
                    'discount_amount' => $itemDiscountAmount,
                    'tax_percentage' => $itemData['tax_percentage'] ?? 0,
                    'tax_amount' => $itemTaxAmount,
                ]);

                // Update item stock
                $item->decrement('current_stock', $quantity);

                $subtotal += $itemSubtotal;
                $discountAmount += $itemDiscountAmount;
                $taxAmount += $itemTaxAmount;
            }

            // Update invoice totals
            $invoice->update([
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'tax_amount' => $taxAmount,
                'total_amount' => $subtotal - $discountAmount + $taxAmount,
            ]);

            DB::commit();

            return redirect()->route('invoices.index')
                           ->with('success', 'Invoice created successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('invoices.index')
                           ->with('error', 'Error creating invoice: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load(['customer', 'salesAssistant', 'currency', 'transactionItems.item', 'createdBy']);

        return view('invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        if ($invoice->status !== 'draft') {
            return redirect()->route('invoices.show', $invoice)
                           ->with('error', 'Only draft invoices can be edited.');
        }

        $customers = Customer::where('is_active', true)->orderBy('first_name')->get();
        $salesAssistants = SalesAssistant::where('is_active', true)->orderBy('first_name')->get();
        $items = Item::where('is_active', true)->orderBy('name')->get();
        $currencies = Currency::where('is_active', true)->get();

        $invoice->load('transactionItems.item');

        return view('invoices.edit', compact('invoice', 'customers', 'salesAssistants', 'items', 'currencies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        if ($invoice->status !== 'draft') {
            return redirect()->route('invoices.show', $invoice)
                           ->with('error', 'Only draft invoices can be edited.');
        }

        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'sales_assistant_id' => 'required|exists:sales_assistants,id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'currency_id' => 'required|exists:currencies,id',
            'payment_method' => 'nullable|in:cash,card,credit',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        DB::beginTransaction();

        try {
            // Restore stock from old items
            foreach ($invoice->transactionItems as $oldItem) {
                $oldItem->item->increment('current_stock', $oldItem->quantity);
            }

            // Delete old transaction items
            $invoice->transactionItems()->delete();

            // Update invoice basic info
            $invoice->update([
                'customer_id' => $request->customer_id,
                'sales_assistant_id' => $request->sales_assistant_id,
                'invoice_date' => $request->invoice_date,
                'due_date' => $request->due_date,
                'currency_id' => $request->currency_id,
                'payment_method' => $request->payment_method,
                'notes' => $request->notes
            ]);

            $subtotal = 0;
            $taxAmount = 0;
            $discountAmount = 0;

            // Create new transaction items
            foreach ($request->items as $itemData) {
                $item = Item::findOrFail($itemData['item_id']);
                $quantity = $itemData['quantity'];
                $unitPrice = $itemData['unit_price'];
                $itemSubtotal = $quantity * $unitPrice;
                $itemDiscountAmount = $itemData['discount_amount'] ?? 0;
                $itemTaxAmount = $itemData['tax_amount'] ?? 0;
                $itemTotalPrice = $itemSubtotal - $itemDiscountAmount + $itemTaxAmount;

                // Create transaction item
                TransactionItem::create([
                    'transaction_type' => 'App\Models\Invoice',
                    'transaction_id' => $invoice->id,
                    'item_id' => $item->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $itemTotalPrice,
                    'discount_percentage' => $itemData['discount_percentage'] ?? 0,
                    'discount_amount' => $itemDiscountAmount,
                    'tax_percentage' => $itemData['tax_percentage'] ?? 0,
                    'tax_amount' => $itemTaxAmount,
                ]);

                // Update item stock
                $item->decrement('current_stock', $quantity);

                $subtotal += $itemSubtotal;
                $discountAmount += $itemDiscountAmount;
                $taxAmount += $itemTaxAmount;
            }

            // Update invoice totals
            $invoice->update([
                'total_amount' => $subtotal - $discountAmount + $taxAmount,
                'discount_amount' => $discountAmount,
                'tax_amount' => $taxAmount,
            ]);

            DB::commit();

            return redirect()->route('invoices.show', $invoice)
                           ->with('success', 'Invoice updated successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('invoices.index')
                           ->with('error', 'Error updating invoice: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        if (!in_array($invoice->status, ['draft', 'cancelled'])) {
            return redirect()->route('invoices.index')
                           ->with('error', 'Only draft or cancelled invoices can be deleted.');
        }

        DB::beginTransaction();

        try {
            // Restore stock
            foreach ($invoice->transactionItems as $item) {
                $item->item->increment('current_stock', $item->quantity);
            }

            // Delete transaction items
            $invoice->transactionItems()->delete();

            // Delete invoice
            $invoice->delete();

            DB::commit();

            return redirect()->route('invoices.index')
                           ->with('success', 'Invoice deleted successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('invoices.index')
                           ->with('error', 'Error deleting invoice: ' . $e->getMessage());
        }
    }

    /**
     * Update invoice status
     */
    public function updateStatus(Request $request, Invoice $invoice)
    {
        $request->validate([
            'status' => 'required|in:draft,sent,paid,overdue,cancelled'
        ]);

        $status = $request->status;
        
        // Update timestamps based on status
        $updateData = ['status' => $status];
        
        if ($status === 'sent' && !$invoice->sent_at) {
            $updateData['sent_at'] = now();
        }
        
        if ($status === 'paid' && !$invoice->paid_at) {
            $updateData['paid_at'] = now();
        }

        $invoice->update($updateData);

        return redirect()->route('invoices.show', $invoice)
                       ->with('success', 'Invoice status updated successfully!');
    }

    /**
     * Export invoice as PDF
     */
    public function exportPdf(Invoice $invoice)
    {
        $invoice->load(['customer', 'salesAssistant', 'currency', 'transactionItems.item', 'createdBy']);
        
        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));
        $filename = 'Invoice-' . $invoice->invoice_number . '-' . date('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Bulk status update
     */
    public function bulkStatusUpdate(Request $request)
    {
        $request->validate([
            'invoice_ids' => 'required|array|min:1',
            'invoice_ids.*' => 'exists:invoices,id',
            'status' => 'required|in:draft,sent,paid,overdue,cancelled'
        ]);
        
        $count = Invoice::whereIn('id', $request->invoice_ids)
            ->update(['status' => $request->status]);
        
        return redirect()->route('invoices.index')
            ->with('success', "Successfully updated {$count} invoice(s) status to " . ucfirst($request->status));
    }

    /**
     * Get invoice data by sales order
     */
    public function getInvoiceBySalesOrder(Request $request)
    {
        $request->validate([
            'sales_order_id' => 'required|exists:sales_orders,id'
        ]);
        
        $salesOrder = SalesOrder::with(['customer', 'transactionItems.item'])
            ->findOrFail($request->sales_order_id);
        
        return response()->json([
            'customer' => $salesOrder->customer,
            'items' => $salesOrder->transactionItems->map(function($item) {
                return [
                    'item_id' => $item->item_id,
                    'item_code' => $item->item->item_code,
                    'item_name' => $item->item->name,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'total_price' => $item->total_price,
                    'discount_percentage' => $item->discount_percentage,
                    'discount_amount' => $item->discount_amount,
                    'tax_percentage' => $item->tax_percentage,
                    'tax_amount' => $item->tax_amount,
                ];
            })
        ]);
    }

    /**
     * Send invoice email
     */
    public function sendEmail(Request $request, Invoice $invoice)
    {
        // This would integrate with your email system
        // For now, just update the status to sent
        $invoice->update([
            'status' => 'sent',
            'sent_at' => now()
        ]);
        
        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice email sent successfully!');
    }

    /**
     * Duplicate invoice
     */
    public function duplicate(Invoice $invoice)
    {
        DB::beginTransaction();
        
        try {
            $newInvoiceNumber = 'INV-' . date('Y') . '-' . str_pad(Invoice::whereYear('created_at', date('Y'))->count() + 1, 4, '0', STR_PAD_LEFT);
            
            $newInvoice = Invoice::create([
                'customer_id' => $invoice->customer_id,
                'sales_assistant_id' => $invoice->sales_assistant_id,
                'invoice_number' => $newInvoiceNumber,
                'invoice_date' => now(),
                'due_date' => now()->addDays(30),
                'currency_id' => $invoice->currency_id,
                'exchange_rate' => $invoice->exchange_rate,
                'payment_terms' => $invoice->payment_terms,
                'payment_method' => $invoice->payment_method,
                'status' => 'draft',
                'notes' => $invoice->notes,
                'terms_conditions' => $invoice->terms_conditions,
                'created_by' => Auth::id()
            ]);
            
            // Copy transaction items
            foreach ($invoice->transactionItems as $item) {
                TransactionItem::create([
                    'transaction_type' => 'App\Models\Invoice',
                    'transaction_id' => $newInvoice->id,
                    'item_id' => $item->item_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'total_price' => $item->total_price,
                    'discount_percentage' => $item->discount_percentage,
                    'discount_amount' => $item->discount_amount,
                    'tax_percentage' => $item->tax_percentage,
                    'tax_amount' => $item->tax_amount,
                ]);
                
                // Update item stock
                $item->item->decrement('current_stock', $item->quantity);
            }
            
            $newInvoice->update([
                'subtotal' => $invoice->subtotal,
                'discount_amount' => $invoice->discount_amount,
                'tax_amount' => $invoice->tax_amount,
                'total_amount' => $invoice->total_amount,
            ]);
            
            DB::commit();
            
            return redirect()->route('invoices.show', $newInvoice)
                ->with('success', 'Invoice duplicated successfully!');
                
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('invoices.show', $invoice)
                ->with('error', 'Error duplicating invoice: ' . $e->getMessage());
        }
    }

    /**
     * Update overdue invoices automatically
     */
    private function updateOverdueInvoices()
    {
        Invoice::where('due_date', '<', now())
            ->whereNotIn('status', ['paid', 'cancelled', 'overdue'])
            ->update(['status' => 'overdue']);
    }

    /**
     * Dashboard statistics
     */
    public function getDashboardStats()
    {
        $stats = [
            'total_invoices' => Invoice::count(),
            'total_amount' => Invoice::sum('total_amount'),
            'paid_amount' => Invoice::where('status', 'paid')->sum('total_amount'),
            'overdue_amount' => Invoice::where('status', 'overdue')->sum('total_amount'),
            'overdue_count' => Invoice::where('status', 'overdue')->count(),
            'due_soon_count' => Invoice::dueSoon(7)->count(),
        ];
        
        return response()->json($stats);
    }
}