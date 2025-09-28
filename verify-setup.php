<?php
/**
 * Gem Shop Management System - Setup Verification Script
 * 
 * This script verifies that the production setup was completed successfully
 * and displays the current system status.
 */

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Currency;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "========================================\n";
echo "Gem Shop Management System\n";
echo "Production Setup Verification\n";
echo "========================================\n\n";

try {
    // Check database connection
    echo "🔍 Checking database connection...\n";
    DB::connection()->getPdo();
    echo "   ✅ Database connection successful\n\n";
    
    // Check users
    echo "👥 Checking administrative users...\n";
    $admin = User::where('email', 'admin@gemshop.com')->first();
    $manager = User::where('email', 'manager@gemshop.com')->first();
    
    if ($admin) {
        echo "   ✅ Admin user found: {$admin->name} ({$admin->email})\n";
    } else {
        echo "   ❌ Admin user not found\n";
    }
    
    if ($manager) {
        echo "   ✅ Manager user found: {$manager->name} ({$manager->email})\n";
    } else {
        echo "   ❌ Manager user not found\n";
    }
    
    // Check currencies
    echo "\n💱 Checking currencies...\n";
    $currencies = Currency::all();
    if ($currencies->count() > 0) {
        echo "   ✅ Found {$currencies->count()} currencies:\n";
        foreach ($currencies as $currency) {
            $base = $currency->is_base_currency ? ' (Base)' : '';
            echo "      - {$currency->code}: {$currency->name}{$base}\n";
        }
    } else {
        echo "   ❌ No currencies found\n";
    }
    
    // Check data cleanup
    echo "\n🧹 Checking data cleanup...\n";
    $tables = [
        'items' => 'Items',
        'customers' => 'Customers',
        'suppliers' => 'Suppliers',
        'purchase_orders' => 'Purchase Orders',
        'invoices' => 'Invoices',
        'grns' => 'GRNs',
        'sales_orders' => 'Sales Orders',
    ];
    
    $allClean = true;
    foreach ($tables as $table => $name) {
        $count = DB::table($table)->count();
        if ($count == 0) {
            echo "   ✅ {$name}: Clean (0 records)\n";
        } else {
            echo "   ⚠️  {$name}: {$count} records found\n";
            $allClean = false;
        }
    }
    
    // Summary
    echo "\n========================================\n";
    if ($admin && $manager && $allClean) {
        echo "✅ PRODUCTION SETUP VERIFICATION PASSED\n";
        echo "========================================\n\n";
        echo "🎉 Your Gem Shop Management System is ready for production!\n\n";
        echo "🔐 Login Credentials:\n";
        echo "   Admin: admin@gemshop.com / Admin@2024!\n";
        echo "   Manager: manager@gemshop.com / Manager@2024!\n\n";
        echo "⚠️  Remember to change these passwords after first login!\n";
    } else {
        echo "❌ PRODUCTION SETUP VERIFICATION FAILED\n";
        echo "========================================\n\n";
        echo "Please run the setup script again or check for errors.\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error during verification: " . $e->getMessage() . "\n";
    echo "Please check your database configuration and try again.\n";
}

echo "\n";
