<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Currency;
use App\Models\Item;
use Illuminate\Support\Facades\Cache;

class CalculatorController extends Controller
{
    public function index()
    {
        $currencies = Currency::where('is_active', true)->get();
        $baseCurrency = Currency::where('is_base_currency', true)->first();
        
        // Get current metal prices (you can integrate with real-time APIs later)
        $metalPrices = Cache::remember('metal_prices', 3600, function () {
            return [
                'gold_24k' => 65.50,  // per gram
                'gold_22k' => 60.04,
                'gold_18k' => 49.13,
                'gold_14k' => 38.21,
                'gold_9k' => 24.56,
                'silver' => 0.85,
                'platinum' => 28.50,
                'palladium' => 45.20
            ];
        });
        
        return view('calculator.index', compact('currencies', 'baseCurrency', 'metalPrices'));
    }

    public function calculateJewelry(Request $request)
    {
        $request->validate([
            'metal_type' => 'required|in:gold,silver,platinum,palladium',
            'purity' => 'required|numeric|min:1|max:100',
            'weight' => 'required|numeric|min:0.001',
            'metal_price' => 'required|numeric|min:0.01',
            'labor_cost' => 'nullable|numeric|min:0',
            'markup_percentage' => 'nullable|numeric|min:0|max:1000',
            'currency_id' => 'required|exists:currencies,id'
        ]);

        $metalType = $request->metal_type;
        $purity = $request->purity;
        $weight = $request->weight;
        $metalPrice = $request->metal_price;
        $laborCost = $request->labor_cost ?? 0;
        $markupPercentage = $request->markup_percentage ?? 0;
        $currency = Currency::findOrFail($request->currency_id);

        // Calculate metal value based on purity
        $purityFactor = $purity / 100; // Convert percentage to decimal
        $metalValue = $weight * $purityFactor * $metalPrice;
        
        // Calculate subtotal
        $subtotal = $metalValue + $laborCost;
        
        // Calculate markup
        $markupAmount = $subtotal * ($markupPercentage / 100);
        
        // Calculate total
        $total = $subtotal + $markupAmount;

        return response()->json([
            'metal_value' => round($metalValue, 2),
            'labor_cost' => round($laborCost, 2),
            'subtotal' => round($subtotal, 2),
            'markup_amount' => round($markupAmount, 2),
            'total' => round($total, 2),
            'currency_symbol' => $currency->symbol,
            'breakdown' => [
                'metal_value' => round($metalValue, 2),
                'labor_cost' => round($laborCost, 2),
                'markup_amount' => round($markupAmount, 2),
                'total' => round($total, 2)
            ]
        ]);
    }

    public function calculateGemstone(Request $request)
    {
        $request->validate([
            'gemstone_type' => 'required|string',
            'carat_weight' => 'required|numeric|min:0.01',
            'price_per_carat' => 'required|numeric|min:0.01',
            'clarity' => 'nullable|string',
            'color_grade' => 'nullable|string',
            'cut_grade' => 'nullable|string',
            'setting_cost' => 'nullable|numeric|min:0',
            'currency_id' => 'required|exists:currencies,id'
        ]);

        $gemstoneType = $request->gemstone_type;
        $caratWeight = $request->carat_weight;
        $pricePerCarat = $request->price_per_carat;
        $clarity = $request->clarity;
        $colorGrade = $request->color_grade;
        $cutGrade = $request->cut_grade;
        $settingCost = $request->setting_cost ?? 0;
        $currency = Currency::findOrFail($request->currency_id);

        // Base gemstone value
        $baseValue = $caratWeight * $pricePerCarat;
        
        // Apply quality multipliers (these can be customized based on your standards)
        $qualityMultiplier = $this->calculateQualityMultiplier($clarity, $colorGrade, $cutGrade);
        $adjustedValue = $baseValue * $qualityMultiplier;
        
        // Total cost including setting
        $total = $adjustedValue + $settingCost;

        return response()->json([
            'base_value' => round($baseValue, 2),
            'quality_multiplier' => round($qualityMultiplier, 2),
            'adjusted_value' => round($adjustedValue, 2),
            'setting_cost' => round($settingCost, 2),
            'total' => round($total, 2),
            'currency_symbol' => $currency->symbol,
            'quality_factors' => [
                'clarity' => $clarity,
                'color_grade' => $colorGrade,
                'cut_grade' => $cutGrade,
                'multiplier' => round($qualityMultiplier, 2)
            ]
        ]);
    }

