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
        Schema::create('collection_invoice', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique(); // Unique Invoice ID
            $table->foreignId('loan_id')->constrained('loan')->onDelete('cascade'); // Link to Loan
            $table->foreignId('collection_id')->constrained('collections')->onDelete('cascade'); // Link to Collection
            $table->decimal('collected_amount', 10, 2);
            $table->enum('type', ['a4', 'pos']); // Type of Invoice
            $table->timestamps();


            $table->index('loan_id');
            $table->index('collection_id');

            // Important query optimization indexes
            $table->index('type'); // For filtering by invoice type
            $table->index('collected_amount'); // For amount-based reporting

            // Recommended composite indexes
            $table->index(['loan_id', 'type']); // For loan-specific invoice type queries
            $table->index(['collection_id', 'created_at']); // For collection timeline analysis
            $table->index(['type', 'collected_amount']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collection_invoice');
    }
};
