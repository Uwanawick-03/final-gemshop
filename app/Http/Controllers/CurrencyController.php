<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Services\CurrencyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CurrencyController extends Controller
{
    protected $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currencies = $this->currencyService->getActiveCurrencies();
        return view('currencies.index', compact('currencies'));
    }

    /**
     * Set display currency for the session
     */
    public function setDisplayCurrency(Request $request)
    {
        $request->validate([
            'currency_code' => 'required|exists:currencies,code'
        ]);

        $currency = $this->currencyService->getCurrencyByCode($request->currency_code);
        
        if ($currency && $currency->is_active) {
            Session::put('display_currency', $request->currency_code);
            
            return response()->json([
                'success' => true,
                'message' => 'Display currency set to ' . $currency->code,
                'currency' => $currency
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid currency'
        ], 400);
    }

    /**
     * Get current display currency
     */
    public function getDisplayCurrency()
    {
        $displayCurrencyCode = Session::get('display_currency', 'LKR');
        $currency = $this->currencyService->getCurrencyByCode($displayCurrencyCode);
        
        return response()->json([
            'currency' => $currency
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('currencies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:3|unique:currencies,code',
            'name' => 'required|string|max:255',
            'symbol' => 'required|string|max:10',
            'exchange_rate' => 'required|numeric|min:0',
            'is_base_currency' => 'boolean',
            'is_active' => 'boolean'
        ]);

        // If this is being set as base currency, unset any existing base currency
        if ($request->has('is_base_currency') && $request->is_base_currency) {
            Currency::where('is_base_currency', true)->update(['is_base_currency' => false]);
        }

        Currency::create($request->all());

        return redirect()->route('currencies.index')
            ->with('success', 'Currency created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Currency $currency)
    {
        return view('currencies.show', compact('currency'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Currency $currency)
    {
        return view('currencies.edit', compact('currency'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Currency $currency)
    {
        $request->validate([
            'code' => 'required|string|max:3|unique:currencies,code,' . $currency->id,
            'name' => 'required|string|max:255',
            'symbol' => 'required|string|max:10',
            'exchange_rate' => 'required|numeric|min:0',
            'is_base_currency' => 'boolean',
            'is_active' => 'boolean'
        ]);

        // If this is being set as base currency, unset any existing base currency
        if ($request->has('is_base_currency') && $request->is_base_currency) {
            Currency::where('id', '!=', $currency->id)
                   ->where('is_base_currency', true)
                   ->update(['is_base_currency' => false]);
        }

        $currency->update($request->all());

        return redirect()->route('currencies.index')
            ->with('success', 'Currency updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Currency $currency)
    {
        // Prevent deletion of base currency
        if ($currency->is_base_currency) {
            return redirect()->route('currencies.index')
                ->with('error', 'Cannot delete the base currency!');
        }

        $currency->delete();

        return redirect()->route('currencies.index')
            ->with('success', 'Currency deleted successfully!');
    }

    /**
     * Get all active currencies for dropdown
     */
    public function getActiveCurrencies()
    {
        $currencies = $this->currencyService->getActiveCurrencies();
        
        return response()->json([
            'currencies' => $currencies
        ]);
    }

    /**
     * Convert amount from LKR to display currency
     */
    public function convertAmount(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'currency_code' => 'required|exists:currencies,code'
        ]);

        $convertedAmount = $this->currencyService->convertFromLKR(
            $request->amount, 
            $request->currency_code
        );

        $currency = $this->currencyService->getCurrencyByCode($request->currency_code);
        $formattedAmount = $this->currencyService->formatAmount($convertedAmount, $request->currency_code);

        return response()->json([
            'converted_amount' => $convertedAmount,
            'formatted_amount' => $formattedAmount,
            'currency' => $currency
        ]);
    }

    /**
     * Update currency rates from external API
     */
    public function updateRatesFromAPI(Request $request)
    {
        try {
            $api = $request->input('api', 'exchangerate');
            
            // Call the artisan command
            $exitCode = \Artisan::call('currency:update-rates', [
                '--api' => $api
            ]);
            
            if ($exitCode === 0) {
                return response()->json([
                    'success' => true,
                    'message' => 'Currency rates updated successfully from ' . $api . ' API'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update currency rates'
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating currency rates: ' . $e->getMessage()
            ], 500);
        }
    }

}