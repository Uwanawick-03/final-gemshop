<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'Admin User',
            'email' => 'admin@gemshop.com',
            'phone' => '+1234567890',
            'role' => 'admin',
            'is_active' => true,
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
        ]);
        
        // Create currencies - LKR as base currency for all processing
        \App\Models\Currency::create([
            'code' => 'LKR',
            'name' => 'Sri Lankan Rupee',
            'symbol' => 'Rs',
            'exchange_rate' => 1.0000,
            'is_base_currency' => true,
            'is_active' => true,
        ]);
        
        \App\Models\Currency::create([
            'code' => 'USD',
            'name' => 'US Dollar',
            'symbol' => '$',
            'exchange_rate' => 0.0031, // 1 LKR = 0.0031 USD (320 LKR = 1 USD)
            'is_base_currency' => false,
            'is_active' => true,
        ]);
        
        \App\Models\Currency::create([
            'code' => 'EUR',
            'name' => 'Euro',
            'symbol' => '€',
            'exchange_rate' => 0.8500,
            'is_base_currency' => false,
            'is_active' => true,
        ]);
    }
}
