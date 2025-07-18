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
            $table->string('fname');
            $table->string('mname');
            $table->string('lname');
            $table->date('dateofbirth')->nullable();
            $table->string('gender');
            $table->string('birthplace')->nullable();
            $table->integer('nationalityid');
            $table->string('note')->nullable();
            $table->string('identificationtype')->nullable();
            $table->string('identificationno')->nullable();
            $table->date('admissiondate')->nullable();
            $table->string('profilephoto')->nullable();
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
