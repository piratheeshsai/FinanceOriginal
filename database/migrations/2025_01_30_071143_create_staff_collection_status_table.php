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
        Schema::create('staff_collection_status', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')->constrained('collections')->onDelete('cascade');
            $table->foreignId('transfers_id')->nullable()->constrained('transfers')->onDelete('cascade');
            $table->enum('status', ['Pending', 'Waiting to Accept', 'Transferred'])->default('Pending');
            $table->timestamps();


            $table->index('collection_id'); // Already created by foreignId() but explicit is good
            $table->index('transfers_id'); // For transfer-related queries

            // Status tracking index
            $table->index('status'); // Critical for filtering by status

            // Recommended composite indexes
            $table->index(['collection_id', 'status']); // For collection status checks
            $table->index(['transfers_id', 'status']); // For transfer status monitoring
            $table->index(['status', 'created_at']); // For status change timeline analysis
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_collection_status');
    }
};
