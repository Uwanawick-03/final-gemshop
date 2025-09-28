<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;
use App\Models\Customer;
use App\Models\SalesAssistant;
use App\Models\Item;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SalesOrder::with(['customer', 'salesAssistant']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
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

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('order_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('order_date', '<=', $request->date_to);
        }

        $salesOrders = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get unique statuses for filter dropdown
        $statuses = SalesOrder::distinct()->pluck('status')->filter();

        // Get status counts for dashboard cards
        $statusCounts = SalesOrder::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Get total orders count
        $totalOrders = SalesOrder::count();

        return view('sales-orders.index', compact('salesOrders', 'statuses', 'statusCounts', 'totalOrders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::where('is_active', true)->orderBy('first_name')->get();
        $salesAssistants = SalesAssistant::where('is_active', true)->orderBy('first_name')->get();
        $items = Item::where('is_active', true)->orderBy('name')->get();

        return view('sales-orders.create', compact('customers', 'salesAssistants', 'items'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'sales_assistant_id' => 'required|exists:sales_assistants,id',
            'order_date' => 'required|date',
            'delivery_date' => 'nullable|date|after_or_equal:order_date',
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        DB::beginTransaction();

        try {
            // Generate order number
            $orderNumber = 'SO-' . date('Y') . '-' . str_pad(SalesOrder::count() + 1, 4, '0', STR_PAD_LEFT);

            // Create sales order
            $salesOrder = SalesOrder::create([
                'customer_id' => $request->customer_id,
                'sales_assistant_id' => $request->sales_assistant_id,
                'order_number' => $orderNumber,
                'order_date' => $request->order_date,
                'delivery_date' => $request->delivery_date,
                'status' => $request->status,
                'notes' => $request->notes,
                'total_amount' => 0 // Will be updated after items are added
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

                TransactionItem::create([
                    'transaction_id' => $salesOrder->id,
                    'transaction_type' => 'App\\Models\\SalesOrder',
                    'item_id' => $item->id,
                    'item_code' => $itemData['item_code'] ?? $item->item_code,
                    'item_name' => $itemData['item_name'] ?? $item->name,
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

            // Update sales order totals
            $salesOrder->update([
                'total_amount' => $subtotal - $discountAmount + $taxAmount,
            ]);

            DB::commit();

            return redirect()->route('sales-orders.show', $salesOrder)
                           ->with('success', 'Sales order created successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('sales-orders.index')
                           ->with('error', 'Error creating sales order: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SalesOrder $salesOrder)
    {
        $salesOrder->load(['customer', 'salesAssistant', 'transactionItems.item']);
        return view('sales-orders.show', compact('salesOrder'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SalesOrder $salesOrder)
    {
        $customers = Customer::where('is_active', true)->orderBy('first_name')->get();
        $salesAssistants = SalesAssistant::where('is_active', true)->orderBy('first_name')->get();
        $items = Item::where('is_active', true)->orderBy('name')->get();

        return view('sales-orders.edit', compact('salesOrder', 'customers', 'salesAssistants', 'items'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SalesOrder $salesOrder)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'sales_assistant_id' => 'required|exists:sales_assistants,id',
            'order_date' => 'required|date',
            'delivery_date' => 'nullable|date|after_or_equal:order_date',
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        DB::beginTransaction();

        try {
            // Update sales order
            $salesOrder->update([
                'customer_id' => $request->customer_id,
                'sales_assistant_id' => $request->sales_assistant_id,
                'order_date' => $request->order_date,
                'delivery_date' => $request->delivery_date,
                'status' => $request->status,
                'notes' => $request->notes,
            ]);

            // Delete existing transaction items
            $salesOrder->transactionItems()->delete();

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

                TransactionItem::create([
                    'transaction_id' => $salesOrder->id,
                    'transaction_type' => 'App\\Models\\SalesOrder',
                    'item_id' => $item->id,
                    'item_code' => $itemData['item_code'] ?? $item->item_code,
                    'item_name' => $itemData['item_name'] ?? $item->name,
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

            // Update sales order totals
            $salesOrder->update([
                'total_amount' => $subtotal - $discountAmount + $taxAmount,
            ]);

            DB::commit();

            return redirect()->route('sales-orders.show', $salesOrder)
                           ->with('success', 'Sales order updated successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('sales-orders.index')
                           ->with('error', 'Error updating sales order: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalesOrder $salesOrder)
    {
        if (in_array($salesOrder->status, ['shipped', 'delivered'])) {
            return redirect()->route('sales-orders.index')
                           ->with('error', 'Cannot delete sales order with status: ' . ucfirst($salesOrder->status));
        }

        DB::beginTransaction();

        try {
            // Delete transaction items
            $salesOrder->transactionItems()->delete();
            
            // Delete sales order
            $salesOrder->delete();

            DB::commit();

            return redirect()->route('sales-orders.index')
                           ->with('success', 'Sales order deleted successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('sales-orders.index')
                           ->with('error', 'Error deleting sales order: ' . $e->getMessage());
        }
    }

    /**
     * Update sales order status
     */
    public function updateStatus(Request $request, SalesOrder $salesOrder)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled'
        ]);

        $salesOrder->update(['status' => $request->status]);

        return redirect()->route('sales-orders.show', $salesOrder)
                       ->with('success', 'Sales order status updated successfully!');
    }
}