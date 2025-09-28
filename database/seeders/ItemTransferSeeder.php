<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ItemTransfer;
use App\Models\Item;
use App\Models\User;
use Carbon\Carbon;

class ItemTransferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing transfers
        ItemTransfer::truncate();

        $items = Item::all();
        $users = User::all();

        if ($items->isEmpty() || $users->isEmpty()) {
            $this->command->warn('No items or users found. Please seed them first.');
            return;
        }

        $locations = [
            'Main Store',
            'Showroom',
            'Workshop',
            'Storage Room',
            'Display Area',
            'Repair Station',
            'Shipping Area',
            'Customer Location'
        ];

        $reasons = [
            'restock' => 'Restock',
            'sale_transfer' => 'Sale Transfer',
            'repair' => 'Repair',
            'display' => 'Display',
            'storage' => 'Storage',
            'damage' => 'Damage',
            'other' => 'Other'
        ];

        $statuses = ['pending', 'in_transit', 'completed', 'cancelled'];

        // Create 30 item transfers
        for ($i = 1; $i <= 30; $i++) {
            $item = $items->random();
            $fromLocation = $locations[array_rand($locations)];
            $toLocation = $locations[array_rand($locations)];
            
            // Ensure from and to locations are different
            while ($toLocation === $fromLocation) {
                $toLocation = $locations[array_rand($locations)];
            }

            $status = $statuses[array_rand($statuses)];
            $reason = array_rand($reasons);
            
            // Generate transfer date (last 30 days)
            $transferDate = Carbon::now()->subDays(rand(0, 30));
            
            // Generate reference number
            $referenceNumber = 'IT-' . date('Y') . '-' . str_pad($i, 4, '0', STR_PAD_LEFT);
            
            // Generate quantity (1-10, but not more than item stock)
            $maxQuantity = min(10, $item->current_stock);
            $quantity = $maxQuantity > 0 ? rand(1, $maxQuantity) : 1;

            $transfer = ItemTransfer::create([
                'item_id' => $item->id,
                'reference_number' => $referenceNumber,
                'from_location' => $fromLocation,
                'to_location' => $toLocation,
                'quantity' => $quantity,
                'transfer_date' => $transferDate,
                'reason' => $reason,
                'status' => $status,
                'notes' => $this->generateNotes($reason, $fromLocation, $toLocation),
                'transferred_by' => $users->random()->id,
                'received_by' => $status === 'completed' ? $users->random()->id : null,
                'received_at' => $status === 'completed' ? $transferDate->addDays(rand(1, 5)) : null,
                'created_by' => $users->random()->id,
                'updated_by' => $users->random()->id,
            ]);

            // Update item stock if transfer is completed
            if ($status === 'completed') {
                $item->decrement('current_stock', $quantity);
            }
        }

        $this->command->info('Created 30 item transfers successfully!');
    }

    private function generateNotes($reason, $fromLocation, $toLocation)
    {
        $notes = [
            'restock' => "Restocking inventory from {$fromLocation} to {$toLocation}",
            'sale_transfer' => "Moving items for sale from {$fromLocation} to {$toLocation}",
            'repair' => "Sending items for repair from {$fromLocation} to {$toLocation}",
            'display' => "Moving items to display area from {$fromLocation} to {$toLocation}",
            'storage' => "Transferring to storage from {$fromLocation} to {$toLocation}",
            'damage' => "Moving damaged items from {$fromLocation} to {$toLocation}",
            'other' => "General transfer from {$fromLocation} to {$toLocation}"
        ];

        return $notes[$reason] ?? "Transfer from {$fromLocation} to {$toLocation}";
    }
}