<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BankController extends Controller
{
    public function index(Request $request)
    {
        $query = Bank::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('bank_code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('branch', 'like', "%{$search}%")
                  ->orWhere('swift_code', 'like', "%{$search}%")
                  ->orWhere('account_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('currency')) {
            $query->where('currency', $request->currency);
        }

        if ($request->filled('active')) {
            $query->where('is_active', $request->active);
        }

        $banks = $query->orderBy('name')->paginate(20);

        $currencies = Bank::select('currency')
            ->whereNotNull('currency')
            ->distinct()
            ->pluck('currency')
            ->filter()
            ->sort()
            ->values();

        return view('banks.index', compact('banks', 'currencies'));
    }

    public function create()
    {
        return view('banks.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'branch' => 'nullable|string|max:255',
            'swift_code' => 'nullable|string|max:50',
            'account_number' => 'nullable|string|max:100',
            'account_name' => 'nullable|string|max:255',
            'currency' => 'nullable|string|max:10',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
        ]);

        $code = 'BK-' . strtoupper(Str::random(6));

        Bank::create([
            'bank_code' => $code,
            'name' => $request->name,
            'branch' => $request->branch,
            'swift_code' => $request->swift_code,
            'account_number' => $request->account_number,
            'account_name' => $request->account_name,
            'currency' => $request->currency,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'city' => $request->city,
            'country' => $request->country,
            'is_active' => $request->has('is_active'),
            'notes' => $request->notes,
        ]);

        return redirect()->route('banks.index')->with('success', 'Bank created successfully!');
    }

    public function show(Bank $bank)
    {
        return view('banks.show', compact('bank'));
    }

    public function edit(Bank $bank)
    {
        return view('banks.edit', compact('bank'));
    }

    public function update(Request $request, Bank $bank)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'branch' => 'nullable|string|max:255',
            'swift_code' => 'nullable|string|max:50',
            'account_number' => 'nullable|string|max:100',
            'account_name' => 'nullable|string|max:255',
            'currency' => 'nullable|string|max:10',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
        ]);

        $bank->update([
            'name' => $request->name,
            'branch' => $request->branch,
            'swift_code' => $request->swift_code,
            'account_number' => $request->account_number,
            'account_name' => $request->account_name,
            'currency' => $request->currency,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'city' => $request->city,
            'country' => $request->country,
            'is_active' => $request->has('is_active'),
            'notes' => $request->notes,
        ]);

        return redirect()->route('banks.index')->with('success', 'Bank updated successfully!');
    }

    public function destroy(Bank $bank)
    {
        $bank->delete();
        return redirect()->route('banks.index')->with('success', 'Bank deleted successfully!');
    }
}
