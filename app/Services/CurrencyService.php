<?php

namespace App\Services;

use App\Models\Currency;
use Illuminate\Support\Facades\Cache;

class CurrencyService
{
    /**
     * Get all active currencies
     */
    public function getActiveCurrencies()
    {
        return Cache::remember('active_currencies', 3600, function () {
            return Currency::where('is_active', true)->orderBy('code')->get();
        });
    }

    /**
     * Get base currency (LKR)
     */
    public function getBaseCurrency()
    {
        return Cache::remember('base_currency', 3600, function () {
            return Currency::where('is_base_currency', true)->first();
        });
    }

    /**
     * Convert amount from LKR to target currency
     */
    public function convertFromLKR($amount, $targetCurrencyCode)
    {
        $targetCurrency = Currency::where('code', $targetCurrencyCode)->first();
        
        if (!$targetCurrency) {
            return $amount;
        }

        return round($amount * $targetCurrency->exchange_rate, 2);
    }

    /**
     * Convert amount from target currency to LKR
     */
    public function convertToLKR($amount, $sourceCurrencyCode)
    {
        $sourceCurrency = Currency::where('code', $sourceCurrencyCode)->first();
        
        if (!$sourceCurrency) {
            return $amount;
        }

        return round($amount / $sourceCurrency->exchange_rate, 2);
    }

    /**
     * Format amount with currency symbol
     */
    public function formatAmount($amount, $currencyCode)
    {
        $currency = Currency::where('code', $currencyCode)->first();
        
        if (!$currency) {
            return number_format($amount, 2);
        }

        return $currency->symbol . ' ' . number_format($amount, 2);
    }

    /**
     * Get currency by code
     */
    public function getCurrencyByCode($code)
    {
        return Currency::where('code', $code)->first();
    }

    /**
     * Convert amount between any two currencies
     */
    public function convertAmount($amount, $fromCurrencyCode, $toCurrencyCode)
    {
        if ($fromCurrencyCode === $toCurrencyCode) {
            return $amount;
        }

        $fromCurrency = Currency::where('code', $fromCurrencyCode)->first();
        $toCurrency = Currency::where('code', $toCurrencyCode)->first();

        if (!$fromCurrency || !$toCurrency) {
            return $amount;
        }

        // Convert to LKR first, then to target currency
        // Exchange rates are stored as "1 unit of currency = X LKR"
        $lkrAmount = $amount * $fromCurrency->exchange_rate;
        return round($lkrAmount / $toCurrency->exchange_rate, 2);
    }

    /**
     * Update exchange rates (for future API integration)
     */
    public function updateExchangeRates($rates)
    {
        foreach ($rates as $code => $rate) {
            Currency::where('code', $code)->update(['exchange_rate' => $rate]);
        }
        
        // Clear cache
        Cache::forget('active_currencies');
        Cache::forget('base_currency');
    }
}
