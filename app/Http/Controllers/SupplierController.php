<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Supplier::query();

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function($q) use ($search) {
                $q->where('supplier_code', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('country', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->string('status') === 'active') $query->where('is_active', true);
            if ($request->string('status') === 'inactive') $query->where('is_active', false);
        }

        if ($request->filled('payment_terms')) {
            $query->where('payment_terms', $request->string('payment_terms'));
        }

        $suppliers = $query->orderBy('company_name')->paginate(20);
        $terms = ['cash','net_15','net_30','net_60','net_90'];
        return view('suppliers.index', compact('suppliers','terms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $terms = ['cash','net_15','net_30','net_60','net_90'];
        return view('suppliers.create', compact('terms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_code' => 'required|string|max:50|unique:suppliers,supplier_code',
            'company_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:50',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'tax_id' => 'nullable|string|max:100',
            'credit_limit' => 'nullable|numeric|min:0',
            'current_balance' => 'nullable|numeric|min:0',
            'payment_terms' => 'required|in:cash,net_15,net_30,net_60,net_90',
            'is_active' => 'sometimes|boolean',
            'notes' => 'nullable|string',
        ]);

        $supplier = Supplier::create([
            'supplier_code' => $validated['supplier_code'],
            'company_name' => $validated['company_name'],
            'contact_person' => $validated['contact_person'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'city' => $validated['city'],
            'country' => $validated['country'],
            'tax_id' => $validated['tax_id'] ?? null,
            'credit_limit' => $validated['credit_limit'] ?? 0,
            'current_balance' => $validated['current_balance'] ?? 0,
            'payment_terms' => $validated['payment_terms'],
            'is_active' => $request->boolean('is_active'),
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('suppliers.show', $supplier)->with('success', 'Supplier created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        return view('suppliers.show', compact('supplier'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        $terms = ['cash','net_15','net_30','net_60','net_90'];
        return view('suppliers.edit', compact('supplier','terms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'supplier_code' => 'required|string|max:50|unique:suppliers,supplier_code,' . $supplier->id,
            'company_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:50',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'tax_id' => 'nullable|string|max:100',
            'credit_limit' => 'nullable|numeric|min:0',
            'current_balance' => 'nullable|numeric|min:0',
            'payment_terms' => 'required|in:cash,net_15,net_30,net_60,net_90',
            'is_active' => 'sometimes|boolean',
            'notes' => 'nullable|string',
        ]);

        $supplier->supplier_code = $validated['supplier_code'];
        $supplier->company_name = $validated['company_name'];
        $supplier->contact_person = $validated['contact_person'];
        $supplier->email = $validated['email'] ?? null;
        $supplier->phone = $validated['phone'];
        $supplier->address = $validated['address'];
        $supplier->city = $validated['city'];
        $supplier->country = $validated['country'];
        $supplier->tax_id = $validated['tax_id'] ?? null;
        $supplier->credit_limit = $validated['credit_limit'] ?? 0;
        $supplier->current_balance = $validated['current_balance'] ?? 0;
        $supplier->payment_terms = $validated['payment_terms'];
        $supplier->is_active = $request->boolean('is_active');
        $supplier->notes = $validated['notes'] ?? null;
        $supplier->save();

        return redirect()->route('suppliers.show', $supplier)->with('success', 'Supplier updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully');
    }
}
