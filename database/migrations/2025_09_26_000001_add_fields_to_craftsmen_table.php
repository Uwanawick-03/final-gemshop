<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('craftsmen', function (Blueprint $table) {
            if (!Schema::hasColumn('craftsmen', 'craftsman_code')) {
                $table->string('craftsman_code')->unique()->after('id');
            }
            if (!Schema::hasColumn('craftsmen', 'first_name')) {
                $table->string('first_name')->after('craftsman_code');
            }
            if (!Schema::hasColumn('craftsmen', 'last_name')) {
                $table->string('last_name')->after('first_name');
            }
            if (!Schema::hasColumn('craftsmen', 'email')) {
                $table->string('email')->nullable()->unique()->after('last_name');
            }
            if (!Schema::hasColumn('craftsmen', 'phone')) {
                $table->string('phone')->after('email');
            }
            if (!Schema::hasColumn('craftsmen', 'address')) {
                $table->text('address')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('craftsmen', 'city')) {
                $table->string('city')->nullable()->after('address');
            }
            if (!Schema::hasColumn('craftsmen', 'country')) {
                $table->string('country')->nullable()->after('city');
            }
            if (!Schema::hasColumn('craftsmen', 'gender')) {
                $table->enum('gender', ['male','female','other'])->nullable()->after('country');
            }
            if (!Schema::hasColumn('craftsmen', 'national_id')) {
                $table->string('national_id')->nullable()->after('gender');
            }
            if (!Schema::hasColumn('craftsmen', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('national_id');
            }
            if (!Schema::hasColumn('craftsmen', 'joined_date')) {
                $table->date('joined_date')->nullable()->after('date_of_birth');
            }
            if (!Schema::hasColumn('craftsmen', 'primary_skill')) {
                $table->string('primary_skill')->nullable()->after('joined_date');
            }
            if (!Schema::hasColumn('craftsmen', 'skills')) {
                $table->json('skills')->nullable()->after('primary_skill');
            }
            if (!Schema::hasColumn('craftsmen', 'hourly_rate')) {
                $table->decimal('hourly_rate', 10, 2)->nullable()->after('skills');
            }
            if (!Schema::hasColumn('craftsmen', 'commission_rate')) {
                $table->decimal('commission_rate', 5, 2)->nullable()->after('hourly_rate');
            }
            if (!Schema::hasColumn('craftsmen', 'employment_status')) {
                $table->enum('employment_status', ['active','inactive','terminated','on_leave'])->default('active')->after('commission_rate');
            }
            if (!Schema::hasColumn('craftsmen', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('employment_status');
            }
            if (!Schema::hasColumn('craftsmen', 'notes')) {
                $table->text('notes')->nullable()->after('is_active');
            }
        });
    }

    public function down(): void
    {
        Schema::table('craftsmen', function (Blueprint $table) {
            // Optional: drop columns if needed
            // $table->dropColumn([...]);
        });
    }
};
