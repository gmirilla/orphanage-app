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
     Schema::create('children', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('gender');
            $table->date('date_of_birth');
            $table->text('background_summary');
            $table->date('admission_date');
            $table->string('admission_source'); // hospital, social_services, etc
            $table->string('guardianship_status')->nullable(); // orphan, abandoned, etc
            $table->text('guardian_info')->nullable();
            $table->string('profile_photo')->nullable();
            $table->string('blood_group')->nullable();
            $table->decimal('height_cm', 5, 2)->nullable();
            $table->decimal('weight_kg', 5, 2)->nullable();
            $table->string('special_needs')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('admitted_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('children');
    }
};
