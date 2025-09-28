<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SupplierReturn;
use App\Models\Supplier;
use App\Models\Item;
use App\Models\TransactionItem;
use App\Models\Currency;
use App\Models\User;

class SupplierReturnSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = Supplier::where('is_active', true)->get();
        $items = Item::where('is_active', true)->get();
        $currencies = Currency::where('is_active', true)->get();
        $users = User::all();

        if ($suppliers->isEmpty() || $items->isEmpty() || $currencies->isEmpty()) {
            $this->command->warn('No suppliers, items, or currencies found. Please seed them first.');
            return;
        }

        $reasons = ['defective', 'wrong_item', 'overstock', 'damaged', 'quality_issue', 'other'];
        $statuses = ['pending', 'approved', 'completed', 'rejected'];

        for ($i = 0; $i < 20; $i++) {
            $supplier = $suppliers->random();
            $currency = $currencies->random();
            $user = $users->random();
            $status = $statuses[array_rand($statuses)];
            $reason = $reasons[array_rand($reasons)];

            // Generate return number
            $returnNumber = 'SR-' . date('Y') . '-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT);

            // Create supplier return
            $supplierReturn = SupplierReturn::create([
                'supplier_id' => $supplier->id,
                'return_number' => $returnNumber,
                'return_date' => now()->subDays(rand(1, 30)),
                'currency_id' => $currency->id,
                'exchange_rate' => $currency->exchange_rate,
                'reason' => $reason,
                'status' => $status,
                'notes' => $this->getRandomNotes($reason),
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);

            // Add 1-5 items to the return (or all items if fewer than 5)
            $itemCount = min(rand(1, 5), $items->count());
            $selectedItems = $items->random($itemCount);
            
            $subtotal = 0;
            $discountAmount = 0;
            $taxAmount = 0;

            foreach ($selectedItems as $item) {
                $quantity = rand(1, 10);
                $unitPrice = $item->selling_price * (0.8 + (rand(0, 40) / 100)); // 80-120% of selling price
                $discountPercentage = rand(0, 20);
                $taxPercentage = rand(0, 15);
                
                $itemSubtotal = $quantity * $unitPrice;
                $itemDiscountAmount = ($itemSubtotal * $discountPercentage) / 100;
                $itemTaxAmount = (($itemSubtotal - $itemDiscountAmount) * $taxPercentage) / 100;
                $itemTotalPrice = $itemSubtotal - $itemDiscountAmount + $itemTaxAmount;

                // Create transaction item
                TransactionItem::create([
                    'transaction_type' => 'App\Models\SupplierReturn',
                    'transaction_id' => $supplierReturn->id,
                    'item_id' => $item->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $itemTotalPrice,
                    'discount_percentage' => $discountPercentage,
                    'discount_amount' => $itemDiscountAmount,
                    'tax_percentage' => $taxPercentage,
                    'tax_amount' => $itemTaxAmount,
                ]);

                // Update item stock (add back to stock for returns)
                $item->increment('current_stock', $quantity);

                $subtotal += $itemSubtotal;
                $discountAmount += $itemDiscountAmount;
                $taxAmount += $itemTaxAmount;
            }

            // Update supplier return totals
            $supplierReturn->update([
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'tax_amount' => $taxAmount,
                'total_amount' => $subtotal - $discountAmount + $taxAmount,
            ]);

            // Set approval details if approved or completed
            if (in_array($status, ['approved', 'completed'])) {
                $approver = $users->random();
                $supplierReturn->update([
                    'approved_by' => $approver->id,
                    'approved_at' => $supplierReturn->created_at->addDays(rand(1, 5)),
                ]);
            }

            // Set processed by if completed
            if ($status === 'completed') {
                $processor = $users->random();
                $supplierReturn->update([
                    'processed_by' => $processor->id,
                ]);
            }
        }

        $this->command->info('Created 20 supplier returns with items.');
    }

    private function getRandomNotes($reason)
    {
        $notes = [
            'defective' => [
                'Items received were defective and not suitable for sale.',
                'Quality issues found during inspection.',
                'Defective items returned for replacement.',
                'Items damaged during manufacturing process.',
            ],
            'wrong_item' => [
                'Wrong items were shipped instead of ordered items.',
                'Incorrect product received.',
                'Order fulfillment error - wrong items sent.',
                'Items do not match the order specifications.',
            ],
            'overstock' => [
                'Excess inventory returned due to overstock.',
                'Too many items ordered, returning excess.',
                'Inventory reduction - returning surplus items.',
                'Overstock situation, returning unused items.',
            ],
            'damaged' => [
                'Items damaged during transit.',
                'Packaging was damaged, items affected.',
                'Shipping damage occurred during delivery.',
                'Items arrived in damaged condition.',
            ],
            'quality_issue' => [
                'Quality standards not met.',
                'Items do not meet quality requirements.',
                'Quality control issues identified.',
                'Substandard quality, returning items.',
            ],
            'other' => [
                'Returning items for various reasons.',
                'General return request.',
                'Items no longer needed.',
                'Returning items as requested.',
            ],
        ];

        $reasonNotes = $notes[$reason] ?? ['Return notes.'];
        return $reasonNotes[array_rand($reasonNotes)];
    }
}