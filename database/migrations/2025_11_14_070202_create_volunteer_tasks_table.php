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
        Schema::create('volunteer_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('volunteer_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->date('scheduled_date');
            $table->time('start_time');
            $table->time('end_time')->nullable();
            $table->string('status')->default('assigned'); // assigned, in_progress, completed, cancelled
            $table->text('feedback')->nullable();
            $table->integer('rating')->nullable(); // 1-5 scale
            $table->foreignId('assigned_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('volunteer_tasks');
    }
};
