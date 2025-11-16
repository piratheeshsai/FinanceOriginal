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
        Schema::create('centers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->onDelete('cascade');
            $table->string('center_code');
            $table->string('name');
            $table->timestamps();

            $table->index('branch_id'); // Foreign key (automatically created but explicit is good)
           
            $table->index('name'); // For name-based searches

            // Recommended composite index
            $table->index(['branch_id', 'name']); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('centers');
    }
};
