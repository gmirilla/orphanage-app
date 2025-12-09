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
        // Admission logs
        Schema::create('admission_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->constrained('children')->onDelete('cascade');
            $table->text('intake_record');
            $table->string('source_details');
            $table->json('supporting_documents')->nullable();
            $table->text('medical_history')->nullable();
            $table->text('social_history')->nullable();
            $table->foreignId('processed_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admission_logs');
    }
};
