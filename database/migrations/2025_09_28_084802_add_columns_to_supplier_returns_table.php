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
        Schema::table('supplier_returns', function (Blueprint $table) {
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->string('return_number')->unique();
            $table->date('return_date');
            $table->foreignId('currency_id')->constrained()->onDelete('cascade');
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('exchange_rate', 10, 4)->default(1);
            $table->enum('status', ['pending', 'approved', 'completed', 'rejected'])->default('pending');
            $table->enum('reason', ['defective', 'wrong_item', 'overstock', 'damaged', 'quality_issue', 'other'])->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supplier_returns', function (Blueprint $table) {
            $table->dropForeign(['supplier_id']);
            $table->dropForeign(['currency_id']);
            $table->dropForeign(['processed_by']);
            $table->dropForeign(['approved_by']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            
            $table->dropColumn([
                'supplier_id',
                'return_number',
                'return_date',
                'currency_id',
                'total_amount',
                'subtotal',
                'tax_amount',
                'discount_amount',
                'exchange_rate',
                'status',
                'reason',
                'notes',
                'processed_by',
                'approved_by',
                'approved_at',
                'created_by',
                'updated_by'
            ]);
        });
    }
};
