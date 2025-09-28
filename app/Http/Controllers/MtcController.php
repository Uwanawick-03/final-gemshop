<?php

namespace App\Http\Controllers;

use App\Models\Mtc;
use App\Models\Item;
use App\Models\Customer;
use App\Models\SalesAssistant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class MtcController extends Controller
{
    public function index(Request $request)
    {
        $query = Mtc::with(['item', 'customer', 'salesAssistant']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('mtc_number', 'like', "%{$search}%")
                  ->orWhereHas('item', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('customer', function($q) use ($search) {
                      $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->filled('sales_assistant_id')) {
            $query->where('sales_assistant_id', $request->sales_assistant_id);
        }

        if ($request->filled('issue_from')) {
            $query->where('issue_date', '>=', $request->issue_from);
        }

        if ($request->filled('issue_to')) {
            $query->where('issue_date', '<=', $request->issue_to);
        }

        if ($request->filled('expiry_from')) {
            $query->where('expiry_date', '>=', $request->expiry_from);
        }

        if ($request->filled('expiry_to')) {
            $query->where('expiry_date', '<=', $request->expiry_to);
        }

        $mtcs = $query->orderBy('created_at', 'desc')->paginate(20);

        $customers = Customer::where('is_active', true)->orderBy('first_name')->get();
        $salesAssistants = SalesAssistant::where('is_active', true)->orderBy('first_name')->get();

        return view('mtcs.index', compact('mtcs', 'customers', 'salesAssistants'));
    }

    public function create()
    {
        $items = Item::where('is_active', true)->orderBy('name')->get();
        $customers = Customer::where('is_active', true)->orderBy('first_name')->get();
        $salesAssistants = SalesAssistant::where('is_active', true)->orderBy('first_name')->get();

        return view('mtcs.create', compact('items', 'customers', 'salesAssistants'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'customer_id' => 'required|exists:customers,id',
            'sales_assistant_id' => 'required|exists:sales_assistants,id',
            'issue_date' => 'required|date',
            'expiry_date' => 'required|date|after:issue_date',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        $mtcNumber = 'MTC-' . strtoupper(Str::random(8));

        Mtc::create([
            'mtc_number' => $mtcNumber,
            'item_id' => $request->item_id,
            'customer_id' => $request->customer_id,
            'sales_assistant_id' => $request->sales_assistant_id,
            'issue_date' => $request->issue_date,
            'expiry_date' => $request->expiry_date,
            'purchase_price' => $request->purchase_price,
            'selling_price' => $request->selling_price,
            'status' => 'active',
            'notes' => $request->notes
        ]);

        return redirect()->route('mtcs.index')->with('success', 'MTC created successfully!');
    }

    public function show(Mtc $mtc)
    {
        $mtc->load(['item', 'customer', 'salesAssistant']);
        return view('mtcs.show', compact('mtc'));
    }

    public function edit(Mtc $mtc)
    {
        $items = Item::where('is_active', true)->orderBy('name')->get();
        $customers = Customer::where('is_active', true)->orderBy('first_name')->get();
        $salesAssistants = SalesAssistant::where('is_active', true)->orderBy('first_name')->get();

        return view('mtcs.edit', compact('mtc', 'items', 'customers', 'salesAssistants'));
    }

    public function update(Request $request, Mtc $mtc)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'customer_id' => 'required|exists:customers,id',
            'sales_assistant_id' => 'required|exists:sales_assistants,id',
            'issue_date' => 'required|date',
            'expiry_date' => 'required|date|after:issue_date',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'status' => 'required|in:active,expired,used,cancelled',
            'notes' => 'nullable|string'
        ]);

        $mtc->update($request->all());

        return redirect()->route('mtcs.show', $mtc)->with('success', 'MTC updated successfully!');
    }

    public function destroy(Mtc $mtc)
    {
        $mtc->delete();
        return redirect()->route('mtcs.index')->with('success', 'MTC deleted successfully!');
    }

    public function updateStatus(Request $request, Mtc $mtc)
    {
        $request->validate([
            'status' => 'required|in:active,expired,used,cancelled'
        ]);

        $mtc->update(['status' => $request->status]);

        return response()->json(['success' => true, 'message' => 'MTC status updated successfully!']);
    }

    public function exportPdf(Mtc $mtc)
    {
        $mtc->load(['item', 'customer', 'salesAssistant']);
        $pdf = \PDF::loadView('mtcs.pdf', compact('mtc'));
        return $pdf->download("MTC-{$mtc->mtc_number}.pdf");
    }

    public function bulkStatusUpdate(Request $request)
    {
        $request->validate([
            'mtc_ids' => 'required|array',
            'status' => 'required|in:active,expired,used,cancelled'
        ]);

        Mtc::whereIn('id', $request->mtc_ids)->update(['status' => $request->status]);

        return response()->json(['success' => true, 'message' => 'MTCs updated successfully!']);
    }
}
