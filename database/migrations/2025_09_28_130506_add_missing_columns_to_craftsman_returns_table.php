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
        Schema::table('craftsman_returns', function (Blueprint $table) {
            // Only add columns that don't exist
            if (!Schema::hasColumn('craftsman_returns', 'craftsman_id')) {
                $table->foreignId('craftsman_id')->constrained()->onDelete('cascade')->after('id');
            }
            if (!Schema::hasColumn('craftsman_returns', 'item_id')) {
                $table->foreignId('item_id')->constrained()->onDelete('cascade')->after('craftsman_id');
            }
            if (!Schema::hasColumn('craftsman_returns', 'return_number')) {
                $table->string('return_number')->unique()->after('item_id');
            }
            if (!Schema::hasColumn('craftsman_returns', 'return_date')) {
                $table->date('return_date')->after('return_number');
            }
            if (!Schema::hasColumn('craftsman_returns', 'return_type')) {
                $table->enum('return_type', ['defective', 'unused_material', 'excess', 'quality_issue'])->after('return_date');
            }
            if (!Schema::hasColumn('craftsman_returns', 'quantity')) {
                $table->integer('quantity')->after('return_type');
            }
            if (!Schema::hasColumn('craftsman_returns', 'reason')) {
                $table->text('reason')->after('quantity');
            }
            if (!Schema::hasColumn('craftsman_returns', 'status')) {
                $table->enum('status', ['pending', 'approved', 'completed', 'rejected'])->default('pending')->after('reason');
            }
            if (!Schema::hasColumn('craftsman_returns', 'processed_by')) {
                $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null')->after('status');
            }
            if (!Schema::hasColumn('craftsman_returns', 'approved_by')) {
                $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null')->after('processed_by');
            }
            if (!Schema::hasColumn('craftsman_returns', 'notes')) {
                $table->text('notes')->nullable()->after('approved_by');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('craftsman_returns', function (Blueprint $table) {
            $table->dropColumn([
                'craftsman_id',
                'item_id',
                'return_number',
                'return_date',
                'return_type',
                'quantity',
                'reason',
                'status',
                'processed_by',
                'approved_by',
                'notes'
            ]);
        });
    }
};