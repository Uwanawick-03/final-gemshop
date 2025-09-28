<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WorkshopAdjustment;
use App\Models\Item;
use App\Models\Craftsman;
use App\Models\User;
use Carbon\Carbon;

class WorkshopAdjustmentSeeder extends Seeder
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

        $sampleAdjustments = [
            [
                'item_id' => $items->random()->id,
                'craftsman_id' => $craftsmen->isNotEmpty() ? $craftsmen->random()->id : null,
                'workshop_location' => 'Main Workshop - Production Line A',
                'adjustment_type' => 'material_used',
                'quantity' => 3,
                'adjustment_date' => Carbon::now()->subDays(2),
                'reason' => 'Material used for custom ring production. Gold wire consumed during crafting process.',
                'status' => 'approved',
                'approved_by' => $users->random()->id,
                'notes' => 'Approved after verification of production records.',
            ],
            [
                'item_id' => $items->random()->id,
                'craftsman_id' => $craftsmen->isNotEmpty() ? $craftsmen->random()->id : null,
                'workshop_location' => 'Jewelry Workshop - Bench 2',
                'adjustment_type' => 'scrap',
                'quantity' => 2,
                'adjustment_date' => Carbon::now()->subDays(1),
                'reason' => 'Defective pieces that cannot be repaired. Silver casting defects found during quality check.',
                'status' => 'approved',
                'approved_by' => $users->random()->id,
                'notes' => 'Scrap materials to be recycled.',
            ],
            [
                'item_id' => $items->random()->id,
                'craftsman_id' => $craftsmen->isNotEmpty() ? $craftsmen->random()->id : null,
                'workshop_location' => 'Engraving Station',
                'adjustment_type' => 'defective',
                'quantity' => 1,
                'adjustment_date' => Carbon::now(),
                'reason' => 'Engraving error on custom pendant. Customer name misspelled during laser engraving process.',
                'status' => 'pending',
                'approved_by' => null,
                'notes' => 'Awaiting supervisor approval for rework.',
            ],
            [
                'item_id' => $items->random()->id,
                'craftsman_id' => $craftsmen->isNotEmpty() ? $craftsmen->random()->id : null,
                'workshop_location' => 'Polishing Department',
                'adjustment_type' => 'correction',
                'quantity' => 5,
                'adjustment_date' => Carbon::now()->subDays(3),
                'reason' => 'Stock correction due to miscount during inventory. Found additional finished pieces in storage.',
                'status' => 'approved',
                'approved_by' => $users->random()->id,
                'notes' => 'Inventory reconciliation completed.',
            ],
            [
                'item_id' => $items->random()->id,
                'craftsman_id' => $craftsmen->isNotEmpty() ? $craftsmen->random()->id : null,
                'workshop_location' => 'Assembly Line - Station 1',
                'adjustment_type' => 'material_used',
                'quantity' => 4,
                'adjustment_date' => Carbon::now()->subDays(5),
                'reason' => 'Diamond chips used for accent work on wedding ring set. Small stones consumed during setting process.',
                'status' => 'rejected',
                'approved_by' => $users->random()->id,
                'notes' => 'Rejected due to insufficient documentation of material usage.',
            ],
        ];

        foreach ($sampleAdjustments as $adjustmentData) {
            // Generate reference number
            $referenceNumber = 'WA-' . strtoupper(uniqid());
            
            WorkshopAdjustment::create(array_merge($adjustmentData, [
                'reference_number' => $referenceNumber,
            ]));
        }

        $this->command->info('Workshop adjustments seeded successfully!');
    }
}
