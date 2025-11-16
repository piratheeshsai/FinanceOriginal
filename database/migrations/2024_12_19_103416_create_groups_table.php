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
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('group_code')->unique(); // Unique group code
            $table->unsignedBigInteger('group_leader')->nullable(); // Group leader ID, nullable
            $table->unsignedBigInteger('center_id'); // Center ID
            $table->unsignedBigInteger('creator_id'); // Creator ID
            $table->unsignedBigInteger('branch_id'); // Branch ID
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('group_leader')->references('id')->on('customers')->onDelete('set null');
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('center_id')->references('id')->on('centers')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');

            // Optional: Adding indexes for better performance
            $table->index('group_leader');
            $table->index('center_id');
            $table->index('branch_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groups');
    }
};
