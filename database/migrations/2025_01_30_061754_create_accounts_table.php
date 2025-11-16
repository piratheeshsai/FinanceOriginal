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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable(); // Foreign key to branches table
            $table->string('account_number', 50)->unique(); // Unique account number
            $table->string('account_name', 100); // Name of the account
            $table->decimal('balance', 15, 2)->default(0);
            $table->string('category')->nullable(); // Account balance
            $table->string('type', 50); // Type of account (e.g., capital, cashier, profit, loan)
            $table->timestamps(); // created_at and updated_at timestamps

            // Foreign key constraint
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');

            $table->index('branch_id'); // Foreign key index
            $table->index('account_number'); // Already unique but explicit index helps
            $table->index('type'); // Critical for filtering by account type
            $table->index('balance'); // Important for financial reporting

            // Recommended composite indexes
            $table->index(['branch_id', 'type']); // For branch-specific account type queries
            $table->index(['type', 'balance']); // For financial analysis by account type
            $table->index(['category', 'type']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
