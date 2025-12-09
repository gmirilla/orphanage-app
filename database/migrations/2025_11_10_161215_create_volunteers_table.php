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
       Schema::create('volunteers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('volunteer_id')->unique();
            $table->date('registration_date');
            $table->date('approval_date')->nullable();
            $table->enum('status', ['pending', 'approved', 'suspended', 'inactive'])->default('pending');
            $table->text('skills')->nullable();
            $table->text('availability')->nullable();
            $table->text('background_check_info')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('volunteers');
    }
};