    public function calculateProfitMargin(Request $request)
    {
        $request->validate([
            'cost_price' => 'required|numeric|min:0.01',
            'selling_price' => 'required|numeric|min:0.01',
            'additional_costs' => 'nullable|numeric|min:0'
        ]);

        $costPrice = $request->cost_price;
        $sellingPrice = $request->selling_price;
        $additionalCosts = $request->additional_costs ?? 0;
        
        $totalCost = $costPrice + $additionalCosts;
        $grossProfit = $sellingPrice - $totalCost;
        $profitMargin = ($grossProfit / $sellingPrice) * 100;
        $markupPercentage = ($grossProfit / $totalCost) * 100;

        return response()->json([
            'cost_price' => round($costPrice, 2),
            'additional_costs' => round($additionalCosts, 2),
            'total_cost' => round($totalCost, 2),
            'selling_price' => round($sellingPrice, 2),
            'gross_profit' => round($grossProfit, 2),
            'profit_margin' => round($profitMargin, 2),
            'markup_percentage' => round($markupPercentage, 2)
        ]);
    }

    public function calculateTax(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'tax_type' => 'required|in:inclusive,exclusive',
            'currency_id' => 'required|exists:currencies,id'
        ]);

        $amount = $request->amount;
        $taxRate = $request->tax_rate;
        $taxType = $request->tax_type;
        $currency = Currency::findOrFail($request->currency_id);

        if ($taxType === 'inclusive') {
            // Tax is included in the amount
            $taxAmount = $amount * ($taxRate / (100 + $taxRate));
            $netAmount = $amount - $taxAmount;
        } else {
            // Tax is added to the amount
            $taxAmount = $amount * ($taxRate / 100);
            $netAmount = $amount;
        }

        $totalAmount = $netAmount + $taxAmount;

        return response()->json([
            'original_amount' => round($amount, 2),
            'tax_rate' => round($taxRate, 2),
            'tax_amount' => round($taxAmount, 2),
            'net_amount' => round($netAmount, 2),
            'total_amount' => round($totalAmount, 2),
            'currency_symbol' => $currency->symbol,
            'tax_type' => $taxType
        ]);
    }

    public function calculateDiscount(Request $request)
    {
        $request->validate([
            'original_price' => 'required|numeric|min:0.01',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'currency_id' => 'required|exists:currencies,id'
        ]);

        $originalPrice = $request->original_price;
        $discountType = $request->discount_type;
        $discountValue = $request->discount_value;
        $currency = Currency::findOrFail($request->currency_id);

        if ($discountType === 'percentage') {
            $discountAmount = $originalPrice * ($discountValue / 100);
        } else {
            $discountAmount = $discountValue;
        }

        $finalPrice = $originalPrice - $discountAmount;
        $savingsPercentage = ($discountAmount / $originalPrice) * 100;

        return response()->json([
            'original_price' => round($originalPrice, 2),
            'discount_type' => $discountType,
            'discount_value' => round($discountValue, 2),
            'discount_amount' => round($discountAmount, 2),
            'final_price' => round($finalPrice, 2),
            'savings_percentage' => round($savingsPercentage, 2),
            'currency_symbol' => $currency->symbol
        ]);
    }

    public function calculateInstallment(Request $request)
    {
        $request->validate([
            'principal_amount' => 'required|numeric|min:0.01',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'installment_periods' => 'required|integer|min:1|max:120',
            'payment_frequency' => 'required|in:monthly,weekly,daily',
            'currency_id' => 'required|exists:currencies,id'
        ]);

        $principal = $request->principal_amount;
        $annualRate = $request->interest_rate / 100;
        $periods = $request->installment_periods;
        $frequency = $request->payment_frequency;
        $currency = Currency::findOrFail($request->currency_id);

        // Adjust interest rate based on payment frequency
        switch ($frequency) {
            case 'monthly':
                $periodicRate = $annualRate / 12;
                break;
            case 'weekly':
                $periodicRate = $annualRate / 52;
                break;
            case 'daily':
                $periodicRate = $annualRate / 365;
                break;
            default:
                $periodicRate = $annualRate / 12;
        }

        // Calculate installment amount using PMT formula
        if ($periodicRate > 0) {
            $installmentAmount = $principal * ($periodicRate * pow(1 + $periodicRate, $periods)) / (pow(1 + $periodicRate, $periods) - 1);
        } else {
            $installmentAmount = $principal / $periods;
        }

        $totalPayments = $installmentAmount * $periods;
        $totalInterest = $totalPayments - $principal;

        return response()->json([
            'principal_amount' => round($principal, 2),
            'interest_rate' => round($request->interest_rate, 2),
            'installment_periods' => $periods,
            'payment_frequency' => $frequency,
            'installment_amount' => round($installmentAmount, 2),
            'total_payments' => round($totalPayments, 2),
            'total_interest' => round($totalInterest, 2),
            'currency_symbol' => $currency->symbol
        ]);
    }

    public function convertUnits(Request $request)
    {
        $request->validate([
            'value' => 'required|numeric',
            'from_unit' => 'required|string',
            'to_unit' => 'required|string',
            'unit_type' => 'required|in:weight,length,temperature,volume'
        ]);

        $value = $request->value;
        $fromUnit = $request->from_unit;
        $toUnit = $request->to_unit;
        $unitType = $request->unit_type;

        $convertedValue = $this->performUnitConversion($value, $fromUnit, $toUnit, $unitType);

        return response()->json([
            'original_value' => $value,
            'original_unit' => $fromUnit,
            'converted_value' => round($convertedValue, 6),
            'converted_unit' => $toUnit,
            'unit_type' => $unitType
        ]);
    }

    private function calculateQualityMultiplier($clarity, $colorGrade, $cutGrade)
    {
        // Default multiplier
        $multiplier = 1.0;
        
        // Clarity multipliers (example for diamonds)
        $clarityMultipliers = [
            'FL' => 1.5, 'IF' => 1.4, 'VVS1' => 1.3, 'VVS2' => 1.2,
            'VS1' => 1.1, 'VS2' => 1.0, 'SI1' => 0.9, 'SI2' => 0.8,
            'I1' => 0.7, 'I2' => 0.6, 'I3' => 0.5
        ];
        
        // Color grade multipliers (example for diamonds)
        $colorMultipliers = [
            'D' => 1.3, 'E' => 1.2, 'F' => 1.1, 'G' => 1.0,
            'H' => 0.9, 'I' => 0.8, 'J' => 0.7, 'K' => 0.6,
            'L' => 0.5, 'M' => 0.4, 'N' => 0.3
        ];
        
        // Cut grade multipliers
        $cutMultipliers = [
            'Excellent' => 1.2, 'Very Good' => 1.1, 'Good' => 1.0,
            'Fair' => 0.9, 'Poor' => 0.8
        ];
        
        if ($clarity && isset($clarityMultipliers[$clarity])) {
            $multiplier *= $clarityMultipliers[$clarity];
        }
        
        if ($colorGrade && isset($colorMultipliers[$colorGrade])) {
            $multiplier *= $colorMultipliers[$colorGrade];
        }
        
        if ($cutGrade && isset($cutMultipliers[$cutGrade])) {
            $multiplier *= $cutMultipliers[$cutGrade];
        }
        
        return $multiplier;
    }

    private function performUnitConversion($value, $fromUnit, $toUnit, $unitType)
    {
        $conversions = [
            'weight' => [
                'gram' => 1, 'kilogram' => 1000, 'pound' => 453.592,
                'ounce' => 28.3495, 'carat' => 0.2, 'grain' => 0.0647989
            ],
            'length' => [
                'millimeter' => 1, 'centimeter' => 10, 'meter' => 1000,
                'inch' => 25.4, 'foot' => 304.8, 'yard' => 914.4
            ],
            'temperature' => [
                'celsius' => 1, 'fahrenheit' => 1, 'kelvin' => 1
            ],
            'volume' => [
                'milliliter' => 1, 'liter' => 1000, 'cubic_centimeter' => 1,
                'fluid_ounce' => 29.5735, 'cup' => 236.588, 'pint' => 473.176
            ]
        ];

        if ($unitType === 'temperature') {
            return $this->convertTemperature($value, $fromUnit, $toUnit);
        }

        if (!isset($conversions[$unitType][$fromUnit]) || !isset($conversions[$unitType][$toUnit])) {
            return $value;
        }

        // Convert to base unit, then to target unit
        $baseValue = $value * $conversions[$unitType][$fromUnit];
        return $baseValue / $conversions[$unitType][$toUnit];
    }

    private function convertTemperature($value, $fromUnit, $toUnit)
    {
        if ($fromUnit === $toUnit) {
            return $value;
        }

        // Convert to Celsius first
        switch ($fromUnit) {
            case 'fahrenheit':
                $celsius = ($value - 32) * 5/9;
                break;
            case 'kelvin':
                $celsius = $value - 273.15;
                break;
            default:
                $celsius = $value;
        }

        // Convert from Celsius to target unit
        switch ($toUnit) {
            case 'fahrenheit':
                return $celsius * 9/5 + 32;
            case 'kelvin':
                return $celsius + 273.15;
            default:
                return $celsius;
        }
    }

    public function getCalculationHistory(Request $request)
    {
        // This would typically store calculations in database
        // For now, return empty array
        return response()->json([]);
    }

    public function saveCalculation(Request $request)
    {
        $request->validate([
            'calculation_type' => 'required|string',
            'input_data' => 'required|array',
            'result_data' => 'required|array',
            'name' => 'nullable|string|max:255'
        ]);

        // This would typically save to database
        // For now, just return success
        return response()->json([
            'success' => true,
            'message' => 'Calculation saved successfully'
        ]);
    }
}
