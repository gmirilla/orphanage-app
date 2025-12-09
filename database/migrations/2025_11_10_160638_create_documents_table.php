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
      Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('type'); // birth_certificate, medical_record, etc
            $table->string('file_path');
            $table->string('file_name');
            $table->string('file_size');
            $table->string('mime_type');
            $table->text('description')->nullable();
            $table->json('tags')->nullable();
            $table->string('related_type')->nullable(); // child, staff, volunteer, donation
            $table->unsignedBigInteger('related_id')->nullable();
            $table->enum('visibility', ['public', 'private', 'restricted'])->default('private');
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
