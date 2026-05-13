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
        Schema::table('reports', function (Blueprint $table) {
            $table->string('title');
            $table->string('report_type'); // weekly, monthly, quarterly, annual, incident, other
            $table->string('classification'); // child_welfare, facility, financial, staff, volunteer, operational, other
            $table->longText('content')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_original_name')->nullable();
            $table->string('status')->default('draft'); // draft, submitted, approved, rejected
            $table->date('period_start')->nullable();
            $table->date('period_end')->nullable();
            $table->foreignId('submitted_by')->constrained('users');
            $table->timestamp('submitted_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users');
            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_notes')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropForeign(['submitted_by']);
            $table->dropForeign(['reviewed_by']);
            $table->dropColumn([
                'title', 'report_type', 'classification', 'content',
                'file_path', 'file_original_name', 'status',
                'period_start', 'period_end', 'submitted_by', 'submitted_at',
                'reviewed_by', 'reviewed_at', 'review_notes',
            ]);
        });
    }
};
