<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('banks', function (Blueprint $table) {
            if (!Schema::hasColumn('banks', 'bank_code')) {
                $table->string('bank_code')->unique()->after('id');
            }
            if (!Schema::hasColumn('banks', 'name')) {
                $table->string('name')->after('bank_code');
            }
            if (!Schema::hasColumn('banks', 'branch')) {
                $table->string('branch')->nullable()->after('name');
            }
            if (!Schema::hasColumn('banks', 'swift_code')) {
                $table->string('swift_code')->nullable()->after('branch');
            }
            if (!Schema::hasColumn('banks', 'account_number')) {
                $table->string('account_number')->nullable()->after('swift_code');
            }
            if (!Schema::hasColumn('banks', 'account_name')) {
                $table->string('account_name')->nullable()->after('account_number');
            }
            if (!Schema::hasColumn('banks', 'currency')) {
                $table->string('currency')->nullable()->after('account_name');
            }
            if (!Schema::hasColumn('banks', 'phone')) {
                $table->string('phone')->nullable()->after('currency');
            }
            if (!Schema::hasColumn('banks', 'email')) {
                $table->string('email')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('banks', 'address')) {
                $table->text('address')->nullable()->after('email');
            }
            if (!Schema::hasColumn('banks', 'city')) {
                $table->string('city')->nullable()->after('address');
            }
            if (!Schema::hasColumn('banks', 'country')) {
                $table->string('country')->nullable()->after('city');
            }
            if (!Schema::hasColumn('banks', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('country');
            }
            if (!Schema::hasColumn('banks', 'notes')) {
                $table->text('notes')->nullable()->after('is_active');
            }
        });
    }

    public function down(): void
    {
        Schema::table('banks', function (Blueprint $table) {
            // Safe no-op down
        });
    }
};
