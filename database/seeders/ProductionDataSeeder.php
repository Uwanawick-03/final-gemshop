<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Currency;

class ProductionDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🧹 Cleaning up test data...');
        
        // Clear all test data from all tables
        $this->clearTestData();
        
        $this->command->info('👥 Creating administrative users...');
        
        // Create Admin User
        $admin = User::create([
            'name' => 'System Administrator',
            'email' => 'admin@gemshop.com',
            'phone' => '+94 11 234 5678',
            'role' => 'admin',
            'is_active' => true,
            'password' => Hash::make('Admin@2024!'),
            'email_verified_at' => now(),
        ]);
        
        // Create Manager User
        $manager = User::create([
            'name' => 'Operations Manager',
            'email' => 'manager@gemshop.com',
            'phone' => '+94 11 234 5679',
            'role' => 'manager',
            'is_active' => true,
            'password' => Hash::make('Manager@2024!'),
            'email_verified_at' => now(),
        ]);
        
        $this->command->info('💱 Setting up currencies...');
        
        // Ensure currencies exist (keep existing or create new)
        $this->setupCurrencies();
        
        $this->command->info('✅ Production data setup completed!');
        $this->command->info('');
        $this->command->info('🔐 Administrative Users Created:');
        $this->command->info('   Admin: admin@gemshop.com / Admin@2024!');
        $this->command->info('   Manager: manager@gemshop.com / Manager@2024!');
        $this->command->info('');
        $this->command->info('⚠️  Please change these passwords after first login!');
    }
    
    /**
     * Clear all test data from the database
     */
    private function clearTestData(): void
    {
        // List of tables to clear (in order to respect foreign key constraints)
        $tablesToClear = [
            'transaction_items',
            'stock_movements',
            'customer_returns',
            'supplier_returns',
            'sales_orders',
            'invoices',
            'grns',
            'purchase_orders',
            'items',
            'customers',
            'suppliers',
            'sales_executives',
            'craftsmen',
            'tour_guides',
            'banks',
            'users', // Clear all users except we'll recreate admin/manager
        ];
        
        // Disable foreign key checks temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        foreach ($tablesToClear as $table) {
            try {
                DB::table($table)->truncate();
                $this->command->info("   ✓ Cleared table: {$table}");
            } catch (\Exception $e) {
                $this->command->warn("   ⚠ Could not clear table: {$table} - {$e->getMessage()}");
            }
        }
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        // Clear any cached data
        \Artisan::call('cache:clear');
        \Artisan::call('config:clear');
        \Artisan::call('route:clear');
        \Artisan::call('view:clear');
    }
    
    /**
     * Setup essential currencies
     */
    private function setupCurrencies(): void
    {
        // Check if currencies already exist
        if (Currency::count() === 0) {
            $currencies = [
                [
                    'code' => 'LKR',
                    'name' => 'Sri Lankan Rupee',
                    'symbol' => 'Rs',
                    'exchange_rate' => 1.0000,
                    'is_base_currency' => true,
                    'is_active' => true,
                ],
                [
                    'code' => 'USD',
                    'name' => 'US Dollar',
                    'symbol' => '$',
                    'exchange_rate' => 0.0031, // 1 LKR = 0.0031 USD (320 LKR = 1 USD)
                    'is_base_currency' => false,
                    'is_active' => true,
                ],
                [
                    'code' => 'EUR',
                    'name' => 'Euro',
                    'symbol' => '€',
                    'exchange_rate' => 0.0028, // 1 LKR = 0.0028 EUR (350 LKR = 1 EUR)
                    'is_base_currency' => false,
                    'is_active' => true,
                ],
            ];
            
            foreach ($currencies as $currency) {
                Currency::create($currency);
            }
            
            $this->command->info('   ✓ Created currencies: LKR, USD, EUR');
        } else {
            $this->command->info('   ✓ Currencies already exist');
        }
    }
}
