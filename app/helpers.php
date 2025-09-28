<?php

if (!function_exists('displayAmount')) {
    /**
     * Display amount in the selected display currency
     *
     * @param float $lkrAmount Amount in LKR (base currency)
     * @param string|null $currencyCode Optional specific currency code
     * @return string
     */
    function displayAmount($lkrAmount, $currencyCode = null)
    {
        $currencyService = app(\App\Services\CurrencyService::class);
        
        if ($currencyCode) {
            $convertedAmount = $currencyService->convertFromLKR($lkrAmount, $currencyCode);
            return $currencyService->formatAmount($convertedAmount, $currencyCode);
        }
        
        // Use session display currency
        $displayCurrencyCode = session('display_currency', 'LKR');
        $convertedAmount = $currencyService->convertFromLKR($lkrAmount, $displayCurrencyCode);
        return $currencyService->formatAmount($convertedAmount, $displayCurrencyCode);
    }
}

if (!function_exists('getDisplayCurrency')) {
    /**
     * Get current display currency
     *
     * @return \App\Models\Currency|null
     */
    function getDisplayCurrency()
    {
        $currencyService = app(\App\Services\CurrencyService::class);
        $displayCurrencyCode = session('display_currency', 'LKR');
        return $currencyService->getCurrencyByCode($displayCurrencyCode);
    }
}

if (!function_exists('convertToLKR')) {
    /**
     * Convert amount from any currency to LKR
     *
     * @param float $amount
     * @param string $fromCurrencyCode
     * @return float
     */
    function convertToLKR($amount, $fromCurrencyCode)
    {
        $currencyService = app(\App\Services\CurrencyService::class);
        return $currencyService->convertToLKR($amount, $fromCurrencyCode);
    }
}

if (!function_exists('formatLKR')) {
    /**
     * Format amount in LKR (base currency)
     *
     * @param float $amount
     * @return string
     */
    function formatLKR($amount)
    {
        return 'Rs ' . number_format($amount, 2);
    }
}
