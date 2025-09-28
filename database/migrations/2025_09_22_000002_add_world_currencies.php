<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add more world currencies for display purposes
        $currencies = [
            ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€', 'exchange_rate' => 0.0027, 'is_base_currency' => false, 'is_active' => true],
            ['code' => 'GBP', 'name' => 'British Pound', 'symbol' => '£', 'exchange_rate' => 0.0024, 'is_base_currency' => false, 'is_active' => true],
            ['code' => 'JPY', 'name' => 'Japanese Yen', 'symbol' => '¥', 'exchange_rate' => 0.46, 'is_base_currency' => false, 'is_active' => true],
            ['code' => 'AUD', 'name' => 'Australian Dollar', 'symbol' => 'A$', 'exchange_rate' => 0.0046, 'is_base_currency' => false, 'is_active' => true],
            ['code' => 'CAD', 'name' => 'Canadian Dollar', 'symbol' => 'C$', 'exchange_rate' => 0.0042, 'is_base_currency' => false, 'is_active' => true],
            ['code' => 'CHF', 'name' => 'Swiss Franc', 'symbol' => 'CHF', 'exchange_rate' => 0.0027, 'is_base_currency' => false, 'is_active' => true],
            ['code' => 'CNY', 'name' => 'Chinese Yuan', 'symbol' => '¥', 'exchange_rate' => 0.022, 'is_base_currency' => false, 'is_active' => true],
            ['code' => 'INR', 'name' => 'Indian Rupee', 'symbol' => '₹', 'exchange_rate' => 0.26, 'is_base_currency' => false, 'is_active' => true],
            ['code' => 'SGD', 'name' => 'Singapore Dollar', 'symbol' => 'S$', 'exchange_rate' => 0.0041, 'is_base_currency' => false, 'is_active' => true],
            ['code' => 'AED', 'name' => 'UAE Dirham', 'symbol' => 'د.إ', 'exchange_rate' => 0.011, 'is_base_currency' => false, 'is_active' => true],
            ['code' => 'SAR', 'name' => 'Saudi Riyal', 'symbol' => '﷼', 'exchange_rate' => 0.012, 'is_base_currency' => false, 'is_active' => true],
            ['code' => 'QAR', 'name' => 'Qatar Riyal', 'symbol' => '﷼', 'exchange_rate' => 0.011, 'is_base_currency' => false, 'is_active' => true],
            ['code' => 'KWD', 'name' => 'Kuwaiti Dinar', 'symbol' => 'د.ك', 'exchange_rate' => 0.00095, 'is_base_currency' => false, 'is_active' => true],
            ['code' => 'BHD', 'name' => 'Bahraini Dinar', 'symbol' => 'د.ب', 'exchange_rate' => 0.0012, 'is_base_currency' => false, 'is_active' => true],
            ['code' => 'OMR', 'name' => 'Omani Rial', 'symbol' => '﷼', 'exchange_rate' => 0.0012, 'is_base_currency' => false, 'is_active' => true],
        ];

        foreach ($currencies as $currency) {
            \App\Models\Currency::updateOrCreate(
                ['code' => $currency['code']],
                $currency
            );
        }
    }

    public function down(): void
    {
        // Remove the additional currencies
        $codes = ['EUR', 'GBP', 'JPY', 'AUD', 'CAD', 'CHF', 'CNY', 'INR', 'SGD', 'AED', 'SAR', 'QAR', 'KWD', 'BHD', 'OMR'];
        \App\Models\Currency::whereIn('code', $codes)->delete();
    }
};
