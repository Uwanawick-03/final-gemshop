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
        Schema::table('alteration_commissions', function (Blueprint $table) {
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('sales_assistant_id')->nullable()->constrained('sales_assistants')->onDelete('set null');
            $table->foreignId('craftsman_id')->nullable()->constrained('craftsmen')->onDelete('set null');
            $table->foreignId('item_id')->nullable()->constrained()->onDelete('set null');
            $table->string('commission_number')->unique();
            $table->date('commission_date');
            $table->enum('alteration_type', ['resize', 'repair', 'polish', 'engrave', 'design_change', 'stone_setting', 'cleaning', 'other'])->default('other');
            $table->text('description')->nullable();
            $table->decimal('commission_amount', 15, 2);
            $table->foreignId('currency_id')->constrained()->onDelete('cascade');
            $table->decimal('exchange_rate', 10, 4)->default(1);
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->date('start_date')->nullable();
            $table->date('completion_date')->nullable();
            $table->enum('payment_status', ['unpaid', 'partial', 'paid'])->default('unpaid');
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->date('payment_date')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alteration_commissions', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropForeign(['sales_assistant_id']);
            $table->dropForeign(['craftsman_id']);
            $table->dropForeign(['item_id']);
            $table->dropForeign(['currency_id']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            
            $table->dropColumn([
                'customer_id',
                'sales_assistant_id',
                'craftsman_id',
                'item_id',
                'commission_number',
                'commission_date',
                'alteration_type',
                'description',
                'commission_amount',
                'currency_id',
                'exchange_rate',
                'status',
                'start_date',
                'completion_date',
                'payment_status',
                'paid_amount',
                'payment_date',
                'notes',
                'created_by',
                'updated_by'
            ]);
        });
    }
};