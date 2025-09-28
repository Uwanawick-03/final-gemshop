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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('item_code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category'); // Ring, Necklace, Earring, Bracelet, etc.
            $table->string('subcategory')->nullable();
            $table->string('material'); // Gold, Silver, Platinum, etc.
            $table->string('gemstone')->nullable(); // Diamond, Ruby, Sapphire, etc.
            $table->decimal('weight', 8, 3)->nullable(); // Weight in grams
            $table->string('size')->nullable(); // Ring size, chain length, etc.
            $table->decimal('purity', 5, 2)->nullable(); // Gold purity (18K, 22K, etc.)
            $table->decimal('cost_price', 15, 2);
            $table->decimal('selling_price', 15, 2);
            $table->decimal('wholesale_price', 15, 2)->nullable();
            $table->integer('current_stock')->default(0);
            $table->integer('minimum_stock')->default(0);
            $table->integer('maximum_stock')->nullable();
            $table->string('unit')->default('piece'); // piece, gram, carat, etc.
            $table->string('barcode')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_taxable')->default(true);
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
