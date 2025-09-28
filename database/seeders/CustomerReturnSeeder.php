<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CustomerReturn;
use App\Models\Customer;
use App\Models\Currency;
use App\Models\Item;
use App\Models\TransactionItem;

class CustomerReturnSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first customer, currency, and item
        $customer = Customer::first();
        $currency = Currency::first();
        $item = Item::first();

        if (!$customer || !$currency || !$item) {
            $this->command->error('Please seed customers, currencies, and items first.');
            return;
        }

        // Create test customer returns
        $returns = [
            [
                'customer_id' => $customer->id,
                'return_number' => 'CR2025001',
                'return_date' => now()->subDays(5)->toDateString(),
                'currency_id' => $currency->id,
                'total_amount' => 150.00,
                'status' => 'pending',
                'reason' => 'Defective item received',
                'notes' => 'Customer reported quality issues'
            ],
            [
                'customer_id' => $customer->id,
                'return_number' => 'CR2025002',
                'return_date' => now()->subDays(3)->toDateString(),
                'currency_id' => $currency->id,
                'total_amount' => 75.50,
                'status' => 'approved',
                'reason' => 'Wrong size ordered',
                'notes' => 'Customer ordered wrong ring size'
            ],
            [
                'customer_id' => $customer->id,
                'return_number' => 'CR2025003',
                'return_date' => now()->subDays(1)->toDateString(),
                'currency_id' => $currency->id,
                'total_amount' => 200.00,
                'status' => 'processed',
                'reason' => 'Customer changed mind',
                'notes' => 'Customer decided to keep different item'
            ]
        ];

        foreach ($returns as $returnData) {
            $return = CustomerReturn::create($returnData);

            // Create transaction items for each return
            TransactionItem::create([
                'transaction_type' => 'App\Models\CustomerReturn',
                'transaction_id' => $return->id,
                'item_id' => $item->id,
                'quantity' => 1.0,
                'unit_price' => $return->total_amount,
                'total_price' => $return->total_amount
            ]);
        }

        $this->command->info('Created ' . count($returns) . ' test customer returns.');
    }
}
