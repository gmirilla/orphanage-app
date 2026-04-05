<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Fix donations.donor_id missing foreign key constraint
        Schema::table('donations', function (Blueprint $table) {
            $table->unsignedBigInteger('donor_id')->change();
            $table->foreign('donor_id')->references('id')->on('donors')->onDelete('cascade');
        });

        // Fix child_room_assignments.room_allocation_id missing foreign key constraint
        Schema::table('child_room_assignments', function (Blueprint $table) {
            $table->unsignedBigInteger('room_allocation_id')->change();
            $table->foreign('room_allocation_id')->references('id')->on('room_allocations')->onDelete('cascade');
        });

        // Fix maintenance_requests.facility_id missing foreign key constraint
        Schema::table('maintenance_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('facility_id')->change();
            $table->foreign('facility_id')->references('id')->on('facilities')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropForeign(['donor_id']);
        });

        Schema::table('child_room_assignments', function (Blueprint $table) {
            $table->dropForeign(['room_allocation_id']);
        });

        Schema::table('maintenance_requests', function (Blueprint $table) {
            $table->dropForeign(['facility_id']);
        });
    }
};
