<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sales_orders', function (Blueprint $table) {
            $table->foreignId('sales_assistant_id')->nullable()->constrained()->onDelete('set null');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('sales_assistant_id')->nullable()->constrained()->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_orders', function (Blueprint $table) {
            $table->dropForeign(['sales_assistant_id']);
            $table->dropColumn('sales_assistant_id');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['sales_assistant_id']);
            $table->dropColumn('sales_assistant_id');
        });
    }
};