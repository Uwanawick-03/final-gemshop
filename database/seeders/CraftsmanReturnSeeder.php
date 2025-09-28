<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CraftsmanReturn;
use App\Models\Craftsman;
use App\Models\Item;
use App\Models\User;
use Carbon\Carbon;

class CraftsmanReturnSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some sample craftsmen, items, and users
        $craftsmen = Craftsman::take(3)->get();
        $items = Item::take(5)->get();
        $users = User::take(3)->get();

        if ($craftsmen->isEmpty() || $items->isEmpty() || $users->isEmpty()) {
            $this->command->warn('No craftsmen, items, or users found. Please run other seeders first.');
            return;
        }

        $sampleReturns = [
            [
                'craftsman_id' => $craftsmen->random()->id,
                'item_id' => $items->random()->id,
                'return_type' => 'defective',
                'quantity' => 2,
                'return_date' => Carbon::now()->subDays(2),
                'reason' => 'Defective gold rings with casting defects. Surface imperfections found during quality inspection.',
                'status' => 'completed',
                'processed_by' => $users->random()->id,
                'approved_by' => $users->random()->id,
                'notes' => 'Defective items returned to supplier for replacement.',
            ],
            [
                'craftsman_id' => $craftsmen->random()->id,
                'item_id' => $items->random()->id,
                'return_type' => 'unused_material',
                'quantity' => 5,
                'return_date' => Carbon::now()->subDays(1),
                'reason' => 'Unused silver wire returned after project completion. Excess material from custom necklace project.',
                'status' => 'completed',
                'processed_by' => $users->random()->id,
                'approved_by' => $users->random()->id,
                'notes' => 'Material returned to inventory for future use.',
            ],
            [
                'craftsman_id' => $craftsmen->random()->id,
                'item_id' => $items->random()->id,
                'return_type' => 'excess',
                'quantity' => 3,
                'return_date' => Carbon::now(),
                'reason' => 'Excess diamond chips from wedding ring set. More stones provided than needed for the project.',
                'status' => 'approved',
                'approved_by' => $users->random()->id,
                'processed_by' => null,
                'notes' => 'Awaiting completion processing.',
            ],
            [
                'craftsman_id' => $craftsmen->random()->id,
                'item_id' => $items->random()->id,
                'return_type' => 'quality_issue',
                'quantity' => 1,
                'return_date' => Carbon::now()->subDays(3),
                'reason' => 'Quality issue with custom pendant. Engraving quality does not meet standards.',
                'status' => 'rejected',
                'approved_by' => $users->random()->id,
                'processed_by' => null,
                'notes' => 'Rejected due to insufficient documentation. Please provide detailed quality report.',
            ],
            [
                'craftsman_id' => $craftsmen->random()->id,
                'item_id' => $items->random()->id,
                'return_type' => 'defective',
                'quantity' => 4,
                'return_date' => Carbon::now()->subDays(5),
                'reason' => 'Defective gemstone settings. Prongs not holding stones securely.',
                'status' => 'pending',
                'approved_by' => null,
                'processed_by' => null,
                'notes' => 'Awaiting supervisor approval.',
            ],
        ];

        foreach ($sampleReturns as $returnData) {
            // Generate return number
            $returnNumber = 'CR-' . strtoupper(uniqid());
            
            CraftsmanReturn::create(array_merge($returnData, [
                'return_number' => $returnNumber,
            ]));
        }

        $this->command->info('Craftsman returns seeded successfully!');
    }
}
