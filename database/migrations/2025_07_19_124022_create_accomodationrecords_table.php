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
        Schema::create('accomodationrecords', function (Blueprint $table) {
            $table->id();
            $table->integer('child_id');
            $table->integer('staff_id');
            $table->string('dormroom')->nullable();
            $table->string('accomodationnotes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accomodationrecords');
    }
};
