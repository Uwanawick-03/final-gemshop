<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Grn;
use App\Models\Supplier;
use App\Models\PurchaseOrder;
use App\Models\Currency;
use App\Models\Item;
use App\Models\TransactionItem;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class GrnController extends Controller
{
    public function index(Request $request)
    {
        $query = Grn::with(['supplier', 'purchaseOrder', 'user', 'currency']);
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('grn_number', 'like', "%{$search}%")
                  ->orWhereHas('supplier', function($sq) use ($search) {
                      $sq->where('company_name', 'like', "%{$search}%");
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
        
        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('grn_date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->where('grn_date', '<=', $request->date_to);
        }
        
        $grns = $query->latest('grn_date')->paginate(20);
        
        // Get filter options
        $suppliers = Supplier::where('is_active', true)->get();
        $statuses = ['draft', 'received', 'verified', 'completed', 'cancelled'];
        
        return view('grns.index', compact('grns', 'suppliers', 'statuses'));
    }

    public function create(Request $request)
    {
        $suppliers = Supplier::where('is_active', true)->get();
        $currencies = Currency::where('is_active', true)->get();
        $purchaseOrders = PurchaseOrder::where('status', 'approved')->get();
        $items = Item::where('is_active', true)->get();
        
        // If coming from a purchase order
        $purchaseOrder = null;
        if ($request->has('purchase_order_id')) {
            $purchaseOrder = PurchaseOrder::with(['supplier', 'transactionItems.item'])
                ->findOrFail($request->purchase_order_id);
        }
        
        return view('grns.create', compact('suppliers', 'currencies', 'purchaseOrders', 'items', 'purchaseOrder'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'grn_date' => 'required|date',
            'received_date' => 'required|date',
            'currency_id' => 'required|exists:currencies,id',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);
        
        DB::beginTransaction();
        
        try {
            // Generate GRN number
            $grnNumber = 'GRN-' . date('Y') . '-' . str_pad(Grn::whereYear('created_at', date('Y'))->count() + 1, 4, '0', STR_PAD_LEFT);
            
            // Create GRN
            $grn = Grn::create([
                'grn_number' => $grnNumber,
                'purchase_order_id' => $request->purchase_order_id,
                'supplier_id' => $request->supplier_id,
                'user_id' => auth()->id(),
                'grn_date' => $request->grn_date,
                'received_date' => $request->received_date,
                'status' => 'received',
                'currency_id' => $request->currency_id,
                'exchange_rate' => $request->exchange_rate ?? 1.0000,
                'notes' => $request->notes,
                'delivery_notes' => $request->delivery_notes,
                'delivery_person' => $request->delivery_person,
                'vehicle_number' => $request->vehicle_number,
            ]);
            
            $subtotal = 0;
            $taxAmount = 0;
            $discountAmount = 0;
            
            // Create transaction items and update stock
            foreach ($request->items as $itemData) {
                $item = Item::findOrFail($itemData['item_id']);
                $quantity = $itemData['quantity'];
                $unitPrice = $itemData['unit_price'];
                $totalPrice = $quantity * $unitPrice;
                
                // Create transaction item
                TransactionItem::create([
                    'transaction_type' => 'App\Models\Grn',
                    'transaction_id' => $grn->id,
                    'item_id' => $item->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                    'discount_percentage' => $itemData['discount_percentage'] ?? 0,
                    'discount_amount' => $itemData['discount_amount'] ?? 0,
                    'tax_percentage' => $itemData['tax_percentage'] ?? 0,
                    'tax_amount' => $itemData['tax_amount'] ?? 0,
                ]);
                
                // Update item stock
                $item->increment('current_stock', $quantity);
                
                $subtotal += $totalPrice;
                $discountAmount += $itemData['discount_amount'] ?? 0;
                $taxAmount += $itemData['tax_amount'] ?? 0;
            }
            
            // Update GRN totals
            $grn->update([
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'tax_amount' => $taxAmount,
                'total_amount' => $subtotal - $discountAmount + $taxAmount,
            ]);
            
            DB::commit();
            
            return redirect()->route('grns.show', $grn)
                ->with('success', 'GRN created successfully!');
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', 'Error creating GRN: ' . $e->getMessage());
        }
    }

    public function show(Grn $grn)
    {
        $grn->load(['supplier', 'purchaseOrder', 'user', 'currency', 'transactionItems.item']);
        
        return view('grns.show', compact('grn'));
    }

    public function edit(Grn $grn)
    {
        if ($grn->status !== 'draft') {
            return redirect()->route('grns.show', $grn)
                ->with('error', 'Only draft GRNs can be edited.');
        }
        
        $suppliers = Supplier::where('is_active', true)->get();
        $currencies = Currency::where('is_active', true)->get();
        $purchaseOrders = PurchaseOrder::where('status', 'approved')->get();
        $items = Item::where('is_active', true)->get();
        
        $grn->load(['transactionItems.item']);
        
        return view('grns.edit', compact('grn', 'suppliers', 'currencies', 'purchaseOrders', 'items'));
    }

    public function update(Request $request, Grn $grn)
    {
        if ($grn->status !== 'draft') {
            return redirect()->route('grns.show', $grn)
                ->with('error', 'Only draft GRNs can be edited.');
        }
        
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'grn_date' => 'required|date',
            'received_date' => 'required|date',
            'currency_id' => 'required|exists:currencies,id',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);
        
        DB::beginTransaction();
        
        try {
            // Update GRN basic info
            $grn->update([
                'supplier_id' => $request->supplier_id,
                'grn_date' => $request->grn_date,
                'received_date' => $request->received_date,
                'currency_id' => $request->currency_id,
                'exchange_rate' => $request->exchange_rate ?? 1.0000,
                'notes' => $request->notes,
                'delivery_notes' => $request->delivery_notes,
                'delivery_person' => $request->delivery_person,
                'vehicle_number' => $request->vehicle_number,
            ]);
            
            // Remove existing transaction items and revert stock
            foreach ($grn->transactionItems as $transactionItem) {
                $item = $transactionItem->item;
                $item->decrement('current_stock', $transactionItem->quantity);
                $transactionItem->delete();
            }
            
            $subtotal = 0;
            $taxAmount = 0;
            $discountAmount = 0;
            
            // Create new transaction items and update stock
            foreach ($request->items as $itemData) {
                $item = Item::findOrFail($itemData['item_id']);
                $quantity = $itemData['quantity'];
                $unitPrice = $itemData['unit_price'];
                $totalPrice = $quantity * $unitPrice;
                
                // Create transaction item
                TransactionItem::create([
                    'transaction_type' => 'App\Models\Grn',
                    'transaction_id' => $grn->id,
                    'item_id' => $item->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                    'discount_percentage' => $itemData['discount_percentage'] ?? 0,
                    'discount_amount' => $itemData['discount_amount'] ?? 0,
                    'tax_percentage' => $itemData['tax_percentage'] ?? 0,
                    'tax_amount' => $itemData['tax_amount'] ?? 0,
                ]);
                
                // Update item stock
                $item->increment('current_stock', $quantity);
                
                $subtotal += $totalPrice;
                $discountAmount += $itemData['discount_amount'] ?? 0;
                $taxAmount += $itemData['tax_amount'] ?? 0;
            }
            
            // Update GRN totals
            $grn->update([
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'tax_amount' => $taxAmount,
                'total_amount' => $subtotal - $discountAmount + $taxAmount,
            ]);
            
            DB::commit();
            
            return redirect()->route('grns.show', $grn)
                ->with('success', 'GRN updated successfully!');
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', 'Error updating GRN: ' . $e->getMessage());
        }
    }

    public function destroy(Grn $grn)
    {
        if (!in_array($grn->status, ['draft', 'received', 'cancelled'])) {
            return redirect()->route('grns.index')
                ->with('error', 'Only draft, received, or cancelled GRNs can be deleted.');
        }
        
        DB::beginTransaction();
        
        try {
            // Revert stock for all items
            foreach ($grn->transactionItems as $transactionItem) {
                $item = $transactionItem->item;
                $item->decrement('current_stock', $transactionItem->quantity);
            }
            
            // Delete transaction items
            $grn->transactionItems()->delete();
            
            // Delete GRN
            $grn->delete();
            
            DB::commit();
            
            return redirect()->route('grns.index')
                ->with('success', 'GRN deleted successfully!');
                
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('grns.index')
                ->with('error', 'Error deleting GRN: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, Grn $grn)
    {
        $request->validate([
            'status' => 'required|in:draft,received,verified,completed,cancelled'
        ]);
        
        $grn->update(['status' => $request->status]);
        
        return redirect()->route('grns.show', $grn)
            ->with('success', 'GRN status updated successfully!');
    }

    public function exportPdf(Grn $grn)
    {
        $grn->load(['supplier', 'purchaseOrder', 'user', 'currency', 'transactionItems.item']);
        
        $pdf = \PDF::loadView('grns.pdf', compact('grn'));
        $filename = 'GRN-' . $grn->grn_number . '-' . date('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }

    public function bulkStatusUpdate(Request $request)
    {
        $request->validate([
            'grn_ids' => 'required|array|min:1',
            'grn_ids.*' => 'exists:grns,id',
            'status' => 'required|in:draft,received,verified,completed,cancelled'
        ]);
        
        $count = Grn::whereIn('id', $request->grn_ids)
            ->update(['status' => $request->status]);
        
        return redirect()->route('grns.index')
            ->with('success', "Successfully updated {$count} GRN(s) status to " . ucfirst($request->status));
    }

    public function getGrnByPo(Request $request)
    {
        $request->validate([
            'purchase_order_id' => 'required|exists:purchase_orders,id'
        ]);
        
        $purchaseOrder = PurchaseOrder::with(['supplier', 'transactionItems.item'])
            ->findOrFail($request->purchase_order_id);
        
        return response()->json([
            'supplier' => $purchaseOrder->supplier,
            'items' => $purchaseOrder->transactionItems->map(function($item) {
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
}
