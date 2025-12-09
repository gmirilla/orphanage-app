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
   Schema::create('education_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->constrained('children')->onDelete('cascade');
            $table->string('school_name');
            $table->string('education_level'); // primary, secondary, etc
            $table->text('academic_progress');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('status')->default('enrolled'); // enrolled, completed, dropped
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('education_histories');
    }
};
