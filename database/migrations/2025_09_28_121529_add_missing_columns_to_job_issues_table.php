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
        Schema::table('job_issues', function (Blueprint $table) {
            // Only add columns that don't exist
            if (!Schema::hasColumn('job_issues', 'issue_type')) {
                $table->enum('issue_type', ['defect', 'delay', 'quality', 'material', 'other'])->after('craftsman_id');
            }
            if (!Schema::hasColumn('job_issues', 'priority')) {
                $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->after('issue_type');
            }
            if (!Schema::hasColumn('job_issues', 'issue_date')) {
                $table->date('issue_date')->after('priority');
            }
            if (!Schema::hasColumn('job_issues', 'description')) {
                $table->text('description')->after('issue_date');
            }
            if (!Schema::hasColumn('job_issues', 'status')) {
                $table->enum('status', ['open', 'in_progress', 'resolved', 'closed'])->default('open')->after('description');
            }
            if (!Schema::hasColumn('job_issues', 'assigned_to')) {
                $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null')->after('status');
            }
            if (!Schema::hasColumn('job_issues', 'resolved_by')) {
                $table->foreignId('resolved_by')->nullable()->constrained('users')->onDelete('set null')->after('assigned_to');
            }
            if (!Schema::hasColumn('job_issues', 'resolution_notes')) {
                $table->text('resolution_notes')->nullable()->after('resolved_by');
            }
            if (!Schema::hasColumn('job_issues', 'resolved_date')) {
                $table->date('resolved_date')->nullable()->after('resolution_notes');
            }
            if (!Schema::hasColumn('job_issues', 'estimated_completion')) {
                $table->date('estimated_completion')->nullable()->after('resolved_date');
            }
            if (!Schema::hasColumn('job_issues', 'actual_completion')) {
                $table->date('actual_completion')->nullable()->after('estimated_completion');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_issues', function (Blueprint $table) {
            $table->dropColumn([
                'job_number',
                'item_id',
                'issue_type',
                'priority',
                'issue_date',
                'description',
                'status',
                'assigned_to',
                'resolved_by',
                'resolution_notes',
                'resolved_date',
                'estimated_completion',
                'actual_completion'
            ]);
        });
    }
};