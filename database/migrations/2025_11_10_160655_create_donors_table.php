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
      Schema::create('donors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->enum('donor_type', ['individual', 'organization', 'corporate'])->default('individual');
            $table->string('tax_id')->nullable();
            $table->text('preferences')->nullable(); // preferred donation type, frequency, etc
            $table->enum('status', ['active', 'inactive', 'preferred'])->default('active');
            $table->foreignId('managed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donors');
    }
};
