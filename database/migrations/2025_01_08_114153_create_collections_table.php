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
        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->constrained('loan')->onDelete('cascade');
            $table->foreignId('collector_id')->constrained('users')->onDelete('cascade');
            $table->decimal('collected_amount', 12, 2);
            $table->decimal('principal_amount', 15, 2)->default(0);
            $table->decimal('interest_amount', 15, 2)->default(0);
            $table->date('collection_date');
            $table->string('collection_method');
            $table->decimal('penalty_collected', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
              $table->softDeletes();


            $table->index('loan_id'); // Already created by foreignId() but explicit is good
            $table->index('collector_id'); // For staff performance tracking

            // Important query optimization indexes
            $table->index('collection_date'); // Critical for date-based reporting
            $table->index('collection_method'); // For payment method analysis

            // Financial reporting indexes
            $table->index('collected_amount'); // For collection amount analysis
            $table->index('principal_amount'); // For principal recovery tracking
            $table->index('interest_amount'); // For interest income analysis

            // Recommended composite indexes
            $table->index(['loan_id', 'collection_date']); // For loan payment history
            $table->index(['collector_id', 'collection_date']); // For collector performance
            $table->index(['collection_date', 'collection_method']); // For payment trends
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collections');
    }
};
