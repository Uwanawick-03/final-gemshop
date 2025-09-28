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
        Schema::table('finished_good_transfers', function (Blueprint $table) {
            // Only add columns that don't exist
            if (!Schema::hasColumn('finished_good_transfers', 'item_id')) {
                $table->foreignId('item_id')->constrained()->onDelete('cascade')->after('id');
            }
            if (!Schema::hasColumn('finished_good_transfers', 'craftsman_id')) {
                $table->foreignId('craftsman_id')->nullable()->constrained()->onDelete('set null')->after('item_id');
            }
            if (!Schema::hasColumn('finished_good_transfers', 'from_workshop')) {
                $table->string('from_workshop')->after('craftsman_id');
            }
            if (!Schema::hasColumn('finished_good_transfers', 'to_location')) {
                $table->string('to_location')->after('from_workshop');
            }
            if (!Schema::hasColumn('finished_good_transfers', 'quantity')) {
                $table->integer('quantity')->after('to_location');
            }
            if (!Schema::hasColumn('finished_good_transfers', 'transfer_date')) {
                $table->date('transfer_date')->after('quantity');
            }
            if (!Schema::hasColumn('finished_good_transfers', 'reference_number')) {
                $table->string('reference_number')->unique()->after('transfer_date');
            }
            if (!Schema::hasColumn('finished_good_transfers', 'quality_check_passed')) {
                $table->boolean('quality_check_passed')->default(false)->after('reference_number');
            }
            if (!Schema::hasColumn('finished_good_transfers', 'quality_check_by')) {
                $table->foreignId('quality_check_by')->nullable()->constrained('users')->onDelete('set null')->after('quality_check_passed');
            }
            if (!Schema::hasColumn('finished_good_transfers', 'status')) {
                $table->enum('status', ['pending', 'quality_check', 'completed', 'rejected'])->default('pending')->after('quality_check_by');
            }
            if (!Schema::hasColumn('finished_good_transfers', 'transferred_by')) {
                $table->foreignId('transferred_by')->nullable()->constrained('users')->onDelete('set null')->after('status');
            }
            if (!Schema::hasColumn('finished_good_transfers', 'received_by')) {
                $table->foreignId('received_by')->nullable()->constrained('users')->onDelete('set null')->after('transferred_by');
            }
            if (!Schema::hasColumn('finished_good_transfers', 'notes')) {
                $table->text('notes')->nullable()->after('received_by');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('finished_good_transfers', function (Blueprint $table) {
            $table->dropColumn([
                'item_id',
                'craftsman_id',
                'from_workshop',
                'to_location',
                'quantity',
                'transfer_date',
                'reference_number',
                'quality_check_passed',
                'quality_check_by',
                'status',
                'transferred_by',
                'received_by',
                'notes'
            ]);
        });
    }
};