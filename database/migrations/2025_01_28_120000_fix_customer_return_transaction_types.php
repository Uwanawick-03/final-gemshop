<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing transaction_items records to use the correct transaction_type
        DB::table('transaction_items')
            ->where('transaction_type', 'customer_return')
            ->update(['transaction_type' => 'App\Models\CustomerReturn']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to the old transaction_type
        DB::table('transaction_items')
            ->where('transaction_type', 'App\Models\CustomerReturn')
            ->update(['transaction_type' => 'customer_return']);
    }
};
