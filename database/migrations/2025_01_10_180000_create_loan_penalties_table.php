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
        Schema::create('loan_penalties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->constrained('loan')->onDelete('restrict');
            $table->foreignId('loan_collection_schedules_id')->constrained('loan_collection_schedules')->onDelete('restrict');
            $table->decimal('penalty_amount', 15, 2);
            $table->date('penalty_date');
            $table->boolean('is_paid')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_penalties');
    }
};
