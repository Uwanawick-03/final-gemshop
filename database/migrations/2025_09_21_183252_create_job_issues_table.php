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
        Schema::create('job_issues', function (Blueprint $table) {
            $table->id();
            $table->string('job_number')->unique();
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->foreignId('craftsman_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('issue_type', ['defect', 'delay', 'quality', 'material', 'other']);
            $table->enum('priority', ['low', 'medium', 'high', 'urgent']);
            $table->date('issue_date');
            $table->text('description');
            $table->enum('status', ['open', 'in_progress', 'resolved', 'closed'])->default('open');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('resolved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('resolution_notes')->nullable();
            $table->date('resolved_date')->nullable();
            $table->date('estimated_completion')->nullable();
            $table->date('actual_completion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_issues');
    }
};
