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
      Schema::create('child_room_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->constrained('children')->onDelete('cascade');
            $table->foreignId('room_allocation_id')->constrained('room_allocations')->onDelete('cascade');
            $table->date('assigned_date');
            $table->date('unassigned_date')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('assigned_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('child_room_assignments');
    }
};
