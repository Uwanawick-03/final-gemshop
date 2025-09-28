<?php

namespace App\Http\Controllers;

use App\Models\FinishedGoodTransfer;
use App\Models\Item;
use App\Models\Craftsman;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FinishedGoodTransferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $finishedGoodTransfers = FinishedGoodTransfer::with(['item', 'craftsman', 'qualityCheckBy', 'transferredBy', 'receivedBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('finished-good-transfers.index', compact('finishedGoodTransfers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $items = Item::where('is_active', true)->orderBy('name')->get();
        $craftsmen = Craftsman::where('is_active', true)->orderBy('first_name')->get();
        $users = User::orderBy('name')->get();

        return view('finished-good-transfers.create', compact('items', 'craftsmen', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'craftsman_id' => 'nullable|exists:craftsmen,id',
            'from_workshop' => 'required|string|max:255',
            'to_location' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'transfer_date' => 'required|date',
            'transferred_by' => 'nullable|exists:users,id',
            'notes' => 'nullable|string|max:1000'
        ]);

        // Generate reference number
        $referenceNumber = 'FGT-' . strtoupper(Str::random(8));

        $finishedGoodTransfer = FinishedGoodTransfer::create([
            'item_id' => $request->item_id,
            'craftsman_id' => $request->craftsman_id,
            'from_workshop' => $request->from_workshop,
            'to_location' => $request->to_location,
            'quantity' => $request->quantity,
            'transfer_date' => $request->transfer_date,
            'reference_number' => $referenceNumber,
            'transferred_by' => $request->transferred_by,
            'notes' => $request->notes,
            'status' => 'pending'
        ]);

        return redirect()->route('finished-good-transfers.show', $finishedGoodTransfer)
            ->with('success', 'Finished good transfer created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(FinishedGoodTransfer $finishedGoodTransfer)
    {
        $finishedGoodTransfer->load(['item', 'craftsman', 'qualityCheckBy', 'transferredBy', 'receivedBy']);
        return view('finished-good-transfers.show', compact('finishedGoodTransfer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FinishedGoodTransfer $finishedGoodTransfer)
    {
        $items = Item::where('is_active', true)->orderBy('name')->get();
        $craftsmen = Craftsman::where('is_active', true)->orderBy('first_name')->get();
        $users = User::orderBy('name')->get();

        return view('finished-good-transfers.edit', compact('finishedGoodTransfer', 'items', 'craftsmen', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FinishedGoodTransfer $finishedGoodTransfer)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'craftsman_id' => 'nullable|exists:craftsmen,id',
            'from_workshop' => 'required|string|max:255',
            'to_location' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'transfer_date' => 'required|date',
            'transferred_by' => 'nullable|exists:users,id',
            'received_by' => 'nullable|exists:users,id',
            'status' => 'required|in:pending,quality_check,completed,rejected',
            'quality_check_passed' => 'boolean',
            'quality_check_by' => 'nullable|exists:users,id',
            'notes' => 'nullable|string|max:1000'
        ]);

        $finishedGoodTransfer->update($request->all());

        return redirect()->route('finished-good-transfers.show', $finishedGoodTransfer)
            ->with('success', 'Finished good transfer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FinishedGoodTransfer $finishedGoodTransfer)
    {
        $finishedGoodTransfer->delete();

        return redirect()->route('finished-good-transfers.index')
            ->with('success', 'Finished good transfer deleted successfully.');
    }

    /**
     * Update the status of a finished good transfer
     */
    public function updateStatus(Request $request, FinishedGoodTransfer $finishedGoodTransfer)
    {
        $request->validate([
            'status' => 'required|in:pending,quality_check,completed,rejected',
            'quality_check_passed' => 'boolean',
            'quality_check_by' => 'nullable|exists:users,id',
            'received_by' => 'nullable|exists:users,id',
            'notes' => 'nullable|string|max:1000'
        ]);

        $updateData = [
            'status' => $request->status
        ];

        // If quality check, set quality check details
        if ($request->status === 'quality_check') {
            $updateData['quality_check_by'] = Auth::id();
        }

        // If completed, set received by
        if ($request->status === 'completed') {
            $updateData['received_by'] = $request->received_by ?? Auth::id();
        }

        if ($request->has('quality_check_passed')) {
            $updateData['quality_check_passed'] = $request->quality_check_passed;
        }

        if ($request->quality_check_by) {
            $updateData['quality_check_by'] = $request->quality_check_by;
        }

        if ($request->notes) {
            $updateData['notes'] = $request->notes;
        }

        $finishedGoodTransfer->update($updateData);

        return redirect()->back()
            ->with('success', 'Finished good transfer status updated successfully.');
    }

    /**
     * Bulk status update for multiple finished good transfers
     */
    public function bulkStatusUpdate(Request $request)
    {
        $request->validate([
            'finished_good_transfer_ids' => 'required|array',
            'finished_good_transfer_ids.*' => 'exists:finished_good_transfers,id',
            'status' => 'required|in:pending,quality_check,completed,rejected'
        ]);

        $updateData = [
            'status' => $request->status
        ];

        // If quality check, set quality check by
        if ($request->status === 'quality_check') {
            $updateData['quality_check_by'] = Auth::id();
        }

        // If completed, set received by
        if ($request->status === 'completed') {
            $updateData['received_by'] = Auth::id();
        }

        FinishedGoodTransfer::whereIn('id', $request->finished_good_transfer_ids)->update($updateData);

        return redirect()->back()
            ->with('success', 'Selected finished good transfers status updated successfully.');
    }

    /**
     * Export finished good transfer as PDF
     */
    public function exportPdf(FinishedGoodTransfer $finishedGoodTransfer)
    {
        $finishedGoodTransfer->load(['item', 'craftsman', 'qualityCheckBy', 'transferredBy', 'receivedBy']);
        
        $pdf = \PDF::loadView('finished-good-transfers.pdf', compact('finishedGoodTransfer'));
        return $pdf->download("finished-good-transfer-{$finishedGoodTransfer->reference_number}.pdf");
    }

    /**
     * Get finished good transfers by status for dashboard
     */
    public function getByStatus($status)
    {
        $finishedGoodTransfers = FinishedGoodTransfer::with(['item', 'craftsman', 'transferredBy'])
            ->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($finishedGoodTransfers);
    }

    /**
     * Quality check action
     */
    public function qualityCheck(Request $request, FinishedGoodTransfer $finishedGoodTransfer)
    {
        $request->validate([
            'quality_check_passed' => 'required|boolean',
            'notes' => 'nullable|string|max:1000'
        ]);

        $finishedGoodTransfer->update([
            'status' => 'quality_check',
            'quality_check_passed' => $request->quality_check_passed,
            'quality_check_by' => Auth::id(),
            'notes' => $request->notes ?? $finishedGoodTransfer->notes
        ]);

        $status = $request->quality_check_passed ? 'passed' : 'failed';
        return redirect()->back()
            ->with('success', "Quality check {$status} successfully.");
    }

    /**
     * Complete transfer action
     */
    public function completeTransfer(Request $request, FinishedGoodTransfer $finishedGoodTransfer)
    {
        $request->validate([
            'received_by' => 'nullable|exists:users,id',
            'notes' => 'nullable|string|max:1000'
        ]);

        $finishedGoodTransfer->update([
            'status' => 'completed',
            'received_by' => $request->received_by ?? Auth::id(),
            'notes' => $request->notes ?? $finishedGoodTransfer->notes
        ]);

        return redirect()->back()
            ->with('success', 'Transfer completed successfully.');
    }
}
