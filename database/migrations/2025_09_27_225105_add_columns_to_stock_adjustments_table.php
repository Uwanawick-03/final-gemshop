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
        Schema::table('stock_adjustments', function (Blueprint $table) {
            $table->string('adjustment_number')->unique()->after('id');
            $table->date('adjustment_date')->after('adjustment_number');
            $table->string('reason')->after('adjustment_date');
            $table->enum('type', ['increase', 'decrease'])->after('reason');
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending')->after('type');
            $table->integer('total_items')->default(0)->after('status');
            $table->text('notes')->nullable()->after('total_items');
            $table->foreignId('approved_by')->nullable()->after('notes')->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->foreignId('created_by')->nullable()->after('approved_at')->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->after('created_by')->constrained('users')->onDelete('set null');

            $table->index(['status', 'adjustment_date']);
            $table->index(['type', 'reason']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_adjustments', function (Blueprint $table) {
            $table->dropIndex(['status', 'adjustment_date']);
            $table->dropIndex(['type', 'reason']);
            $table->dropForeign(['approved_by']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropColumn([
                'adjustment_number',
                'adjustment_date',
                'reason',
                'type',
                'status',
                'total_items',
                'notes',
                'approved_by',
                'approved_at',
                'created_by',
                'updated_by'
            ]);
        });
    }
};