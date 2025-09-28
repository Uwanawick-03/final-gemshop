<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AlterationCommission;
use App\Models\Customer;
use App\Models\SalesAssistant;
use App\Models\Craftsman;
use App\Models\Item;
use App\Models\Currency;
use Carbon\Carbon;

class AlterationCommissionSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing commissions
        AlterationCommission::truncate();

        $customers = Customer::all();
        $salesAssistants = SalesAssistant::all();
        $craftsmen = Craftsman::all();
        $items = Item::all();
        $currencies = Currency::all();
        $users = \App\Models\User::all();

        if ($customers->isEmpty() || $currencies->isEmpty() || $users->isEmpty()) {
            $this->command->warn('No customers, currencies, or users found. Please seed them first.');
            return;
        }

        $alterationTypes = [
            'resize' => 'Resize',
            'repair' => 'Repair',
            'polish' => 'Polish',
            'engrave' => 'Engrave',
            'design_change' => 'Design Change',
            'stone_setting' => 'Stone Setting',
            'cleaning' => 'Cleaning',
            'other' => 'Other'
        ];

        $statuses = ['pending', 'in_progress', 'completed', 'cancelled'];
        $paymentStatuses = ['unpaid', 'partial', 'paid'];

        // Create 25 alteration commissions
        for ($i = 1; $i <= 25; $i++) {
            $customer = $customers->random();
            $salesAssistant = $salesAssistants->random();
            $craftsman = $craftsmen->random();
            $item = $items->random();
            $currency = $currencies->random();
            $user = $users->random();

            $status = $statuses[array_rand($statuses)];
            $paymentStatus = $paymentStatuses[array_rand($paymentStatuses)];
            $alterationType = array_rand($alterationTypes);
            
            // Generate commission date (last 60 days)
            $commissionDate = Carbon::now()->subDays(rand(0, 60));
            
            // Generate reference number
            $commissionNumber = 'AC-' . date('Y') . '-' . str_pad($i, 4, '0', STR_PAD_LEFT);
            
            // Generate commission amount (100-5000)
            $commissionAmount = rand(100, 5000);

            // Calculate exchange rate (simplified)
            $exchangeRate = $currency->code === 'LKR' ? 1.0 : rand(50, 500) / 100;

            // Generate dates based on status
            $startDate = null;
            $completionDate = null;
            $paymentDate = null;
            $paidAmount = 0;

            if ($status === 'in_progress' || $status === 'completed') {
                $startDate = $commissionDate->addDays(rand(1, 7));
            }

            if ($status === 'completed') {
                $completionDate = $startDate ? $startDate->addDays(rand(1, 14)) : $commissionDate->addDays(rand(8, 21));
            }

            if ($paymentStatus !== 'unpaid') {
                $paymentDate = $commissionDate->addDays(rand(1, 30));
                if ($paymentStatus === 'partial') {
                    $paidAmount = $commissionAmount * rand(30, 80) / 100; // 30-80% paid
                } else {
                    $paidAmount = $commissionAmount; // Fully paid
                }
            }

            $commission = AlterationCommission::create([
                'customer_id' => $customer->id,
                'sales_assistant_id' => $salesAssistant->id,
                'craftsman_id' => $craftsman->id,
                'item_id' => $item->id,
                'commission_number' => $commissionNumber,
                'commission_date' => $commissionDate,
                'alteration_type' => $alterationType,
                'description' => $this->generateDescription($alterationType, $item->name),
                'commission_amount' => $commissionAmount,
                'currency_id' => $currency->id,
                'exchange_rate' => $exchangeRate,
                'status' => $status,
                'start_date' => $startDate,
                'completion_date' => $completionDate,
                'payment_status' => $paymentStatus,
                'paid_amount' => $paidAmount,
                'payment_date' => $paymentDate,
                'notes' => $this->generateNotes($status, $alterationType),
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);
        }

        $this->command->info('Created 25 alteration commissions successfully!');
    }

    private function generateDescription($alterationType, $itemName)
    {
        $descriptions = [
            'resize' => "Resize {$itemName} to fit customer specifications",
            'repair' => "Repair damaged {$itemName} and restore to original condition",
            'polish' => "Professional polishing of {$itemName} to restore shine",
            'engrave' => "Custom engraving on {$itemName} as requested by customer",
            'design_change' => "Modify design of {$itemName} according to customer requirements",
            'stone_setting' => "Set stones in {$itemName} with precision craftsmanship",
            'cleaning' => "Deep cleaning and maintenance of {$itemName}",
            'other' => "Custom alteration work on {$itemName}"
        ];

        return $descriptions[$alterationType] ?? "Alteration work on {$itemName}";
    }

    private function generateNotes($status, $alterationType)
    {
        $notes = [
            'pending' => "Commission created and waiting for craftsman assignment",
            'in_progress' => "Work in progress - {$alterationType} alteration being performed",
            'completed' => "Alteration work completed successfully and ready for pickup",
            'cancelled' => "Commission cancelled due to customer request"
        ];

        return $notes[$status] ?? "Commission notes";
    }
}