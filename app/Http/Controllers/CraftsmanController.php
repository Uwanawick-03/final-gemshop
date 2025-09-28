<?php

namespace App\Http\Controllers;

use App\Models\Craftsman;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CraftsmanController extends Controller
{
    public function index(Request $request)
    {
        $query = Craftsman::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('craftsman_code', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('primary_skill', 'like', "%{$search}%");
            });
        }

        if ($request->filled('skill')) {
            $query->where('primary_skill', $request->skill)
                  ->orWhereJsonContains('skills', $request->skill);
        }

        if ($request->filled('status')) {
            $query->where('employment_status', $request->status);
        }

        if ($request->filled('active')) {
            $query->where('is_active', $request->active);
        }

        $craftsmen = $query->orderBy('created_at', 'desc')->paginate(20);

        $skills = Craftsman::select('primary_skill')
            ->whereNotNull('primary_skill')
            ->distinct()
            ->pluck('primary_skill')
            ->filter()
            ->sort()
            ->values();

        return view('craftsmen.index', compact('craftsmen', 'skills'));
    }

    public function create()
    {
        return view('craftsmen.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:craftsmen,email',
            'phone' => 'required|string|max:20',
            'joined_date' => 'nullable|date',
            'hourly_rate' => 'nullable|numeric|min:0',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'employment_status' => 'required|in:active,inactive,terminated,on_leave',
        ]);

        $code = 'CR-' . strtoupper(Str::random(6));

        Craftsman::create([
            'craftsman_code' => $code,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'country' => $request->country,
            'gender' => $request->gender,
            'national_id' => $request->national_id,
            'date_of_birth' => $request->date_of_birth,
            'joined_date' => $request->joined_date,
            'primary_skill' => $request->primary_skill,
            'skills' => $request->skills ? array_map('trim', explode(',', $request->skills)) : null,
            'hourly_rate' => $request->hourly_rate,
            'commission_rate' => $request->commission_rate,
            'employment_status' => $request->employment_status,
            'is_active' => $request->has('is_active'),
            'notes' => $request->notes,
        ]);

        return redirect()->route('craftsmen.index')->with('success', 'Craftsman created successfully!');
    }

    public function show(Craftsman $craftsman)
    {
        // Don't load relationships that don't exist yet to avoid database errors
        // $craftsman->load(['jobIssues', 'alterationCommissions', 'craftsmanReturns']);
        
        return view('craftsmen.show', compact('craftsman'));
    }

    public function edit(Craftsman $craftsman)
    {
        return view('craftsmen.edit', compact('craftsman'));
    }

    public function update(Request $request, Craftsman $craftsman)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:craftsmen,email,' . $craftsman->id,
            'phone' => 'required|string|max:20',
            'joined_date' => 'nullable|date',
            'hourly_rate' => 'nullable|numeric|min:0',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'employment_status' => 'required|in:active,inactive,terminated,on_leave',
        ]);

        $craftsman->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'country' => $request->country,
            'gender' => $request->gender,
            'national_id' => $request->national_id,
            'date_of_birth' => $request->date_of_birth,
            'joined_date' => $request->joined_date,
            'primary_skill' => $request->primary_skill,
            'skills' => $request->skills ? array_map('trim', explode(',', $request->skills)) : null,
            'hourly_rate' => $request->hourly_rate,
            'commission_rate' => $request->commission_rate,
            'employment_status' => $request->employment_status,
            'is_active' => $request->has('is_active'),
            'notes' => $request->notes,
        ]);

        return redirect()->route('craftsmen.show', $craftsman)->with('success', 'Craftsman updated successfully!');
    }

    public function destroy(Craftsman $craftsman)
    {
        $craftsman->delete();
        return redirect()->route('craftsmen.index')->with('success', 'Craftsman deleted successfully!');
    }
}
