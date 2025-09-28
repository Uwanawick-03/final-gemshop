<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerReturn;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Currency;
use App\Models\TransactionItem;
use App\Services\CurrencyService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CustomerReturnController extends Controller
{
    protected $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = CustomerReturn::with(['customer', 'currency']);
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('return_number', 'like', "%{$search}%")
                  ->orWhere('reason', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($sq) use ($search) {
                      $sq->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('customer_code', 'like', "%{$search}%");
                  });
            });
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by customer
        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }
        
        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('return_date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->where('return_date', '<=', $request->date_to);
        }
        
        $customerReturns = $query->latest('return_date')->paginate(20);
        
        // Calculate total value in LKR
        $totalValueInLKR = 0;
        foreach ($customerReturns as $return) {
            $convertedAmount = $this->currencyService->convertAmount(
                $return->total_amount,
                $return->currency->code,
                'LKR'
            );
            $totalValueInLKR += $convertedAmount;
        }
        
        // Get filter options
        $customers = Customer::where('is_active', true)->get();
        $statuses = ['pending', 'approved', 'rejected', 'processed', 'refunded'];
        
        return view('customer-returns.index', compact('customerReturns', 'customers', 'statuses', 'totalValueInLKR'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $customers = Customer::where('is_active', true)->get();
        $items = Item::where('is_active', true)->get();
        $currencies = Currency::where('is_active', true)->get();
        
        // Pre-select customer if provided
        $selectedCustomer = null;
        if ($request->filled('customer_id')) {
            $selectedCustomer = Customer::find($request->customer_id);
        }
        
        return view('customer-returns.create', compact('customers', 'items', 'currencies', 'selectedCustomer'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'return_date' => 'required|date',
            'currency_id' => 'required|exists:currencies,id',
            'reason' => 'required|string|max:500',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.total_price' => 'required|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();

            // Generate return number
            $returnNumber = 'CR' . date('Y') . str_pad(CustomerReturn::count() + 1, 4, '0', STR_PAD_LEFT);

            // Get currency and exchange rate
            $currency = Currency::find($validated['currency_id']);
            $exchangeRate = $currency ? $currency->exchange_rate : 1.0000;

            // Create customer return
            $customerReturn = CustomerReturn::create([
                'customer_id' => $validated['customer_id'],
                'return_number' => $returnNumber,
                'return_date' => $validated['return_date'],
                'currency_id' => $validated['currency_id'],
                'total_amount' => array_sum(array_column($validated['items'], 'total_price')),
                'status' => 'pending',
                'reason' => $validated['reason'],
                'notes' => $validated['notes'],
                'created_by' => auth()->id(),
                'exchange_rate' => $exchangeRate
            ]);

            // Create transaction items
            foreach ($validated['items'] as $item) {
                TransactionItem::create([
                    'transaction_type' => 'App\Models\CustomerReturn',
                    'transaction_id' => $customerReturn->id,
                    'item_id' => $item['item_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['total_price']
                ]);
            }

            DB::commit();

            return redirect()->route('customer-returns.show', $customerReturn)
                ->with('success', 'Customer return created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Failed to create customer return: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CustomerReturn $customerReturn)
    {
        $customerReturn->load(['customer', 'currency', 'transactionItems.item']);
        
        return view('customer-returns.show', compact('customerReturn'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CustomerReturn $customerReturn)
    {
        if ($customerReturn->status !== 'pending') {
            return redirect()->route('customer-returns.show', $customerReturn)
                ->with('error', 'Only pending returns can be edited.');
        }

        $customers = Customer::where('is_active', true)->get();
        $items = Item::where('is_active', true)->get();
        $currencies = Currency::where('is_active', true)->get();
        $customerReturn->load('transactionItems.item');
        
        return view('customer-returns.edit', compact('customerReturn', 'customers', 'items', 'currencies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CustomerReturn $customerReturn)
    {
        if ($customerReturn->status !== 'pending') {
            return redirect()->route('customer-returns.show', $customerReturn)
                ->with('error', 'Only pending returns can be edited.');
        }

        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'return_date' => 'required|date',
            'currency_id' => 'required|exists:currencies,id',
            'reason' => 'required|string|max:500',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.total_price' => 'required|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();

            // Get new currency and exchange rate
            $newCurrency = Currency::find($validated['currency_id']);
            $newExchangeRate = $newCurrency ? $newCurrency->exchange_rate : 1.0000;
            
            // Check if currency changed
            $currencyChanged = $customerReturn->currency_id != $validated['currency_id'];
            $oldCurrency = $customerReturn->currency;

            // Update customer return
            $customerReturn->update([
                'customer_id' => $validated['customer_id'],
                'return_date' => $validated['return_date'],
                'currency_id' => $validated['currency_id'],
                'total_amount' => array_sum(array_column($validated['items'], 'total_price')),
                'reason' => $validated['reason'],
                'notes' => $validated['notes'],
                'updated_by' => auth()->id(),
                'exchange_rate' => $newExchangeRate
            ]);

            // Delete existing transaction items
            $customerReturn->transactionItems()->delete();

            // Create new transaction items
            foreach ($validated['items'] as $item) {
                $unitPrice = $item['unit_price'];
                $totalPrice = $item['total_price'];
                
                // Convert prices if currency changed
                if ($currencyChanged && $oldCurrency) {
                    $unitPrice = $this->currencyService->convertAmount(
                        $unitPrice, 
                        $oldCurrency->code, 
                        $newCurrency->code
                    );
                    $totalPrice = $this->currencyService->convertAmount(
                        $totalPrice, 
                        $oldCurrency->code, 
                        $newCurrency->code
                    );
                }
                
                TransactionItem::create([
                    'transaction_type' => 'App\Models\CustomerReturn',
                    'transaction_id' => $customerReturn->id,
                    'item_id' => $item['item_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice
                ]);
            }

            DB::commit();

            return redirect()->route('customer-returns.show', $customerReturn)
                ->with('success', 'Customer return updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Failed to update customer return: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomerReturn $customerReturn)
    {
        if ($customerReturn->status !== 'pending') {
            return redirect()->route('customer-returns.index')
                ->with('error', 'Only pending returns can be deleted.');
        }

        try {
            DB::beginTransaction();

            // Delete transaction items
            $customerReturn->transactionItems()->delete();
            
            // Delete customer return
            $customerReturn->delete();

            DB::commit();

            return redirect()->route('customer-returns.index')
                ->with('success', 'Customer return deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('customer-returns.index')
                ->with('error', 'Failed to delete customer return: ' . $e->getMessage());
        }
    }

    /**
     * Update the status of the customer return
     */
    public function updateStatus(Request $request, CustomerReturn $customerReturn)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected,processed,refunded'
        ]);

        $customerReturn->update([
            'status' => $validated['status'],
            'updated_by' => auth()->id()
        ]);

        return redirect()->route('customer-returns.show', $customerReturn)
            ->with('success', 'Customer return status updated successfully.');
    }

    /**
     * Get items for a specific customer (for AJAX)
     */
    public function getCustomerItems(Customer $customer)
    {
        // This would typically get items from customer's previous purchases
        // For now, return all active items
        $items = Item::where('is_active', true)->get();
        
        return response()->json($items);
    }

    /**
     * Export customer return as PDF
     */
    public function exportPdf(CustomerReturn $customerReturn)
    {
        $customerReturn->load(['customer', 'currency', 'transactionItems.item']);
        
        // This would generate and return a PDF
        // Implementation depends on your PDF library (e.g., DomPDF, TCPDF)
        
        return view('customer-returns.pdf', compact('customerReturn'));
    }

    /**
     * Get exchange rates for currency conversion
     */
    public function getExchangeRates()
    {
        $currencies = Currency::where('is_active', true)
            ->select('id', 'code', 'exchange_rate')
            ->get()
            ->keyBy('id');
        
        return response()->json($currencies);
    }
}
