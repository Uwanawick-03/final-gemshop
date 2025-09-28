<?php

namespace App\Console\Commands;

use App\Services\CurrencyService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UpdateCurrencyRates extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'currency:update-rates {--api=exchangerate : API provider (exchangerate, fixer, currencylayer)}';

    /**
     * The console command description.
     */
    protected $description = 'Update currency exchange rates from external API';

    protected $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        parent::__construct();
        $this->currencyService = $currencyService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $api = $this->option('api');
        
        $this->info("Updating currency rates using {$api} API...");
        
        try {
            $rates = $this->fetchRatesFromAPI($api);
            
            if ($rates) {
                $this->currencyService->updateExchangeRates($rates);
                $this->info('Currency rates updated successfully!');
                
                // Log the update
                Log::info('Currency rates updated', [
                    'api' => $api,
                    'rates_count' => count($rates),
                    'updated_at' => now()
                ]);
            } else {
                $this->error('Failed to fetch currency rates from API');
                return 1;
            }
        } catch (\Exception $e) {
            $this->error('Error updating currency rates: ' . $e->getMessage());
            Log::error('Currency rate update failed', [
                'error' => $e->getMessage(),
                'api' => $api
            ]);
            return 1;
        }
        
        return 0;
    }

    /**
     * Fetch rates from specified API
     */
    private function fetchRatesFromAPI($api)
    {
        switch ($api) {
            case 'exchangerate':
                return $this->fetchFromExchangeRateAPI();
            case 'fixer':
                return $this->fetchFromFixerAPI();
            case 'currencylayer':
                return $this->fetchFromCurrencyLayerAPI();
            default:
                throw new \InvalidArgumentException("Unsupported API: {$api}");
        }
    }

    /**
     * Fetch rates from ExchangeRate-API (free tier)
     */
    private function fetchFromExchangeRateAPI()
    {
        // ExchangeRate-API free endpoint (no API key required)
        $response = Http::get('https://api.exchangerate-api.com/v4/latest/LKR');
        
        if ($response->successful()) {
            $data = $response->json();
            $rates = [];
            
            // Convert from LKR to other currencies (inverse rates)
            foreach ($data['rates'] as $currency => $rate) {
                if ($currency !== 'LKR') {
                    // Convert to our format: how many LKR = 1 unit of currency
                    $rates[$currency] = round(1 / $rate, 4);
                }
            }
            
            return $rates;
        }
        
        throw new \Exception('Failed to fetch from ExchangeRate-API');
    }

    /**
     * Fetch rates from Fixer.io (requires API key)
     */
    private function fetchFromFixerAPI()
    {
        $apiKey = env('FIXER_API_KEY');
        
        if (!$apiKey) {
            throw new \Exception('FIXER_API_KEY not set in environment');
        }
        
        $response = Http::get('http://data.fixer.io/api/latest', [
            'access_key' => $apiKey,
            'base' => 'LKR',
            'symbols' => 'USD,EUR,GBP,INR,AED,CHF,CNY,JPY,AUD,CAD'
        ]);
        
        if ($response->successful()) {
            $data = $response->json();
            
            if ($data['success']) {
                $rates = [];
                foreach ($data['rates'] as $currency => $rate) {
                    $rates[$currency] = round(1 / $rate, 4);
                }
                return $rates;
            }
        }
        
        throw new \Exception('Failed to fetch from Fixer API');
    }

    /**
     * Fetch rates from CurrencyLayer (requires API key)
     */
    private function fetchFromCurrencyLayerAPI()
    {
        $apiKey = env('CURRENCY_LAYER_API_KEY');
        
        if (!$apiKey) {
            throw new \Exception('CURRENCY_LAYER_API_KEY not set in environment');
        }
        
        $response = Http::get('http://api.currencylayer.com/live', [
            'access_key' => $apiKey,
            'source' => 'LKR',
            'currencies' => 'USD,EUR,GBP,INR,AED,CHF,CNY,JPY,AUD,CAD'
        ]);
        
        if ($response->successful()) {
            $data = $response->json();
            
            if ($data['success']) {
                $rates = [];
                foreach ($data['quotes'] as $pair => $rate) {
                    $currency = str_replace('LKR', '', $pair);
                    $rates[$currency] = round(1 / $rate, 4);
                }
                return $rates;
            }
        }
        
        throw new \Exception('Failed to fetch from CurrencyLayer API');
    }
}
