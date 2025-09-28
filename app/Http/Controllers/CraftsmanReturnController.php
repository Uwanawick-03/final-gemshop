<?php

namespace App\Http\Controllers;

use App\Models\CraftsmanReturn;
use App\Models\Craftsman;
use App\Models\Item;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CraftsmanReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $craftsmanReturns = CraftsmanReturn::with(['craftsman', 'item', 'processedBy', 'approvedBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('craftsman-returns.index', compact('craftsmanReturns'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $craftsmen = Craftsman::where('is_active', true)->orderBy('first_name')->get();
        $items = Item::where('is_active', true)->orderBy('name')->get();
        $users = User::orderBy('name')->get();

        return view('craftsman-returns.create', compact('craftsmen', 'items', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'craftsman_id' => 'required|exists:craftsmen,id',
            'item_id' => 'required|exists:items,id',
            'return_type' => 'required|in:defective,unused_material,excess,quality_issue',
            'quantity' => 'required|integer|min:1',
            'return_date' => 'required|date',
            'reason' => 'required|string|max:1000',
            'notes' => 'nullable|string|max:1000'
        ]);

        // Generate return number
        $returnNumber = 'CR-' . strtoupper(Str::random(8));

        $craftsmanReturn = CraftsmanReturn::create([
            'craftsman_id' => $request->craftsman_id,
            'item_id' => $request->item_id,
            'return_number' => $returnNumber,
            'return_date' => $request->return_date,
            'return_type' => $request->return_type,
            'quantity' => $request->quantity,
            'reason' => $request->reason,
            'notes' => $request->notes,
            'status' => 'pending'
        ]);

        return redirect()->route('craftsman-returns.show', $craftsmanReturn)
            ->with('success', 'Craftsman return created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CraftsmanReturn $craftsmanReturn)
    {
        $craftsmanReturn->load(['craftsman', 'item', 'processedBy', 'approvedBy']);
        return view('craftsman-returns.show', compact('craftsmanReturn'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CraftsmanReturn $craftsmanReturn)
    {
        $craftsmen = Craftsman::where('is_active', true)->orderBy('first_name')->get();
        $items = Item::where('is_active', true)->orderBy('name')->get();
        $users = User::orderBy('name')->get();

        return view('craftsman-returns.edit', compact('craftsmanReturn', 'craftsmen', 'items', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CraftsmanReturn $craftsmanReturn)
    {
        $request->validate([
            'craftsman_id' => 'required|exists:craftsmen,id',
            'item_id' => 'required|exists:items,id',
            'return_type' => 'required|in:defective,unused_material,excess,quality_issue',
            'quantity' => 'required|integer|min:1',
            'return_date' => 'required|date',
            'reason' => 'required|string|max:1000',
            'processed_by' => 'nullable|exists:users,id',
            'approved_by' => 'nullable|exists:users,id',
            'status' => 'required|in:pending,approved,completed,rejected',
            'notes' => 'nullable|string|max:1000'
        ]);

        $craftsmanReturn->update($request->all());

        return redirect()->route('craftsman-returns.show', $craftsmanReturn)
            ->with('success', 'Craftsman return updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CraftsmanReturn $craftsmanReturn)
    {
        $craftsmanReturn->delete();

        return redirect()->route('craftsman-returns.index')
            ->with('success', 'Craftsman return deleted successfully.');
    }

    /**
     * Approve a craftsman return
     */
    public function approve(Request $request, CraftsmanReturn $craftsmanReturn)
    {
        $request->validate([
            'notes' => 'nullable|string|max:1000'
        ]);

        $craftsmanReturn->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'notes' => $request->notes ?? $craftsmanReturn->notes
        ]);

        return redirect()->back()
            ->with('success', 'Craftsman return approved successfully.');
    }

    /**
     * Reject a craftsman return
     */
    public function reject(Request $request, CraftsmanReturn $craftsmanReturn)
    {
        $request->validate([
            'notes' => 'required|string|max:1000'
        ]);

        $craftsmanReturn->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'notes' => $request->notes
        ]);

        return redirect()->back()
            ->with('success', 'Craftsman return rejected successfully.');
    }

    /**
     * Complete a craftsman return
     */
    public function complete(Request $request, CraftsmanReturn $craftsmanReturn)
    {
        $request->validate([
            'notes' => 'nullable|string|max:1000'
        ]);

        $craftsmanReturn->update([
            'status' => 'completed',
            'processed_by' => Auth::id(),
            'notes' => $request->notes ?? $craftsmanReturn->notes
        ]);

        // Update item stock based on return type
        $this->updateItemStock($craftsmanReturn);

        return redirect()->back()
            ->with('success', 'Craftsman return completed successfully.');
    }

    /**
     * Bulk status update for multiple craftsman returns
     */
    public function bulkStatusUpdate(Request $request)
    {
        $request->validate([
            'craftsman_return_ids' => 'required|array',
            'craftsman_return_ids.*' => 'exists:craftsman_returns,id',
            'status' => 'required|in:pending,approved,completed,rejected'
        ]);

        $updateData = [
            'status' => $request->status
        ];

        // If approved, set approved by
        if ($request->status === 'approved') {
            $updateData['approved_by'] = Auth::id();
        }

        // If completed, set processed by
        if ($request->status === 'completed') {
            $updateData['processed_by'] = Auth::id();
        }

        CraftsmanReturn::whereIn('id', $request->craftsman_return_ids)->update($updateData);

        // Update item stock for completed returns
        if ($request->status === 'completed') {
            $completedReturns = CraftsmanReturn::whereIn('id', $request->craftsman_return_ids)->get();
            foreach ($completedReturns as $return) {
                $this->updateItemStock($return);
            }
        }

        return redirect()->back()
            ->with('success', 'Selected craftsman returns status updated successfully.');
    }

    /**
     * Export craftsman return as PDF
     */
    public function exportPdf(CraftsmanReturn $craftsmanReturn)
    {
        $craftsmanReturn->load(['craftsman', 'item', 'processedBy', 'approvedBy']);
        
        $pdf = \PDF::loadView('craftsman-returns.pdf', compact('craftsmanReturn'));
        return $pdf->download("craftsman-return-{$craftsmanReturn->return_number}.pdf");
    }

    /**
     * Get craftsman returns by status for dashboard
     */
    public function getByStatus($status)
    {
        $craftsmanReturns = CraftsmanReturn::with(['craftsman', 'item', 'processedBy', 'approvedBy'])
            ->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($craftsmanReturns);
    }

    /**
     * Get craftsman returns by craftsman
     */
    public function getByCraftsman($craftsmanId)
    {
        $craftsmanReturns = CraftsmanReturn::with(['item', 'processedBy', 'approvedBy'])
            ->where('craftsman_id', $craftsmanId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($craftsmanReturns);
    }

    /**
     * Update item stock based on return type
     */
    private function updateItemStock(CraftsmanReturn $craftsmanReturn)
    {
        $item = $craftsmanReturn->item;
        $quantity = $craftsmanReturn->quantity;

        switch ($craftsmanReturn->return_type) {
            case 'defective':
                // Decrease stock for defective items
                $item->decrement('current_stock', $quantity);
                break;
            case 'unused_material':
                // Increase stock for unused materials returned
                $item->increment('current_stock', $quantity);
                break;
            case 'excess':
                // Increase stock for excess materials returned
                $item->increment('current_stock', $quantity);
                break;
            case 'quality_issue':
                // Decrease stock for quality issue items
                $item->decrement('current_stock', $quantity);
                break;
        }
    }
}
