<?php

namespace App\Http\Controllers;

use App\Models\WorkshopAdjustment;
use App\Models\Item;
use App\Models\Craftsman;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WorkshopAdjustmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $workshopAdjustments = WorkshopAdjustment::with(['item', 'craftsman', 'approvedBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('workshop-adjustments.index', compact('workshopAdjustments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $items = Item::where('is_active', true)->orderBy('name')->get();
        $craftsmen = Craftsman::where('is_active', true)->orderBy('first_name')->get();
        $users = User::orderBy('name')->get();

        return view('workshop-adjustments.create', compact('items', 'craftsmen', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'workshop_location' => 'required|string|max:255',
            'adjustment_type' => 'required|in:material_used,scrap,defective,correction',
            'quantity' => 'required|integer|min:1',
            'adjustment_date' => 'required|date',
            'reason' => 'required|string|max:1000',
            'craftsman_id' => 'nullable|exists:craftsmen,id',
            'notes' => 'nullable|string|max:1000'
        ]);

        // Generate reference number
        $referenceNumber = 'WA-' . strtoupper(Str::random(8));

        $workshopAdjustment = WorkshopAdjustment::create([
            'item_id' => $request->item_id,
            'workshop_location' => $request->workshop_location,
            'adjustment_type' => $request->adjustment_type,
            'quantity' => $request->quantity,
            'adjustment_date' => $request->adjustment_date,
            'reference_number' => $referenceNumber,
            'reason' => $request->reason,
            'craftsman_id' => $request->craftsman_id,
            'notes' => $request->notes,
            'status' => 'pending'
        ]);

        return redirect()->route('workshop-adjustments.show', $workshopAdjustment)
            ->with('success', 'Workshop adjustment created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(WorkshopAdjustment $workshopAdjustment)
    {
        $workshopAdjustment->load(['item', 'craftsman', 'approvedBy']);
        return view('workshop-adjustments.show', compact('workshopAdjustment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WorkshopAdjustment $workshopAdjustment)
    {
        $items = Item::where('is_active', true)->orderBy('name')->get();
        $craftsmen = Craftsman::where('is_active', true)->orderBy('first_name')->get();
        $users = User::orderBy('name')->get();

        return view('workshop-adjustments.edit', compact('workshopAdjustment', 'items', 'craftsmen', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WorkshopAdjustment $workshopAdjustment)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'workshop_location' => 'required|string|max:255',
            'adjustment_type' => 'required|in:material_used,scrap,defective,correction',
            'quantity' => 'required|integer|min:1',
            'adjustment_date' => 'required|date',
            'reason' => 'required|string|max:1000',
            'craftsman_id' => 'nullable|exists:craftsmen,id',
            'approved_by' => 'nullable|exists:users,id',
            'status' => 'required|in:pending,approved,rejected',
            'notes' => 'nullable|string|max:1000'
        ]);

        $workshopAdjustment->update($request->all());

        return redirect()->route('workshop-adjustments.show', $workshopAdjustment)
            ->with('success', 'Workshop adjustment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WorkshopAdjustment $workshopAdjustment)
    {
        $workshopAdjustment->delete();

        return redirect()->route('workshop-adjustments.index')
            ->with('success', 'Workshop adjustment deleted successfully.');
    }

    /**
     * Approve a workshop adjustment
     */
    public function approve(Request $request, WorkshopAdjustment $workshopAdjustment)
    {
        $request->validate([
            'notes' => 'nullable|string|max:1000'
        ]);

        $workshopAdjustment->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'notes' => $request->notes ?? $workshopAdjustment->notes
        ]);

        // Update item stock based on adjustment type
        $this->updateItemStock($workshopAdjustment);

        return redirect()->back()
            ->with('success', 'Workshop adjustment approved successfully.');
    }

    /**
     * Reject a workshop adjustment
     */
    public function reject(Request $request, WorkshopAdjustment $workshopAdjustment)
    {
        $request->validate([
            'notes' => 'required|string|max:1000'
        ]);

        $workshopAdjustment->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'notes' => $request->notes
        ]);

        return redirect()->back()
            ->with('success', 'Workshop adjustment rejected successfully.');
    }

    /**
     * Bulk status update for multiple workshop adjustments
     */
    public function bulkStatusUpdate(Request $request)
    {
        $request->validate([
            'workshop_adjustment_ids' => 'required|array',
            'workshop_adjustment_ids.*' => 'exists:workshop_adjustments,id',
            'status' => 'required|in:pending,approved,rejected'
        ]);

        $updateData = [
            'status' => $request->status
        ];

        // If approved, set approved by
        if ($request->status === 'approved') {
            $updateData['approved_by'] = Auth::id();
        }

        WorkshopAdjustment::whereIn('id', $request->workshop_adjustment_ids)->update($updateData);

        // Update item stock for approved adjustments
        if ($request->status === 'approved') {
            $approvedAdjustments = WorkshopAdjustment::whereIn('id', $request->workshop_adjustment_ids)->get();
            foreach ($approvedAdjustments as $adjustment) {
                $this->updateItemStock($adjustment);
            }
        }

        return redirect()->back()
            ->with('success', 'Selected workshop adjustments status updated successfully.');
    }

    /**
     * Export workshop adjustment as PDF
     */
    public function exportPdf(WorkshopAdjustment $workshopAdjustment)
    {
        $workshopAdjustment->load(['item', 'craftsman', 'approvedBy']);
        
        $pdf = \PDF::loadView('workshop-adjustments.pdf', compact('workshopAdjustment'));
        return $pdf->download("workshop-adjustment-{$workshopAdjustment->reference_number}.pdf");
    }

    /**
     * Get workshop adjustments by status for dashboard
     */
    public function getByStatus($status)
    {
        $workshopAdjustments = WorkshopAdjustment::with(['item', 'craftsman', 'approvedBy'])
            ->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($workshopAdjustments);
    }

    /**
     * Update item stock based on adjustment type
     */
    private function updateItemStock(WorkshopAdjustment $workshopAdjustment)
    {
        $item = $workshopAdjustment->item;
        $quantity = $workshopAdjustment->quantity;

        switch ($workshopAdjustment->adjustment_type) {
            case 'material_used':
                // Decrease stock for material used
                $item->decrement('current_stock', $quantity);
                break;
            case 'scrap':
                // Decrease stock for scrap
                $item->decrement('current_stock', $quantity);
                break;
            case 'defective':
                // Decrease stock for defective items
                $item->decrement('current_stock', $quantity);
                break;
            case 'correction':
                // Increase stock for correction (if it's a positive correction)
                $item->increment('current_stock', $quantity);
                break;
        }
    }
}
