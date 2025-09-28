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
        // Create invoices table if it doesn't exist
        if (!Schema::hasTable('invoices')) {
            Schema::create('invoices', function (Blueprint $table) {
                $table->id();
                $table->foreignId('customer_id')->nullable()->constrained()->onDelete('set null');
                $table->foreignId('sales_assistant_id')->nullable()->constrained()->onDelete('set null');
                $table->string('invoice_number')->unique();
                $table->date('invoice_date')->nullable();
                $table->decimal('total_amount', 15, 2)->default(0);
                $table->decimal('tax_amount', 15, 2)->default(0);
                $table->decimal('discount_amount', 15, 2)->default(0);
                $table->string('status')->default('draft');
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        } else {
            // Fix invoices table
            Schema::table('invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('invoices', 'invoice_number')) {
                $table->string('invoice_number')->unique()->after('id');
            }
            if (!Schema::hasColumn('invoices', 'invoice_date')) {
                $table->date('invoice_date')->nullable()->after('invoice_number');
            }
            if (!Schema::hasColumn('invoices', 'total_amount')) {
                $table->decimal('total_amount', 15, 2)->default(0)->after('invoice_date');
            }
            if (!Schema::hasColumn('invoices', 'tax_amount')) {
                $table->decimal('tax_amount', 15, 2)->default(0)->after('total_amount');
            }
            if (!Schema::hasColumn('invoices', 'discount_amount')) {
                $table->decimal('discount_amount', 15, 2)->default(0)->after('tax_amount');
            }
            if (!Schema::hasColumn('invoices', 'status')) {
                $table->string('status')->default('draft')->after('discount_amount');
            }
            if (!Schema::hasColumn('invoices', 'notes')) {
                $table->text('notes')->nullable()->after('status');
            }
        });

        }

        // Create sales_orders table if it doesn't exist
        if (!Schema::hasTable('sales_orders')) {
            Schema::create('sales_orders', function (Blueprint $table) {
                $table->id();
                $table->foreignId('customer_id')->nullable()->constrained()->onDelete('set null');
                $table->foreignId('sales_assistant_id')->nullable()->constrained()->onDelete('set null');
                $table->string('order_number')->unique();
                $table->date('order_date');
                $table->date('delivery_date')->nullable();
                $table->decimal('total_amount', 15, 2)->default(0);
                $table->string('status')->default('pending');
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        } else {
            // Fix sales_orders table
            Schema::table('sales_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('sales_orders', 'customer_id')) {
                $table->foreignId('customer_id')->nullable()->constrained()->onDelete('set null')->after('id');
            }
            if (!Schema::hasColumn('sales_orders', 'sales_assistant_id')) {
                $table->foreignId('sales_assistant_id')->nullable()->constrained()->onDelete('set null')->after('customer_id');
            }
            if (!Schema::hasColumn('sales_orders', 'order_number')) {
                $table->string('order_number')->unique()->after('sales_assistant_id');
            }
            if (!Schema::hasColumn('sales_orders', 'order_date')) {
                $table->date('order_date')->after('order_number');
            }
            if (!Schema::hasColumn('sales_orders', 'delivery_date')) {
                $table->date('delivery_date')->nullable()->after('order_date');
            }
            if (!Schema::hasColumn('sales_orders', 'total_amount')) {
                $table->decimal('total_amount', 15, 2)->default(0)->after('delivery_date');
            }
            if (!Schema::hasColumn('sales_orders', 'status')) {
                $table->string('status')->default('pending')->after('total_amount');
            }
            if (!Schema::hasColumn('sales_orders', 'notes')) {
                $table->text('notes')->nullable()->after('status');
            }
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['invoice_number', 'invoice_date', 'total_amount', 'tax_amount', 'discount_amount', 'status', 'notes']);
        });

        Schema::table('sales_orders', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropForeign(['sales_assistant_id']);
            $table->dropColumn(['customer_id', 'sales_assistant_id', 'order_number', 'order_date', 'delivery_date', 'total_amount', 'status', 'notes']);
        });
    }
};