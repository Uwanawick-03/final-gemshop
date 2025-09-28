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
        Schema::table('customer_returns', function (Blueprint $table) {
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->string('return_number')->unique();
            $table->date('return_date');
            $table->foreignId('currency_id')->constrained()->onDelete('cascade');
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->enum('status', ['pending', 'approved', 'rejected', 'processed', 'refunded'])->default('pending');
            $table->text('reason');
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
        Schema::table('customer_returns', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropForeign(['currency_id']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropColumn([
                'customer_id',
                'return_number',
                'return_date',
                'currency_id',
                'total_amount',
                'status',
                'reason',
                'notes',
                'created_by',
                'updated_by'
            ]);
        });
    }
};