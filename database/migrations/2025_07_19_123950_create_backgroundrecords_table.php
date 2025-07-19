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
        Schema::create('backgroundrecords', function (Blueprint $table) {
            $table->id();
            $table->integer('child_id');
            $table->string('pguardianname')->nullable();
            $table->string('pguardiancontact')->nullable();
            $table->string('addmissionreason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backgroundrecords');
    }
};
