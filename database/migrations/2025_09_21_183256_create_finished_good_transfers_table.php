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
        Schema::create('finished_good_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->foreignId('craftsman_id')->nullable()->constrained()->onDelete('set null');
            $table->string('from_workshop');
            $table->string('to_location');
            $table->integer('quantity');
            $table->date('transfer_date');
            $table->string('reference_number')->unique();
            $table->boolean('quality_check_passed')->default(false);
            $table->foreignId('quality_check_by')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('status', ['pending', 'quality_check', 'completed', 'rejected'])->default('pending');
            $table->foreignId('transferred_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('received_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finished_good_transfers');
    }
};
