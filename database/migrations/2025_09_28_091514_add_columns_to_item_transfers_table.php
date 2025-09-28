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
        Schema::table('item_transfers', function (Blueprint $table) {
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->string('reference_number')->unique();
            $table->string('from_location');
            $table->string('to_location');
            $table->decimal('quantity', 10, 3);
            $table->date('transfer_date');
            $table->enum('reason', ['restock', 'sale_transfer', 'repair', 'display', 'storage', 'damage', 'other'])->default('other');
            $table->enum('status', ['pending', 'in_transit', 'completed', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->foreignId('transferred_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('received_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('received_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('item_transfers', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
            $table->dropForeign(['transferred_by']);
            $table->dropForeign(['received_by']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            
            $table->dropColumn([
                'item_id',
                'reference_number',
                'from_location',
                'to_location',
                'quantity',
                'transfer_date',
                'reason',
                'status',
                'notes',
                'transferred_by',
                'received_by',
                'received_at',
                'created_by',
                'updated_by'
            ]);
        });
    }
};