<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JobIssue;
use App\Models\Item;
use App\Models\Craftsman;
use App\Models\User;
use Carbon\Carbon;

class JobIssueSeeder extends Seeder
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

        $sampleIssues = [
            [
                'item_id' => $items->random()->id,
                'craftsman_id' => $craftsmen->isNotEmpty() ? $craftsmen->random()->id : null,
                'issue_type' => 'defect',
                'priority' => 'high',
                'issue_date' => Carbon::now()->subDays(5),
                'description' => 'Crack found in the gemstone during quality inspection. The crack is visible under magnification and affects the overall appearance of the piece.',
                'status' => 'open',
                'assigned_to' => $users->random()->id,
                'estimated_completion' => Carbon::now()->addDays(3),
            ],
            [
                'item_id' => $items->random()->id,
                'craftsman_id' => $craftsmen->isNotEmpty() ? $craftsmen->random()->id : null,
                'issue_type' => 'delay',
                'priority' => 'medium',
                'issue_date' => Carbon::now()->subDays(3),
                'description' => 'Production delay due to unavailability of required materials. Supplier delivery is delayed by 2 weeks.',
                'status' => 'in_progress',
                'assigned_to' => $users->random()->id,
                'estimated_completion' => Carbon::now()->addDays(7),
            ],
            [
                'item_id' => $items->random()->id,
                'craftsman_id' => $craftsmen->isNotEmpty() ? $craftsmen->random()->id : null,
                'issue_type' => 'quality',
                'priority' => 'urgent',
                'issue_date' => Carbon::now()->subDays(1),
                'description' => 'Customer complaint about poor finishing quality. The piece does not meet the expected standards and requires rework.',
                'status' => 'open',
                'assigned_to' => $users->random()->id,
                'estimated_completion' => Carbon::now()->addDays(2),
            ],
            [
                'item_id' => $items->random()->id,
                'craftsman_id' => $craftsmen->isNotEmpty() ? $craftsmen->random()->id : null,
                'issue_type' => 'material',
                'priority' => 'low',
                'issue_date' => Carbon::now()->subDays(7),
                'description' => 'Incorrect material used for the ring band. The gold purity is 18k instead of the required 22k.',
                'status' => 'resolved',
                'assigned_to' => $users->random()->id,
                'resolved_by' => $users->random()->id,
                'resolved_date' => Carbon::now()->subDays(2),
                'actual_completion' => Carbon::now()->subDays(2),
                'resolution_notes' => 'Material replaced with correct 22k gold. Customer notified and new delivery scheduled.',
                'estimated_completion' => Carbon::now()->subDays(1),
            ],
            [
                'item_id' => $items->random()->id,
                'craftsman_id' => $craftsmen->isNotEmpty() ? $craftsmen->random()->id : null,
                'issue_type' => 'other',
                'priority' => 'medium',
                'issue_date' => Carbon::now()->subDays(10),
                'description' => 'Custom design modification requested by customer after production started. Requires design approval and additional time.',
                'status' => 'closed',
                'assigned_to' => $users->random()->id,
                'resolved_by' => $users->random()->id,
                'resolved_date' => Carbon::now()->subDays(1),
                'actual_completion' => Carbon::now()->subDays(1),
                'resolution_notes' => 'Design modification approved and implemented. Customer satisfied with the final result.',
                'estimated_completion' => Carbon::now()->subDays(2),
            ],
        ];

        foreach ($sampleIssues as $issueData) {
            // Generate job number
            $jobNumber = 'JOB-' . strtoupper(uniqid());
            
            JobIssue::create(array_merge($issueData, [
                'job_number' => $jobNumber,
            ]));
        }

        $this->command->info('Job issues seeded successfully!');
    }
}
