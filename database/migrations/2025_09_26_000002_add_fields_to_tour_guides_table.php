<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tour_guides', function (Blueprint $table) {
            if (!Schema::hasColumn('tour_guides', 'guide_code')) {
                $table->string('guide_code')->unique()->after('id');
            }
            if (!Schema::hasColumn('tour_guides', 'first_name')) {
                $table->string('first_name')->after('guide_code');
            }
            if (!Schema::hasColumn('tour_guides', 'last_name')) {
                $table->string('last_name')->after('first_name');
            }
            if (!Schema::hasColumn('tour_guides', 'email')) {
                $table->string('email')->nullable()->unique()->after('last_name');
            }
            if (!Schema::hasColumn('tour_guides', 'phone')) {
                $table->string('phone')->after('email');
            }
            if (!Schema::hasColumn('tour_guides', 'address')) {
                $table->text('address')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('tour_guides', 'city')) {
                $table->string('city')->nullable()->after('address');
            }
            if (!Schema::hasColumn('tour_guides', 'country')) {
                $table->string('country')->nullable()->after('city');
            }
            if (!Schema::hasColumn('tour_guides', 'gender')) {
                $table->enum('gender', ['male','female','other'])->nullable()->after('country');
            }
            if (!Schema::hasColumn('tour_guides', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('gender');
            }
            if (!Schema::hasColumn('tour_guides', 'national_id')) {
                $table->string('national_id')->nullable()->after('date_of_birth');
            }
            if (!Schema::hasColumn('tour_guides', 'joined_date')) {
                $table->date('joined_date')->nullable()->after('national_id');
            }
            if (!Schema::hasColumn('tour_guides', 'languages')) {
                $table->json('languages')->nullable()->after('joined_date');
            }
            if (!Schema::hasColumn('tour_guides', 'service_areas')) {
                $table->json('service_areas')->nullable()->after('languages');
            }
            if (!Schema::hasColumn('tour_guides', 'license_number')) {
                $table->string('license_number')->nullable()->after('service_areas');
            }
            if (!Schema::hasColumn('tour_guides', 'license_expiry')) {
                $table->date('license_expiry')->nullable()->after('license_number');
            }
            if (!Schema::hasColumn('tour_guides', 'daily_rate')) {
                $table->decimal('daily_rate', 10, 2)->nullable()->after('license_expiry');
            }
            if (!Schema::hasColumn('tour_guides', 'employment_status')) {
                $table->enum('employment_status', ['active','inactive','terminated','on_leave'])->default('active')->after('daily_rate');
            }
            if (!Schema::hasColumn('tour_guides', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('employment_status');
            }
            if (!Schema::hasColumn('tour_guides', 'notes')) {
                $table->text('notes')->nullable()->after('is_active');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tour_guides', function (Blueprint $table) {
            // No-op safe down for now
        });
    }
};
