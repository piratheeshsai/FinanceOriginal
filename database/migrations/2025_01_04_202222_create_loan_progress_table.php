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
        Schema::create('loan_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->constrained('loan')->onDelete('cascade'); // Foreign key to loan table
            $table->decimal('total_amount', 15, 2);
            $table->decimal('balance', 15, 2); // Remaining balance (total amount - paid amount)
            $table->decimal('total_paid_amount', 15, 2)->default(0); // Amount paid so far

            $table->date('last_due_date')->nullable(); // Last due date (final payment date)
            $table->timestamps();

            $table->index('loan_id'); 
            $table->index('balance');
            $table->index('last_due_date');

            // Recommended composite indexes
            $table->index(['loan_id', 'balance']);
            $table->index(['last_due_date', 'balance']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_progress');
    }
};
