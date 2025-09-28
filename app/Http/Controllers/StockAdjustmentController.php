<?php

namespace App\Http\Controllers;

use App\Models\StockAdjustment;
use App\Models\StockAdjustmentItem;
use App\Models\Item;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StockAdjustmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = StockAdjustment::with(['createdBy', 'approvedBy', 'adjustmentItems']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('adjustment_number', 'like', "%{$search}%")
                  ->orWhere('reason', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%")
                  ->orWhereHas('createdBy', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by reason
        if ($request->filled('reason')) {
            $query->where('reason', $request->reason);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('adjustment_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('adjustment_date', '<=', $request->date_to);
        }

        $stockAdjustments = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get filter options
        $statuses = StockAdjustment::distinct()->pluck('status')->filter();
        $types = ['increase', 'decrease'];
        $reasons = StockAdjustment::distinct()->pluck('reason')->filter();

        // Get status counts for dashboard
        $statusCounts = StockAdjustment::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $totalAdjustments = StockAdjustment::count();

        return view('stock-adjustments.index', compact(
            'stockAdjustments', 
            'statuses', 
            'types', 
            'reasons', 
            'statusCounts', 
            'totalAdjustments'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $items = Item::where('is_active', true)->orderBy('name')->get();
        $reasons = [
            'damage' => 'Damage/Loss',
            'theft' => 'Theft',
            'found' => 'Found Items',
            'return' => 'Customer Return',
            'supplier_return' => 'Supplier Return',
            'cycle_count' => 'Cycle Count',
            'reconciliation' => 'Reconciliation',
            'other' => 'Other'
        ];

        return view('stock-adjustments.create', compact('items', 'reasons'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'adjustment_date' => 'required|date',
            'type' => 'required|in:increase,decrease',
            'reason' => 'required|string',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.adjusted_quantity' => 'required|numeric|min:0',
            'items.*.reason' => 'nullable|string',
            'items.*.notes' => 'nullable|string'
        ]);

        DB::beginTransaction();

        try {
            // Generate adjustment number
            $adjustmentNumber = 'SA-' . date('Y') . '-' . str_pad(StockAdjustment::count() + 1, 4, '0', STR_PAD_LEFT);

            // Create stock adjustment
            $stockAdjustment = StockAdjustment::create([
                'adjustment_number' => $adjustmentNumber,
                'adjustment_date' => $request->adjustment_date,
                'type' => $request->type,
                'reason' => $request->reason,
                'status' => 'pending',
                'notes' => $request->notes,
                'total_items' => count($request->items),
                'created_by' => Auth::id()
            ]);

            // Create adjustment items
            foreach ($request->items as $itemData) {
                $item = Item::findOrFail($itemData['item_id']);
                $currentQuantity = $item->current_stock;
                $adjustedQuantity = $itemData['adjusted_quantity'];
                $differenceQuantity = $adjustedQuantity - $currentQuantity;

                StockAdjustmentItem::create([
                    'stock_adjustment_id' => $stockAdjustment->id,
                    'item_id' => $item->id,
                    'item_code' => $item->item_code,
                    'item_name' => $item->name,
                    'current_quantity' => $currentQuantity,
                    'adjusted_quantity' => $adjustedQuantity,
                    'difference_quantity' => $differenceQuantity,
                    'unit_cost' => $item->cost_price,
                    'reason' => $itemData['reason'] ?? $request->reason,
                    'notes' => $itemData['notes']
                ]);
            }

            DB::commit();

            return redirect()->route('stock-adjustments.show', $stockAdjustment)
                           ->with('success', 'Stock adjustment created successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('stock-adjustments.index')
                           ->with('error', 'Error creating stock adjustment: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(StockAdjustment $stockAdjustment)
    {
        $stockAdjustment->load(['createdBy', 'approvedBy', 'adjustmentItems.item']);
        return view('stock-adjustments.show', compact('stockAdjustment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StockAdjustment $stockAdjustment)
    {
        if ($stockAdjustment->status !== 'pending') {
            return redirect()->route('stock-adjustments.show', $stockAdjustment)
                           ->with('error', 'Cannot edit approved or completed adjustments.');
        }

        $items = Item::where('is_active', true)->orderBy('name')->get();
        $reasons = [
            'damage' => 'Damage/Loss',
            'theft' => 'Theft',
            'found' => 'Found Items',
            'return' => 'Customer Return',
            'supplier_return' => 'Supplier Return',
            'cycle_count' => 'Cycle Count',
            'reconciliation' => 'Reconciliation',
            'other' => 'Other'
        ];

        return view('stock-adjustments.edit', compact('stockAdjustment', 'items', 'reasons'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StockAdjustment $stockAdjustment)
    {
        if ($stockAdjustment->status !== 'pending') {
            return redirect()->route('stock-adjustments.show', $stockAdjustment)
                           ->with('error', 'Cannot update approved or completed adjustments.');
        }

        $request->validate([
            'adjustment_date' => 'required|date',
            'type' => 'required|in:increase,decrease',
            'reason' => 'required|string',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.adjusted_quantity' => 'required|numeric|min:0',
            'items.*.reason' => 'nullable|string',
            'items.*.notes' => 'nullable|string'
        ]);

        DB::beginTransaction();

        try {
            // Update stock adjustment
            $stockAdjustment->update([
                'adjustment_date' => $request->adjustment_date,
                'type' => $request->type,
                'reason' => $request->reason,
                'notes' => $request->notes,
                'total_items' => count($request->items),
                'updated_by' => Auth::id()
            ]);

            // Delete existing adjustment items
            $stockAdjustment->adjustmentItems()->delete();

            // Create new adjustment items
            foreach ($request->items as $itemData) {
                $item = Item::findOrFail($itemData['item_id']);
                $currentQuantity = $item->current_stock;
                $adjustedQuantity = $itemData['adjusted_quantity'];
                $differenceQuantity = $adjustedQuantity - $currentQuantity;

                StockAdjustmentItem::create([
                    'stock_adjustment_id' => $stockAdjustment->id,
                    'item_id' => $item->id,
                    'item_code' => $item->item_code,
                    'item_name' => $item->name,
                    'current_quantity' => $currentQuantity,
                    'adjusted_quantity' => $adjustedQuantity,
                    'difference_quantity' => $differenceQuantity,
                    'unit_cost' => $item->cost_price,
                    'reason' => $itemData['reason'] ?? $request->reason,
                    'notes' => $itemData['notes']
                ]);
            }

            DB::commit();

            return redirect()->route('stock-adjustments.show', $stockAdjustment)
                           ->with('success', 'Stock adjustment updated successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('stock-adjustments.index')
                           ->with('error', 'Error updating stock adjustment: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StockAdjustment $stockAdjustment)
    {
        if ($stockAdjustment->status !== 'pending') {
            return redirect()->route('stock-adjustments.index')
                           ->with('error', 'Cannot delete approved or completed adjustments.');
        }

        DB::beginTransaction();

        try {
            $stockAdjustment->adjustmentItems()->delete();
            $stockAdjustment->delete();

            DB::commit();

            return redirect()->route('stock-adjustments.index')
                           ->with('success', 'Stock adjustment deleted successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('stock-adjustments.index')
                           ->with('error', 'Error deleting stock adjustment: ' . $e->getMessage());
        }
    }

    /**
     * Approve stock adjustment
     */
    public function approve(StockAdjustment $stockAdjustment)
    {
        if ($stockAdjustment->status !== 'pending') {
            return redirect()->route('stock-adjustments.show', $stockAdjustment)
                           ->with('error', 'Only pending adjustments can be approved.');
        }

        DB::beginTransaction();

        try {
            // Update adjustment status
            $stockAdjustment->update([
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now()
            ]);

            // Apply stock adjustments
            foreach ($stockAdjustment->adjustmentItems as $adjustmentItem) {
                $item = Item::findOrFail($adjustmentItem->item_id);
                $item->update([
                    'current_stock' => $adjustmentItem->adjusted_quantity
                ]);
            }

            // Mark as completed
            $stockAdjustment->update(['status' => 'completed']);

            DB::commit();

            return redirect()->route('stock-adjustments.show', $stockAdjustment)
                           ->with('success', 'Stock adjustment approved and applied successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('stock-adjustments.show', $stockAdjustment)
                           ->with('error', 'Error approving stock adjustment: ' . $e->getMessage());
        }
    }

    /**
     * Reject stock adjustment
     */
    public function reject(Request $request, StockAdjustment $stockAdjustment)
    {
        if ($stockAdjustment->status !== 'pending') {
            return redirect()->route('stock-adjustments.show', $stockAdjustment)
                           ->with('error', 'Only pending adjustments can be rejected.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $stockAdjustment->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'notes' => $stockAdjustment->notes . "\n\nRejection Reason: " . $request->rejection_reason
        ]);

        return redirect()->route('stock-adjustments.show', $stockAdjustment)
                       ->with('success', 'Stock adjustment rejected successfully!');
    }
}
