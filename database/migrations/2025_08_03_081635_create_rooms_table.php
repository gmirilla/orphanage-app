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
        Schema::create('rooms', function (Blueprint $table) {

            $table->id();
            $table->string('roomnumber')->unique();
            $table->string('roomtype'); 
            $table->string('roomclassification')->nullable();
            $table->integer('capacity'); // number of beds
            $table->enum('status', ['available', 'occupied', 'maintenance'])->default('available');
            $table->string('roomnotes')->nullable();
            $table->unsignedBigInteger('staffincharge')->nullable();
            $table->timestamps();
            $table->foreign('staffincharge')->references('id')->on('staff')->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
