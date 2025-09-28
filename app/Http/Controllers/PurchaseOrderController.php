<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\Currency;
use App\Models\Item;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = PurchaseOrder::query()->with('supplier');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('po_number', 'like', "%{$search}%")
                  ->orWhereHas('supplier', function ($qs) use ($search) {
                      $qs->where('company_name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from')) {
            $query->whereDate('order_date', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('order_date', '<=', $request->to);
        }

        $purchaseOrders = $query->with(['supplier', 'currency', 'transactionItems'])->orderByDesc('created_at')->paginate(20);

        $statuses = ['draft','pending','approved','partially_received','completed','cancelled'];

        return view('purchase-orders.index', compact('purchaseOrders', 'statuses'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('company_name')->get();
        $currencies = Currency::where('is_active', true)->orderBy('code')->get();
        $items = Item::where('is_active', true)->orderBy('name')->get();
        return view('purchase-orders.create', compact('suppliers', 'currencies', 'items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'expected_delivery_date' => 'nullable|date|after_or_equal:order_date',
            'currency_id' => 'required|exists:currencies,id',
            'exchange_rate' => 'required|numeric|min:0.0001',
            'notes' => 'nullable|string',
            'terms_conditions' => 'nullable|string',
            'items' => 'nullable|array',
            'items.*.item_id' => 'required_with:items|exists:items,id',
            'items.*.quantity' => 'required_with:items|integer|min:1',
            'items.*.unit_price' => 'required_with:items|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $poNumber = 'PO-' . strtoupper(Str::random(8));

            // Create Purchase Order
            $po = PurchaseOrder::create([
                'po_number' => $poNumber,
                'supplier_id' => $request->supplier_id,
                'user_id' => $request->user()->id,
                'order_date' => $request->order_date,
                'expected_delivery_date' => $request->expected_delivery_date,
                'status' => 'draft',
                'subtotal' => 0,
                'tax_amount' => 0,
                'discount_amount' => 0,
                'total_amount' => 0,
                'currency_id' => $request->currency_id,
                'exchange_rate' => $request->exchange_rate,
                'notes' => $request->notes,
                'terms_conditions' => $request->terms_conditions,
            ]);

            $subtotal = 0;
            $taxAmount = 0;
            $discountAmount = 0;

            // Create transaction items if provided
            if ($request->has('items') && is_array($request->items)) {
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
                        'transaction_type' => 'App\Models\PurchaseOrder',
                        'transaction_id' => $po->id,
                        'item_id' => $item->id,
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'total_price' => $itemTotalPrice,
                        'discount_percentage' => $itemData['discount_percentage'] ?? 0,
                        'discount_amount' => $itemDiscountAmount,
                        'tax_percentage' => $itemData['tax_percentage'] ?? 0,
                        'tax_amount' => $itemTaxAmount,
                    ]);

                    $subtotal += $itemSubtotal;
                    $discountAmount += $itemDiscountAmount;
                    $taxAmount += $itemTaxAmount;
                }
            }

            // Update Purchase Order totals
            $po->update([
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'tax_amount' => $taxAmount,
                'total_amount' => $subtotal - $discountAmount + $taxAmount,
            ]);

            DB::commit();

            return redirect()->route('purchase-orders.show', $po)->with('success', 'Purchase order created successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', 'Error creating purchase order: ' . $e->getMessage());
        }
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['supplier', 'currency', 'user', 'transactionItems.item']);
        return view('purchase-orders.show', compact('purchaseOrder'));
    }

    public function edit(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status !== 'draft') {
            return redirect()->route('purchase-orders.show', $purchaseOrder)
                ->with('error', 'Only draft purchase orders can be edited.');
        }

        $suppliers = Supplier::orderBy('company_name')->get();
        $currencies = Currency::where('is_active', true)->orderBy('code')->get();
        $items = Item::where('is_active', true)->orderBy('name')->get();
        $purchaseOrder->load(['transactionItems.item']);
        return view('purchase-orders.edit', compact('purchaseOrder', 'suppliers', 'currencies', 'items'));
    }

    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status !== 'draft') {
            return redirect()->route('purchase-orders.show', $purchaseOrder)
                ->with('error', 'Only draft purchase orders can be edited.');
        }

        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'expected_delivery_date' => 'nullable|date|after_or_equal:order_date',
            'currency_id' => 'required|exists:currencies,id',
            'exchange_rate' => 'required|numeric|min:0.0001',
            'status' => 'required|in:draft,pending,approved,partially_received,completed,cancelled',
            'items' => 'nullable|array',
            'items.*.item_id' => 'required_with:items|exists:items,id',
            'items.*.quantity' => 'required_with:items|integer|min:1',
            'items.*.unit_price' => 'required_with:items|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            // Update Purchase Order basic info
            $purchaseOrder->update([
                'supplier_id' => $request->supplier_id,
                'order_date' => $request->order_date,
                'expected_delivery_date' => $request->expected_delivery_date,
                'currency_id' => $request->currency_id,
                'exchange_rate' => $request->exchange_rate,
                'status' => $request->status,
                'notes' => $request->notes,
                'terms_conditions' => $request->terms_conditions,
            ]);

            $subtotal = 0;
            $taxAmount = 0;
            $discountAmount = 0;

            // Remove existing transaction items and create new ones if provided
            $purchaseOrder->transactionItems()->delete();

            if ($request->has('items') && is_array($request->items)) {
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
                        'transaction_type' => 'App\Models\PurchaseOrder',
                        'transaction_id' => $purchaseOrder->id,
                        'item_id' => $item->id,
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'total_price' => $itemTotalPrice,
                        'discount_percentage' => $itemData['discount_percentage'] ?? 0,
                        'discount_amount' => $itemDiscountAmount,
                        'tax_percentage' => $itemData['tax_percentage'] ?? 0,
                        'tax_amount' => $itemTaxAmount,
                    ]);

                    $subtotal += $itemSubtotal;
                    $discountAmount += $itemDiscountAmount;
                    $taxAmount += $itemTaxAmount;
                }
            }

            // Update Purchase Order totals
            $purchaseOrder->update([
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'tax_amount' => $taxAmount,
                'total_amount' => $subtotal - $discountAmount + $taxAmount,
            ]);

            DB::commit();

            return redirect()->route('purchase-orders.show', $purchaseOrder)->with('success', 'Purchase order updated successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', 'Error updating purchase order: ' . $e->getMessage());
        }
    }

    public function destroy(PurchaseOrder $purchaseOrder)
    {
        if (!in_array($purchaseOrder->status, ['draft', 'pending', 'cancelled'])) {
            return redirect()->route('purchase-orders.index')
                ->with('error', 'Only draft, pending, or cancelled purchase orders can be deleted.');
        }

        DB::beginTransaction();

        try {
            // Delete transaction items
            $purchaseOrder->transactionItems()->delete();
            
            // Delete purchase order
            $purchaseOrder->delete();
            
            DB::commit();

            return redirect()->route('purchase-orders.index')->with('success', 'Purchase order deleted successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('purchase-orders.index')
                ->with('error', 'Error deleting purchase order: ' . $e->getMessage());
        }
    }
}
