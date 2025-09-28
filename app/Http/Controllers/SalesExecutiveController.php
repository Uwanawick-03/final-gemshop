<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesExecutive;
use Illuminate\Support\Str;

class SalesExecutiveController extends Controller
{
    public function index(Request $request)
    {
        $query = SalesExecutive::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('executive_code', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%");
            });
        }

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        if ($request->filled('status')) {
            $query->where('employment_status', $request->status);
        }

        if ($request->filled('active')) {
            $query->where('is_active', $request->active);
        }

        $salesExecutives = $query->orderBy('created_at', 'desc')->paginate(20);

        $departments = SalesExecutive::select('department')
            ->whereNotNull('department')
            ->distinct()
            ->pluck('department')
            ->filter()
            ->sort()
            ->values();

        return view('sales-executives.index', compact('salesExecutives', 'departments'));
    }

    public function create()
    {
        return view('sales-executives.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:sales_executives,email',
            'phone' => 'required|string|max:20',
            'hire_date' => 'required|date',
            'department' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'salary' => 'nullable|numeric|min:0',
            'employment_status' => 'required|in:active,inactive,terminated,on_leave',
        ]);

        $executiveCode = 'SE-' . strtoupper(Str::random(6));

        SalesExecutive::create([
            'executive_code' => $executiveCode,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'country' => $request->country,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'national_id' => $request->national_id,
            'hire_date' => $request->hire_date,
            'salary' => $request->salary,
            'department' => $request->department,
            'position' => $request->position,
            'employment_status' => $request->employment_status,
            'is_active' => $request->has('is_active'),
            'notes' => $request->notes,
        ]);

        return redirect()->route('sales-executives.index')
            ->with('success', 'Sales Executive created successfully!');
    }

    public function show(SalesExecutive $salesExecutive)
    {
        $salesExecutive->load(['salesOrders.customer', 'invoices.customer']);
        
        return view('sales-executives.show', compact('salesExecutive'));
    }

    public function edit(SalesExecutive $salesExecutive)
    {
        return view('sales-executives.edit', compact('salesExecutive'));
    }

    public function update(Request $request, SalesExecutive $salesExecutive)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:sales_executives,email,' . $salesExecutive->id,
            'phone' => 'required|string|max:20',
            'hire_date' => 'required|date',
            'department' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'salary' => 'nullable|numeric|min:0',
            'employment_status' => 'required|in:active,inactive,terminated,on_leave',
        ]);

        $salesExecutive->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'country' => $request->country,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'national_id' => $request->national_id,
            'hire_date' => $request->hire_date,
            'salary' => $request->salary,
            'department' => $request->department,
            'position' => $request->position,
            'employment_status' => $request->employment_status,
            'is_active' => $request->has('is_active'),
            'notes' => $request->notes,
        ]);

        return redirect()->route('sales-executives.show', $salesExecutive)
            ->with('success', 'Sales Executive updated successfully!');
    }

    public function destroy(SalesExecutive $salesExecutive)
    {
        $salesExecutive->delete();

        return redirect()->route('sales-executives.index')
            ->with('success', 'Sales Executive deleted successfully!');
    }
}
