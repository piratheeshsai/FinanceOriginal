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
        Schema::create('loan_approvals', function (Blueprint $table) {
            $table->id();
            // Update the reference to the correct singular table name
            $table->foreignId('loan_id')->constrained('loan')->onDelete('cascade');  // Referring to the 'loan' table
            $table->enum('status', ['Pending', 'Approved', 'Rejected', 'Active', 'Completed'])->default('Pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('active_at')->nullable();
            $table->string('rejection_reason')->nullable();
            $table->timestamps();

            $table->index('loan_id');
            $table->index('status'); // Critical for filtering by approval status
            $table->index('approved_by'); // For tracking who approved loans
            $table->index('approved_at'); // For approval timeline reporting
            $table->index('active_at'); // For activation timeline reporting

            // Recommended composite indexes
            $table->index(['status', 'approved_at']); // For status change reporting
            $table->index(['loan_id', 'status']); // For checking loan approval status
            $table->index(['approved_by', 'approved_at']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_approvals');
    }
};
