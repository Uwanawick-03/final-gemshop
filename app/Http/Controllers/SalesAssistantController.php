<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesAssistant;
use Illuminate\Support\Str;

class SalesAssistantController extends Controller
{
    public function index(Request $request)
    {
        $query = SalesAssistant::query();
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('assistant_code', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%");
            });
        }

        // Filter by department
        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        // Filter by employment status
        if ($request->filled('status')) {
            $query->where('employment_status', $request->status);
        }

        // Filter by active status
        if ($request->filled('active')) {
            $query->where('is_active', $request->active);
        }
        
        $salesAssistants = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Get unique departments for filter dropdown
        $departments = SalesAssistant::select('department')
            ->whereNotNull('department')
            ->distinct()
            ->pluck('department')
            ->filter()
            ->sort()
            ->values();
        
        return view('sales-assistants.index', compact('salesAssistants', 'departments'));
    }

    public function create()
    {
        return view('sales-assistants.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:sales_assistants,email',
            'phone' => 'required|string|max:20',
            'hire_date' => 'required|date',
            'department' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'salary' => 'nullable|numeric|min:0',
            'employment_status' => 'required|in:active,inactive,terminated,on_leave',
        ]);
        
        $assistantCode = 'SA-' . strtoupper(Str::random(6));
        
        SalesAssistant::create([
            'assistant_code' => $assistantCode,
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
        
        return redirect()->route('sales-assistants.index')
            ->with('success', 'Sales Assistant created successfully!');
    }

    public function show(SalesAssistant $salesAssistant)
    {
        // Eager load relationships for better performance
        $salesAssistant->load(['salesOrders', 'invoices']);
        
        return view('sales-assistants.show', compact('salesAssistant'));
    }

    public function edit(SalesAssistant $salesAssistant)
    {
        return view('sales-assistants.edit', compact('salesAssistant'));
    }

    public function update(Request $request, SalesAssistant $salesAssistant)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:sales_assistants,email,' . $salesAssistant->id,
            'phone' => 'required|string|max:20',
            'hire_date' => 'required|date',
            'department' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'salary' => 'nullable|numeric|min:0',
            'employment_status' => 'required|in:active,inactive,terminated,on_leave',
        ]);
        
        $salesAssistant->update([
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
        
        return redirect()->route('sales-assistants.show', $salesAssistant)
            ->with('success', 'Sales Assistant updated successfully!');
    }

    public function destroy(SalesAssistant $salesAssistant)
    {
        $salesAssistant->delete();
        
        return redirect()->route('sales-assistants.index')
            ->with('success', 'Sales Assistant deleted successfully!');
    }
}
