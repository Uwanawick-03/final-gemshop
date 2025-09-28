<?php

namespace App\Http\Controllers;

use App\Models\SupplierReturn;
use App\Models\Supplier;
use App\Models\Item;
use App\Models\TransactionItem;
use App\Models\Currency;
use App\Services\CurrencyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class SupplierReturnController extends Controller
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
        $query = SupplierReturn::with(['supplier', 'currency', 'createdBy']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('return_number', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%")
                  ->orWhere('reason', 'like', "%{$search}%")
                  ->orWhereHas('supplier', function($supplierQuery) use ($search) {
                      $supplierQuery->where('company_name', 'like', "%{$search}%")
                                   ->orWhere('contact_person', 'like', "%{$search}%")
                                   ->orWhere('supplier_code', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by supplier
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        // Filter by reason
        if ($request->filled('reason')) {
            $query->where('reason', $request->reason);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('return_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('return_date', '<=', $request->date_to);
        }

        // Filter by amount range
        if ($request->filled('amount_from')) {
            $query->where('total_amount', '>=', $request->amount_from);
        }
        if ($request->filled('amount_to')) {
            $query->where('total_amount', '<=', $request->amount_to);
        }

        $supplierReturns = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get filter options
        $suppliers = Supplier::where('is_active', true)->orderBy('company_name')->get();
        $statuses = ['pending', 'approved', 'completed', 'rejected'];
        $reasons = [
            'defective' => 'Defective Items',
            'wrong_item' => 'Wrong Item',
            'overstock' => 'Overstock',
            'damaged' => 'Damaged in Transit',
            'quality_issue' => 'Quality Issue',
            'other' => 'Other'
        ];

        // Calculate totals
        $totalReturns = SupplierReturn::count();
        $totalAmount = SupplierReturn::sum('total_amount');
        $pendingAmount = SupplierReturn::where('status', 'pending')->sum('total_amount');
        $completedAmount = SupplierReturn::where('status', 'completed')->sum('total_amount');
        $pendingCount = SupplierReturn::where('status', 'pending')->count();

        // Calculate total value in LKR (using all returns, not just paginated ones)
        $allReturns = SupplierReturn::with('currency')->get();
        $totalValueInLKR = 0;
        foreach ($allReturns as $return) {
            $convertedAmount = $this->currencyService->convertAmount(
                $return->total_amount,
                $return->currency->code,
                'LKR'
            );
            $totalValueInLKR += $convertedAmount;
        }

        return view('supplier-returns.index', compact(
            'supplierReturns', 
            'suppliers', 
            'statuses', 
            'reasons',
            'totalReturns',
            'totalAmount',
            'pendingAmount',
            'completedAmount',
            'pendingCount',
            'totalValueInLKR'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::where('is_active', true)->orderBy('company_name')->get();
        $items = Item::where('is_active', true)->orderBy('name')->get();
        $currencies = Currency::where('is_active', true)->get();

        return view('supplier-returns.create', compact('suppliers', 'items', 'currencies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'return_date' => 'required|date',
            'currency_id' => 'required|exists:currencies,id',
            'exchange_rate' => 'nullable|numeric|min:0',
            'reason' => 'required|in:defective,wrong_item,overstock,damaged,quality_issue,other',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        DB::beginTransaction();

        try {
            // Generate return number
            $returnNumber = 'SR-' . date('Y') . '-' . str_pad(SupplierReturn::whereYear('created_at', date('Y'))->count() + 1, 4, '0', STR_PAD_LEFT);

            // Get exchange rate if not provided
            $exchangeRate = $request->exchange_rate;
            if (!$exchangeRate) {
                $currency = Currency::findOrFail($request->currency_id);
                $exchangeRate = $currency->exchange_rate;
            }

            // Create supplier return
            $supplierReturn = SupplierReturn::create([
                'supplier_id' => $request->supplier_id,
                'return_number' => $returnNumber,
                'return_date' => $request->return_date,
                'currency_id' => $request->currency_id,
                'exchange_rate' => $exchangeRate,
                'reason' => $request->reason,
                'status' => 'pending',
                'notes' => $request->notes,
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
                    'transaction_type' => 'App\Models\SupplierReturn',
                    'transaction_id' => $supplierReturn->id,
                    'item_id' => $item->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $itemTotalPrice,
                    'discount_percentage' => $itemData['discount_percentage'] ?? 0,
                    'discount_amount' => $itemDiscountAmount,
                    'tax_percentage' => $itemData['tax_percentage'] ?? 0,
                    'tax_amount' => $itemTaxAmount,
                ]);

                // Update item stock (add back to stock for returns)
                $item->increment('current_stock', $quantity);

                $subtotal += $itemSubtotal;
                $discountAmount += $itemDiscountAmount;
                $taxAmount += $itemTaxAmount;
            }

            // Update supplier return totals
            $supplierReturn->update([
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'tax_amount' => $taxAmount,
                'total_amount' => $subtotal - $discountAmount + $taxAmount,
            ]);

            DB::commit();

            return redirect()->route('supplier-returns.index')
                           ->with('success', 'Supplier return created successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('supplier-returns.index')
                           ->with('error', 'Error creating supplier return: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SupplierReturn $supplierReturn)
    {
        $supplierReturn->load(['supplier', 'currency', 'transactionItems.item', 'createdBy', 'processedBy', 'approvedBy']);

        return view('supplier-returns.show', compact('supplierReturn'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SupplierReturn $supplierReturn)
    {
        if ($supplierReturn->status !== 'pending') {
            return redirect()->route('supplier-returns.show', $supplierReturn)
                           ->with('error', 'Only pending returns can be edited.');
        }

        $suppliers = Supplier::where('is_active', true)->orderBy('company_name')->get();
        $items = Item::where('is_active', true)->orderBy('name')->get();
        $currencies = Currency::where('is_active', true)->get();

        $supplierReturn->load('transactionItems.item');

        return view('supplier-returns.edit', compact('supplierReturn', 'suppliers', 'items', 'currencies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SupplierReturn $supplierReturn)
    {
        if ($supplierReturn->status !== 'pending') {
            return redirect()->route('supplier-returns.show', $supplierReturn)
                           ->with('error', 'Only pending returns can be edited.');
        }

        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'return_date' => 'required|date',
            'currency_id' => 'required|exists:currencies,id',
            'reason' => 'required|in:defective,wrong_item,overstock,damaged,quality_issue,other',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        DB::beginTransaction();

        try {
            // Restore stock from old items (subtract back)
            foreach ($supplierReturn->transactionItems as $oldItem) {
                $oldItem->item->decrement('current_stock', $oldItem->quantity);
            }

            // Delete old transaction items
            $supplierReturn->transactionItems()->delete();

            // Update supplier return basic info
            $supplierReturn->update([
                'supplier_id' => $request->supplier_id,
                'return_date' => $request->return_date,
                'currency_id' => $request->currency_id,
                'reason' => $request->reason,
                'notes' => $request->notes,
                'updated_by' => Auth::id()
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
                    'transaction_type' => 'App\Models\SupplierReturn',
                    'transaction_id' => $supplierReturn->id,
                    'item_id' => $item->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $itemTotalPrice,
                    'discount_percentage' => $itemData['discount_percentage'] ?? 0,
                    'discount_amount' => $itemDiscountAmount,
                    'tax_percentage' => $itemData['tax_percentage'] ?? 0,
                    'tax_amount' => $itemTaxAmount,
                ]);

                // Update item stock (add back to stock for returns)
                $item->increment('current_stock', $quantity);

                $subtotal += $itemSubtotal;
                $discountAmount += $itemDiscountAmount;
                $taxAmount += $itemTaxAmount;
            }

            // Update supplier return totals
            $supplierReturn->update([
                'total_amount' => $subtotal - $discountAmount + $taxAmount,
                'discount_amount' => $discountAmount,
                'tax_amount' => $taxAmount,
            ]);

            DB::commit();

            return redirect()->route('supplier-returns.show', $supplierReturn)
                           ->with('success', 'Supplier return updated successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('supplier-returns.index')
                           ->with('error', 'Error updating supplier return: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SupplierReturn $supplierReturn)
    {
        if (!in_array($supplierReturn->status, ['pending', 'rejected'])) {
            return redirect()->route('supplier-returns.index')
                           ->with('error', 'Only pending or rejected returns can be deleted.');
        }

        DB::beginTransaction();

        try {
            // Restore stock (subtract back since we're deleting the return)
            foreach ($supplierReturn->transactionItems as $item) {
                $item->item->decrement('current_stock', $item->quantity);
            }

            // Delete transaction items
            $supplierReturn->transactionItems()->delete();

            // Delete supplier return
            $supplierReturn->delete();

            DB::commit();

            return redirect()->route('supplier-returns.index')
                           ->with('success', 'Supplier return deleted successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('supplier-returns.index')
                           ->with('error', 'Error deleting supplier return: ' . $e->getMessage());
        }
    }

    /**
     * Update supplier return status
     */
    public function updateStatus(Request $request, SupplierReturn $supplierReturn)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,completed,rejected'
        ]);

        $status = $request->status;
        
        // Update timestamps based on status
        $updateData = ['status' => $status];
        
        if ($status === 'approved' && !$supplierReturn->approved_at) {
            $updateData['approved_by'] = Auth::id();
            $updateData['approved_at'] = now();
        }
        
        if ($status === 'completed') {
            $updateData['processed_by'] = Auth::id();
        }

        $supplierReturn->update($updateData);

        return redirect()->route('supplier-returns.show', $supplierReturn)
                       ->with('success', 'Supplier return status updated successfully!');
    }

    /**
     * Export supplier return as PDF
     */
    public function exportPdf(SupplierReturn $supplierReturn)
    {
        $supplierReturn->load(['supplier', 'currency', 'transactionItems.item', 'createdBy']);
        
        $pdf = Pdf::loadView('supplier-returns.pdf', compact('supplierReturn'));
        $filename = 'SupplierReturn-' . $supplierReturn->return_number . '-' . date('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Bulk status update
     */
    public function bulkStatusUpdate(Request $request)
    {
        $request->validate([
            'supplier_return_ids' => 'required|array|min:1',
            'supplier_return_ids.*' => 'exists:supplier_returns,id',
            'status' => 'required|in:pending,approved,completed,rejected'
        ]);
        
        $count = SupplierReturn::whereIn('id', $request->supplier_return_ids)
            ->update(['status' => $request->status]);
        
        return redirect()->route('supplier-returns.index')
            ->with('success', "Successfully updated {$count} supplier return(s) status to " . ucfirst($request->status));
    }

    /**
     * Get exchange rates for currency conversion
     */
    public function getExchangeRates()
    {
        $currencies = Currency::where('is_active', true)->get();
        
        return response()->json([
            'currencies' => $currencies->map(function($currency) {
                return [
                    'id' => $currency->id,
                    'code' => $currency->code,
                    'name' => $currency->name,
                    'exchange_rate' => $currency->exchange_rate
                ];
            })
        ]);
    }
}
