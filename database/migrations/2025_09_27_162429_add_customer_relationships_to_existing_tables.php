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
        // Add customer_id to invoices table
        Schema::table('invoices', function (Blueprint $table) {
            $table->unsignedBigInteger('customer_id')->nullable()->after('id');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
            
            $table->string('invoice_number')->unique()->after('customer_id');
            $table->date('invoice_date')->nullable()->after('invoice_number');
            $table->decimal('total_amount', 15, 2)->default(0)->after('invoice_date');
            $table->decimal('tax_amount', 15, 2)->default(0)->after('total_amount');
            $table->decimal('discount_amount', 15, 2)->default(0)->after('tax_amount');
            $table->string('status')->default('draft')->after('discount_amount');
            $table->text('notes')->nullable()->after('status');
        });

        // Add customer_id to sales_orders table
        Schema::table('sales_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('customer_id')->nullable()->after('id');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
            
            $table->string('order_number')->unique()->after('customer_id');
            $table->date('order_date')->nullable()->after('order_number');
            $table->date('delivery_date')->nullable()->after('order_date');
            $table->decimal('total_amount', 15, 2)->default(0)->after('delivery_date');
            $table->string('status')->default('pending')->after('total_amount');
            $table->text('notes')->nullable()->after('status');
        });

        // Add customer_id to customer_returns table
        Schema::table('customer_returns', function (Blueprint $table) {
            $table->unsignedBigInteger('customer_id')->nullable()->after('id');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
            
            $table->string('return_number')->unique()->after('customer_id');
            $table->date('return_date')->nullable()->after('return_number');
            $table->decimal('total_amount', 15, 2)->default(0)->after('return_date');
            $table->string('status')->default('pending')->after('total_amount');
            $table->text('reason')->nullable()->after('status');
            $table->text('notes')->nullable()->after('reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropColumn(['customer_id', 'invoice_number', 'invoice_date', 'total_amount', 'tax_amount', 'discount_amount', 'status', 'notes']);
        });

        Schema::table('sales_orders', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropColumn(['customer_id', 'order_number', 'order_date', 'delivery_date', 'total_amount', 'status', 'notes']);
        });

        Schema::table('customer_returns', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropColumn(['customer_id', 'return_number', 'return_date', 'total_amount', 'status', 'reason', 'notes']);
        });
    }
};