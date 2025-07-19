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
        Schema::create('medicalrecords', function (Blueprint $table) {
            $table->id();
            $table->integer('child_id');
            $table->string('allergy')->nullable();
            $table->string('medication')->nullable();
            $table->string('doctorname')->nullable();
            $table->string('doctorcontact')->nullable();
            $table->string('medicalnote')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicalrecords');
    }
};
