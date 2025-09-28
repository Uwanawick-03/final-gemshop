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
        Schema::table('workshop_adjustments', function (Blueprint $table) {
            // Only add columns that don't exist
            if (!Schema::hasColumn('workshop_adjustments', 'item_id')) {
                $table->foreignId('item_id')->constrained()->onDelete('cascade')->after('id');
            }
            if (!Schema::hasColumn('workshop_adjustments', 'workshop_location')) {
                $table->string('workshop_location')->after('item_id');
            }
            if (!Schema::hasColumn('workshop_adjustments', 'adjustment_type')) {
                $table->enum('adjustment_type', ['material_used', 'scrap', 'defective', 'correction'])->after('workshop_location');
            }
            if (!Schema::hasColumn('workshop_adjustments', 'quantity')) {
                $table->integer('quantity')->after('adjustment_type');
            }
            if (!Schema::hasColumn('workshop_adjustments', 'adjustment_date')) {
                $table->date('adjustment_date')->after('quantity');
            }
            if (!Schema::hasColumn('workshop_adjustments', 'reference_number')) {
                $table->string('reference_number')->unique()->after('adjustment_date');
            }
            if (!Schema::hasColumn('workshop_adjustments', 'reason')) {
                $table->text('reason')->after('reference_number');
            }
            if (!Schema::hasColumn('workshop_adjustments', 'craftsman_id')) {
                $table->foreignId('craftsman_id')->nullable()->constrained()->onDelete('set null')->after('reason');
            }
            if (!Schema::hasColumn('workshop_adjustments', 'approved_by')) {
                $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null')->after('craftsman_id');
            }
            if (!Schema::hasColumn('workshop_adjustments', 'status')) {
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('approved_by');
            }
            if (!Schema::hasColumn('workshop_adjustments', 'notes')) {
                $table->text('notes')->nullable()->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workshop_adjustments', function (Blueprint $table) {
            $table->dropColumn([
                'item_id',
                'workshop_location',
                'adjustment_type',
                'quantity',
                'adjustment_date',
                'reference_number',
                'reason',
                'craftsman_id',
                'approved_by',
                'status',
                'notes'
            ]);
        });
    }
};