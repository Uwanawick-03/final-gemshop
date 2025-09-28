<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FinishedGoodTransfer;
use App\Models\Item;
use App\Models\Craftsman;
use App\Models\User;
use Carbon\Carbon;

class FinishedGoodTransferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some sample items, craftsmen, and users
        $items = Item::take(5)->get();
        $craftsmen = Craftsman::take(3)->get();
        $users = User::take(3)->get();

        if ($items->isEmpty() || $users->isEmpty()) {
            $this->command->warn('No items or users found. Please run other seeders first.');
            return;
        }

        $sampleTransfers = [
            [
                'item_id' => $items->random()->id,
                'craftsman_id' => $craftsmen->isNotEmpty() ? $craftsmen->random()->id : null,
                'from_workshop' => 'Main Workshop - Production Line A',
                'to_location' => 'Quality Control Department',
                'quantity' => 5,
                'transfer_date' => Carbon::now()->subDays(2),
                'status' => 'completed',
                'quality_check_passed' => true,
                'quality_check_by' => $users->random()->id,
                'transferred_by' => $users->random()->id,
                'received_by' => $users->random()->id,
                'notes' => 'High quality finished goods ready for final inspection and packaging.',
            ],
            [
                'item_id' => $items->random()->id,
                'craftsman_id' => $craftsmen->isNotEmpty() ? $craftsmen->random()->id : null,
                'from_workshop' => 'Jewelry Workshop - Bench 3',
                'to_location' => 'Showroom Display',
                'quantity' => 3,
                'transfer_date' => Carbon::now()->subDays(1),
                'status' => 'quality_check',
                'quality_check_passed' => false,
                'quality_check_by' => $users->random()->id,
                'transferred_by' => $users->random()->id,
                'received_by' => null,
                'notes' => 'Premium collection pieces awaiting quality approval.',
            ],
            [
                'item_id' => $items->random()->id,
                'craftsman_id' => $craftsmen->isNotEmpty() ? $craftsmen->random()->id : null,
                'from_workshop' => 'Engraving Station',
                'to_location' => 'Storage Room - Section B',
                'quantity' => 8,
                'transfer_date' => Carbon::now(),
                'status' => 'pending',
                'quality_check_passed' => false,
                'quality_check_by' => null,
                'transferred_by' => null,
                'received_by' => null,
                'notes' => 'Custom engraved pieces ready for transfer to storage.',
            ],
            [
                'item_id' => $items->random()->id,
                'craftsman_id' => $craftsmen->isNotEmpty() ? $craftsmen->random()->id : null,
                'from_workshop' => 'Polishing Department',
                'to_location' => 'Quality Control Department',
                'quantity' => 12,
                'transfer_date' => Carbon::now()->subDays(3),
                'status' => 'rejected',
                'quality_check_passed' => false,
                'quality_check_by' => $users->random()->id,
                'transferred_by' => $users->random()->id,
                'received_by' => null,
                'notes' => 'Quality check failed - surface finish not meeting standards. Returned for rework.',
            ],
            [
                'item_id' => $items->random()->id,
                'craftsman_id' => $craftsmen->isNotEmpty() ? $craftsmen->random()->id : null,
                'from_workshop' => 'Assembly Line - Station 2',
                'to_location' => 'Packaging Department',
                'quantity' => 15,
                'transfer_date' => Carbon::now()->subDays(5),
                'status' => 'completed',
                'quality_check_passed' => true,
                'quality_check_by' => $users->random()->id,
                'transferred_by' => $users->random()->id,
                'received_by' => $users->random()->id,
                'notes' => 'Batch completed successfully. All items passed quality inspection and are ready for packaging.',
            ],
        ];

        foreach ($sampleTransfers as $transferData) {
            // Generate reference number
            $referenceNumber = 'FGT-' . strtoupper(uniqid());
            
            FinishedGoodTransfer::create(array_merge($transferData, [
                'reference_number' => $referenceNumber,
            ]));
        }

        $this->command->info('Finished good transfers seeded successfully!');
    }
}
