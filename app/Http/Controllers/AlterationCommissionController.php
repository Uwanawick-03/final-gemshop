<?php

namespace App\Http\Controllers;

use App\Models\AlterationCommission;
use App\Models\Customer;
use App\Models\SalesAssistant;
use App\Models\Craftsman;
use App\Models\Item;
use App\Models\Currency;
use App\Services\CurrencyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AlterationCommissionController extends Controller
{
    protected $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    public function index(Request $request)
    {
        $query = AlterationCommission::with(['customer', 'salesAssistant', 'craftsman', 'item', 'currency', 'createdBy']);

        // Apply filters
        // if ($request->filled('status')) {
        //     $query->where('status', $request->status);
        // } // Column doesn't exist yet

        // if ($request->filled('payment_status')) {
        //     $query->where('payment_status', $request->payment_status);
        // } // Column doesn't exist yet

        // if ($request->filled('alteration_type')) {
        //     $query->where('alteration_type', $request->alteration_type);
        // } // Column doesn't exist yet

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->filled('craftsman_id')) {
            $query->where('craftsman_id', $request->craftsman_id);
        }

        if ($request->filled('sales_assistant_id')) {
            $query->where('sales_assistant_id', $request->sales_assistant_id);
        }

        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('commission_number', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($customerQuery) use ($search) {
                      $customerQuery->where('first_name', 'like', "%{$search}%")
                                   ->orWhere('last_name', 'like', "%{$search}%")
                                   ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('item', function($itemQuery) use ($search) {
                      $itemQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('item_code', 'like', "%{$search}%");
                  });
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        $alterationCommissions = $query->paginate(15)->withQueryString();

        // Get filter options
        $customers = Customer::select('id', 'first_name', 'last_name', 'email')->orderBy('first_name')->get();
        $salesAssistants = SalesAssistant::select('id', 'first_name', 'last_name')->orderBy('first_name')->get();
        $craftsmen = Craftsman::select('id', 'first_name', 'last_name')->orderBy('first_name')->get();
        $items = Item::select('id', 'name', 'item_code')->orderBy('name')->get();
        // $statuses = AlterationCommission::select('status')->distinct()->pluck('status'); // Column doesn't exist yet
        // $paymentStatuses = AlterationCommission::select('payment_status')->distinct()->pluck('payment_status'); // Column doesn't exist yet
        // $alterationTypes = AlterationCommission::select('alteration_type')->distinct()->pluck('alteration_type'); // Column doesn't exist yet
        
        // Provide default values for now
        $statuses = collect(['pending', 'in_progress', 'completed', 'cancelled']);
        $paymentStatuses = collect(['unpaid', 'partial', 'paid']);
        $alterationTypes = collect(['resize', 'repair', 'polish', 'engrave', 'design_change', 'stone_setting', 'cleaning', 'other']);

        // Calculate statistics
        $totalCommissions = AlterationCommission::count();
        // $pendingCommissions = AlterationCommission::where('status', 'pending')->count(); // Column doesn't exist yet
        // $inProgressCommissions = AlterationCommission::where('status', 'in_progress')->count(); // Column doesn't exist yet
        // $completedCommissions = AlterationCommission::where('status', 'completed')->count(); // Column doesn't exist yet
        // $cancelledCommissions = AlterationCommission::where('status', 'cancelled')->count(); // Column doesn't exist yet
        // $overdueCommissions = AlterationCommission::where('status', 'in_progress')
        //     ->where('start_date', '<', now()->subDays(7))
        //     ->count(); // Columns don't exist yet

        // Provide default values for now
        $pendingCommissions = 0;
        $inProgressCommissions = 0;
        $completedCommissions = 0;
        $cancelledCommissions = 0;
        $overdueCommissions = 0;

        // Calculate total commission amounts
        // $totalCommissionAmount = AlterationCommission::sum('commission_amount'); // Column doesn't exist yet
        // $paidAmount = AlterationCommission::sum('paid_amount'); // Column doesn't exist yet
        // $unpaidAmount = $totalCommissionAmount - $paidAmount; // Columns don't exist yet
        
        // Provide default values for now
        $totalCommissionAmount = 0;
        $paidAmount = 0;
        $unpaidAmount = 0;

        // Calculate total value in LKR (using all commissions, not just paginated ones)
        $allCommissions = AlterationCommission::with('currency')->get();
        $totalValueInLKR = 0;
        foreach ($allCommissions as $commission) {
            $convertedAmount = $this->currencyService->convertAmount(
                $commission->commission_amount,
                $commission->currency->code,
                'LKR'
            );
            $totalValueInLKR += $convertedAmount;
        }

        return view('alteration-commissions.index', compact(
            'alterationCommissions',
            'customers',
            'salesAssistants',
            'craftsmen',
            'items',
            'statuses',
            'paymentStatuses',
            'alterationTypes',
            'totalCommissions',
            'pendingCommissions',
            'inProgressCommissions',
            'completedCommissions',
            'cancelledCommissions',
            'overdueCommissions',
            'totalCommissionAmount',
            'paidAmount',
            'unpaidAmount',
            'totalValueInLKR'
        ));
    }

    public function create()
    {
        $customers = Customer::select('id', 'first_name', 'last_name', 'email')->orderBy('first_name')->get();
        $salesAssistants = SalesAssistant::select('id', 'first_name', 'last_name')->orderBy('first_name')->get();
        $craftsmen = Craftsman::select('id', 'first_name', 'last_name')->orderBy('first_name')->get();
        $items = Item::select('id', 'name', 'item_code')->orderBy('name')->get();
        $currencies = Currency::select('id', 'code', 'name')->orderBy('code')->get();

        $alterationTypes = [
            'resize' => 'Resize',
            'repair' => 'Repair',
            'polish' => 'Polish',
            'engrave' => 'Engrave',
            'design_change' => 'Design Change',
            'stone_setting' => 'Stone Setting',
            'cleaning' => 'Cleaning',
            'other' => 'Other'
        ];

        return view('alteration-commissions.create', compact(
            'customers',
            'salesAssistants',
            'craftsmen',
            'items',
            'currencies',
            'alterationTypes'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'sales_assistant_id' => 'nullable|exists:sales_assistants,id',
            'craftsman_id' => 'nullable|exists:craftsmen,id',
            'item_id' => 'nullable|exists:items,id',
            // 'commission_date' => 'required|date', // Column doesn't exist yet
            'alteration_type' => 'required|in:resize,repair,polish,engrave,design_change,stone_setting,cleaning,other',
            'description' => 'nullable|string|max:1000',
            'commission_amount' => 'required|numeric|min:0.01',
            'currency_id' => 'required|exists:currencies,id',
            'start_date' => 'nullable|date',
            'notes' => 'nullable|string|max:1000'
        ]);

        DB::beginTransaction();
        try {
            // Generate commission number
            $commissionNumber = 'AC-' . date('Y') . '-' . str_pad(AlterationCommission::count() + 1, 4, '0', STR_PAD_LEFT);

            // Get exchange rate
            $currency = Currency::findOrFail($request->currency_id);
            $exchangeRate = $this->currencyService->getExchangeRate($currency->code, 'LKR');

            $alterationCommission = AlterationCommission::create([
                'customer_id' => $request->customer_id,
                'sales_assistant_id' => $request->sales_assistant_id,
                'craftsman_id' => $request->craftsman_id,
                'item_id' => $request->item_id,
                'commission_number' => $commissionNumber,
                // 'commission_date' => $request->commission_date, // Column doesn't exist yet
                'alteration_type' => $request->alteration_type,
                'description' => $request->description,
                'commission_amount' => $request->commission_amount,
                'currency_id' => $request->currency_id,
                'exchange_rate' => $exchangeRate,
                'status' => 'pending',
                'start_date' => $request->start_date,
                'payment_status' => 'unpaid',
                'notes' => $request->notes,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id()
            ]);

            DB::commit();

            return redirect()->route('alteration-commissions.index')
                ->with('success', 'Alteration commission created successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Failed to create alteration commission: ' . $e->getMessage()]);
        }
    }

    public function show(AlterationCommission $alterationCommission)
    {
        $alterationCommission->load(['customer', 'salesAssistant', 'craftsman', 'item', 'currency', 'createdBy', 'updatedBy']);

        return view('alteration-commissions.show', compact('alterationCommission'));
    }

    public function edit(AlterationCommission $alterationCommission)
    {
        if ($alterationCommission->status === 'completed') {
            return redirect()->route('alteration-commissions.show', $alterationCommission)
                ->with('error', 'Cannot edit completed commissions.');
        }

        $customers = Customer::select('id', 'first_name', 'last_name', 'email')->orderBy('first_name')->get();
        $salesAssistants = SalesAssistant::select('id', 'first_name', 'last_name')->orderBy('first_name')->get();
        $craftsmen = Craftsman::select('id', 'first_name', 'last_name')->orderBy('first_name')->get();
        $items = Item::select('id', 'name', 'item_code')->orderBy('name')->get();
        $currencies = Currency::select('id', 'code', 'name')->orderBy('code')->get();

        $alterationTypes = [
            'resize' => 'Resize',
            'repair' => 'Repair',
            'polish' => 'Polish',
            'engrave' => 'Engrave',
            'design_change' => 'Design Change',
            'stone_setting' => 'Stone Setting',
            'cleaning' => 'Cleaning',
            'other' => 'Other'
        ];

        return view('alteration-commissions.edit', compact(
            'alterationCommission',
            'customers',
            'salesAssistants',
            'craftsmen',
            'items',
            'currencies',
            'alterationTypes'
        ));
    }

    public function update(Request $request, AlterationCommission $alterationCommission)
    {
        if ($alterationCommission->status === 'completed') {
            return redirect()->route('alteration-commissions.show', $alterationCommission)
                ->with('error', 'Cannot edit completed commissions.');
        }

        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'sales_assistant_id' => 'nullable|exists:sales_assistants,id',
            'craftsman_id' => 'nullable|exists:craftsmen,id',
            'item_id' => 'nullable|exists:items,id',
            // 'commission_date' => 'required|date', // Column doesn't exist yet
            'alteration_type' => 'required|in:resize,repair,polish,engrave,design_change,stone_setting,cleaning,other',
            'description' => 'nullable|string|max:1000',
            'commission_amount' => 'required|numeric|min:0.01',
            'currency_id' => 'required|exists:currencies,id',
            'start_date' => 'nullable|date',
            'notes' => 'nullable|string|max:1000'
        ]);

        DB::beginTransaction();
        try {
            // Get exchange rate
            $currency = Currency::findOrFail($request->currency_id);
            $exchangeRate = $this->currencyService->getExchangeRate($currency->code, 'LKR');

            $alterationCommission->update([
                'customer_id' => $request->customer_id,
                'sales_assistant_id' => $request->sales_assistant_id,
                'craftsman_id' => $request->craftsman_id,
                'item_id' => $request->item_id,
                // 'commission_date' => $request->commission_date, // Column doesn't exist yet
                'alteration_type' => $request->alteration_type,
                'description' => $request->description,
                'commission_amount' => $request->commission_amount,
                'currency_id' => $request->currency_id,
                'exchange_rate' => $exchangeRate,
                'start_date' => $request->start_date,
                'notes' => $request->notes,
                'updated_by' => auth()->id()
            ]);

            DB::commit();

            return redirect()->route('alteration-commissions.index')
                ->with('success', 'Alteration commission updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Failed to update alteration commission: ' . $e->getMessage()]);
        }
    }

    public function destroy(AlterationCommission $alterationCommission)
    {
        if ($alterationCommission->status === 'completed') {
            return redirect()->route('alteration-commissions.index')
                ->with('error', 'Cannot delete completed commissions.');
        }

        DB::beginTransaction();
        try {
            $alterationCommission->delete();

            DB::commit();

            return redirect()->route('alteration-commissions.index')
                ->with('success', 'Alteration commission deleted successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Failed to delete alteration commission: ' . $e->getMessage()]);
        }
    }

    public function updateStatus(Request $request, AlterationCommission $alterationCommission)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'completion_date' => 'nullable|date|after_or_equal:start_date'
        ]);

        DB::beginTransaction();
        try {
            $updateData = [
                'status' => $request->status,
                'updated_by' => auth()->id()
            ];

            if ($request->status === 'completed' && $request->completion_date) {
                $updateData['completion_date'] = $request->completion_date;
            }

            $alterationCommission->update($updateData);

            DB::commit();

            return redirect()->route('alteration-commissions.show', $alterationCommission)
                ->with('success', 'Commission status updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Failed to update status: ' . $e->getMessage()]);
        }
    }

    public function updatePayment(Request $request, AlterationCommission $alterationCommission)
    {
        $request->validate([
            'paid_amount' => 'required|numeric|min:0.01|max:' . $alterationCommission->commission_amount,
            'payment_date' => 'required|date'
        ]);

        DB::beginTransaction();
        try {
            $paidAmount = $request->paid_amount;
            $totalAmount = $alterationCommission->commission_amount;
            $currentPaid = $alterationCommission->paid_amount ?? 0;
            $newTotalPaid = $currentPaid + $paidAmount;

            $paymentStatus = 'unpaid';
            if ($newTotalPaid >= $totalAmount) {
                $paymentStatus = 'paid';
            } elseif ($newTotalPaid > 0) {
                $paymentStatus = 'partial';
            }

            $alterationCommission->update([
                'paid_amount' => $newTotalPaid,
                'payment_status' => $paymentStatus,
                'payment_date' => $request->payment_date,
                'updated_by' => auth()->id()
            ]);

            DB::commit();

            return redirect()->route('alteration-commissions.show', $alterationCommission)
                ->with('success', 'Payment updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Failed to update payment: ' . $e->getMessage()]);
        }
    }

    public function bulkStatusUpdate(Request $request)
    {
        $request->validate([
            'commission_ids' => 'required|array|min:1',
            'commission_ids.*' => 'exists:alteration_commissions,id',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'completion_date' => 'nullable|date'
        ]);

        DB::beginTransaction();
        try {
            $commissions = AlterationCommission::whereIn('id', $request->commission_ids)->get();

            foreach ($commissions as $commission) {
                $updateData = [
                    'status' => $request->status,
                    'updated_by' => auth()->id()
                ];

                if ($request->status === 'completed' && $request->completion_date) {
                    $updateData['completion_date'] = $request->completion_date;
                }

                $commission->update($updateData);
            }

            DB::commit();

            return redirect()->route('alteration-commissions.index')
                ->with('success', 'Bulk status update completed successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Failed to update statuses: ' . $e->getMessage()]);
        }
    }

    public function exportPdf(AlterationCommission $alterationCommission)
    {
        $alterationCommission->load(['customer', 'salesAssistant', 'craftsman', 'item', 'currency', 'createdBy']);

        $pdf = \PDF::loadView('alteration-commissions.pdf', compact('alterationCommission'));
        
        return $pdf->download('alteration-commission-' . $alterationCommission->commission_number . '.pdf');
    }

    public function getExchangeRates(Request $request)
    {
        $fromCurrency = $request->get('from', 'LKR');
        $toCurrency = $request->get('to', 'LKR');
        
        $rate = $this->currencyService->getExchangeRate($fromCurrency, $toCurrency);
        
        return response()->json(['rate' => $rate]);
    }
}
