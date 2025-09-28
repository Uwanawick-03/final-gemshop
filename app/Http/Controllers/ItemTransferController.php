<?php

namespace App\Http\Controllers;

use App\Models\ItemTransfer;
use App\Models\Item;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ItemTransferController extends Controller
{
    public function index(Request $request)
    {
        $query = ItemTransfer::with(['item', 'transferredBy', 'receivedBy', 'createdBy']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('reason')) {
            $query->where('reason', $request->reason);
        }

        if ($request->filled('from_location')) {
            $query->where('from_location', 'like', '%' . $request->from_location . '%');
        }

        if ($request->filled('to_location')) {
            $query->where('to_location', 'like', '%' . $request->to_location . '%');
        }

        if ($request->filled('item_id')) {
            $query->where('item_id', $request->item_id);
        }

        if ($request->filled('transferred_by')) {
            $query->where('transferred_by', $request->transferred_by);
        }

        if ($request->filled('date_from')) {
            $query->where('transfer_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('transfer_date', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                  ->orWhere('from_location', 'like', "%{$search}%")
                  ->orWhere('to_location', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%")
                  ->orWhereHas('item', function($itemQuery) use ($search) {
                      $itemQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('item_code', 'like', "%{$search}%");
                  });
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'transfer_date');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        $itemTransfers = $query->paginate(15)->withQueryString();

        // Get filter options
        $items = Item::select('id', 'name', 'item_code')->orderBy('name')->get();
        $users = User::select('id', 'name')->orderBy('name')->get();
        $statuses = ItemTransfer::select('status')->distinct()->pluck('status');
        $reasons = ItemTransfer::select('reason')->distinct()->pluck('reason');
        $locations = ItemTransfer::select('from_location', 'to_location')
            ->get()
            ->flatMap(function($transfer) {
                return [$transfer->from_location, $transfer->to_location];
            })
            ->unique()
            ->sort()
            ->values();

        // Calculate statistics
        $totalTransfers = ItemTransfer::count();
        $pendingTransfers = ItemTransfer::where('status', 'pending')->count();
        $inTransitTransfers = ItemTransfer::where('status', 'in_transit')->count();
        $completedTransfers = ItemTransfer::where('status', 'completed')->count();
        $cancelledTransfers = ItemTransfer::where('status', 'cancelled')->count();
        $overdueTransfers = ItemTransfer::where('transfer_date', '<', now())
            ->where('status', 'pending')
            ->count();

        // Calculate total quantity transferred
        $totalQuantityTransferred = ItemTransfer::where('status', 'completed')->sum('quantity');

        return view('item-transfers.index', compact(
            'itemTransfers',
            'items',
            'users',
            'statuses',
            'reasons',
            'locations',
            'totalTransfers',
            'pendingTransfers',
            'inTransitTransfers',
            'completedTransfers',
            'cancelledTransfers',
            'overdueTransfers',
            'totalQuantityTransferred'
        ));
    }

    public function create()
    {
        $items = Item::select('id', 'name', 'item_code', 'current_stock')
            ->where('current_stock', '>', 0)
            ->orderBy('name')
            ->get();

        $users = User::select('id', 'name')->orderBy('name')->get();

        $reasons = [
            'restock' => 'Restock',
            'sale_transfer' => 'Sale Transfer',
            'repair' => 'Repair',
            'display' => 'Display',
            'storage' => 'Storage',
            'damage' => 'Damage',
            'other' => 'Other'
        ];

        $locations = [
            'Main Store',
            'Showroom',
            'Workshop',
            'Storage Room',
            'Display Area',
            'Repair Station',
            'Shipping Area',
            'Customer Location'
        ];

        return view('item-transfers.create', compact('items', 'users', 'reasons', 'locations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'from_location' => 'required|string|max:255',
            'to_location' => 'required|string|max:255|different:from_location',
            'quantity' => 'required|numeric|min:0.001',
            'transfer_date' => 'required|date|after_or_equal:today',
            'reason' => 'required|in:restock,sale_transfer,repair,display,storage,damage,other',
            'notes' => 'nullable|string|max:1000',
            'transferred_by' => 'nullable|exists:users,id'
        ]);

        // Check if item has sufficient stock
        $item = Item::findOrFail($request->item_id);
        if ($item->current_stock < $request->quantity) {
            return back()->withErrors(['quantity' => 'Insufficient stock. Available: ' . $item->current_stock]);
        }

        DB::beginTransaction();
        try {
            // Generate reference number
            $referenceNumber = 'IT-' . date('Y') . '-' . str_pad(ItemTransfer::count() + 1, 4, '0', STR_PAD_LEFT);

            $itemTransfer = ItemTransfer::create([
                'item_id' => $request->item_id,
                'reference_number' => $referenceNumber,
                'from_location' => $request->from_location,
                'to_location' => $request->to_location,
                'quantity' => $request->quantity,
                'transfer_date' => $request->transfer_date,
                'reason' => $request->reason,
                'status' => 'pending',
                'notes' => $request->notes,
                'transferred_by' => $request->transferred_by,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id()
            ]);

            DB::commit();

            return redirect()->route('item-transfers.index')
                ->with('success', 'Item transfer created successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Failed to create item transfer: ' . $e->getMessage()]);
        }
    }

    public function show(ItemTransfer $itemTransfer)
    {
        $itemTransfer->load(['item', 'transferredBy', 'receivedBy', 'createdBy', 'updatedBy']);

        return view('item-transfers.show', compact('itemTransfer'));
    }

    public function edit(ItemTransfer $itemTransfer)
    {
        if ($itemTransfer->status === 'completed') {
            return redirect()->route('item-transfers.show', $itemTransfer)
                ->with('error', 'Cannot edit completed transfers.');
        }

        $items = Item::select('id', 'name', 'item_code', 'current_stock')
            ->orderBy('name')
            ->get();

        $users = User::select('id', 'name')->orderBy('name')->get();

        $reasons = [
            'restock' => 'Restock',
            'sale_transfer' => 'Sale Transfer',
            'repair' => 'Repair',
            'display' => 'Display',
            'storage' => 'Storage',
            'damage' => 'Damage',
            'other' => 'Other'
        ];

        $locations = [
            'Main Store',
            'Showroom',
            'Workshop',
            'Storage Room',
            'Display Area',
            'Repair Station',
            'Shipping Area',
            'Customer Location'
        ];

        return view('item-transfers.edit', compact('itemTransfer', 'items', 'users', 'reasons', 'locations'));
    }

    public function update(Request $request, ItemTransfer $itemTransfer)
    {
        if ($itemTransfer->status === 'completed') {
            return redirect()->route('item-transfers.show', $itemTransfer)
                ->with('error', 'Cannot edit completed transfers.');
        }

        $request->validate([
            'item_id' => 'required|exists:items,id',
            'from_location' => 'required|string|max:255',
            'to_location' => 'required|string|max:255|different:from_location',
            'quantity' => 'required|numeric|min:0.001',
            'transfer_date' => 'required|date',
            'reason' => 'required|in:restock,sale_transfer,repair,display,storage,damage,other',
            'notes' => 'nullable|string|max:1000',
            'transferred_by' => 'nullable|exists:users,id'
        ]);

        // Check if item has sufficient stock (if quantity increased)
        if ($request->quantity > $itemTransfer->quantity) {
            $item = Item::findOrFail($request->item_id);
            $additionalQuantity = $request->quantity - $itemTransfer->quantity;
            if ($item->current_stock < $additionalQuantity) {
                return back()->withErrors(['quantity' => 'Insufficient stock. Available: ' . $item->current_stock]);
            }
        }

        DB::beginTransaction();
        try {
            $itemTransfer->update([
                'item_id' => $request->item_id,
                'from_location' => $request->from_location,
                'to_location' => $request->to_location,
                'quantity' => $request->quantity,
                'transfer_date' => $request->transfer_date,
                'reason' => $request->reason,
                'notes' => $request->notes,
                'transferred_by' => $request->transferred_by,
                'updated_by' => auth()->id()
            ]);

            DB::commit();

            return redirect()->route('item-transfers.index')
                ->with('success', 'Item transfer updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Failed to update item transfer: ' . $e->getMessage()]);
        }
    }

    public function destroy(ItemTransfer $itemTransfer)
    {
        if ($itemTransfer->status === 'completed') {
            return redirect()->route('item-transfers.index')
                ->with('error', 'Cannot delete completed transfers.');
        }

        DB::beginTransaction();
        try {
            $itemTransfer->delete();

            DB::commit();

            return redirect()->route('item-transfers.index')
                ->with('success', 'Item transfer deleted successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Failed to delete item transfer: ' . $e->getMessage()]);
        }
    }

    public function updateStatus(Request $request, ItemTransfer $itemTransfer)
    {
        $request->validate([
            'status' => 'required|in:pending,in_transit,completed,cancelled',
            'received_by' => 'nullable|exists:users,id'
        ]);

        DB::beginTransaction();
        try {
            $itemTransfer->update([
                'status' => $request->status,
                'received_by' => $request->received_by,
                'received_at' => $request->status === 'completed' ? now() : null,
                'updated_by' => auth()->id()
            ]);

            // If completed, update item stock
            if ($request->status === 'completed') {
                $item = $itemTransfer->item;
                $item->decrement('current_stock', $itemTransfer->quantity);
            }

            DB::commit();

            return redirect()->route('item-transfers.show', $itemTransfer)
                ->with('success', 'Transfer status updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Failed to update status: ' . $e->getMessage()]);
        }
    }

    public function bulkStatusUpdate(Request $request)
    {
        $request->validate([
            'transfer_ids' => 'required|array|min:1',
            'transfer_ids.*' => 'exists:item_transfers,id',
            'status' => 'required|in:pending,in_transit,completed,cancelled',
            'received_by' => 'nullable|exists:users,id'
        ]);

        DB::beginTransaction();
        try {
            $transfers = ItemTransfer::whereIn('id', $request->transfer_ids)->get();

            foreach ($transfers as $transfer) {
                $transfer->update([
                    'status' => $request->status,
                    'received_by' => $request->received_by,
                    'received_at' => $request->status === 'completed' ? now() : null,
                    'updated_by' => auth()->id()
                ]);

                // If completed, update item stock
                if ($request->status === 'completed') {
                    $item = $transfer->item;
                    $item->decrement('current_stock', $transfer->quantity);
                }
            }

            DB::commit();

            return redirect()->route('item-transfers.index')
                ->with('success', 'Bulk status update completed successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Failed to update statuses: ' . $e->getMessage()]);
        }
    }

    public function exportPdf(ItemTransfer $itemTransfer)
    {
        $itemTransfer->load(['item', 'transferredBy', 'receivedBy', 'createdBy']);

        $pdf = \PDF::loadView('item-transfers.pdf', compact('itemTransfer'));
        
        return $pdf->download('item-transfer-' . $itemTransfer->reference_number . '.pdf');
    }

    public function getItemStock(Request $request)
    {
        $item = Item::findOrFail($request->item_id);
        
        return response()->json([
            'current_stock' => $item->current_stock,
            'item_name' => $item->name,
            'item_code' => $item->item_code
        ]);
    }
}
