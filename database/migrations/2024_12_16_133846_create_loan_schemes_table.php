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
        Schema::create('loan_schemes', function (Blueprint $table) {
            $table->id();
            $table->string('loan_name');
            $table->enum('loan_type', ['group', 'individual']);
            $table->decimal('interest_rate', 5, 2); // e.g., 5.5%
            $table->enum('collecting_duration', ['daily', 'weekly', 'monthly']);
            $table->integer('loan_term'); // Number of repayment cycles
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_schemes');
    }
};
