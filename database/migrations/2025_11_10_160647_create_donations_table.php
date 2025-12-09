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
      Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donor_id')->constrained('donors')->onDelete('cascade');
            $table->enum('donation_type', ['cash', 'material', 'service']); // Monetary, In-kind, Services
            $table->decimal('amount', 12, 2)->nullable();
            $table->text('description')->nullable();
            $table->string('currency', 3)->default('NGN');
            $table->date('donation_date');
            $table->enum('status', ['pledged', 'received', 'cancelled'])->default('received');
            $table->string('receipt_number')->unique();
            $table->text('notes')->nullable();
            $table->foreignId('recorded_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
