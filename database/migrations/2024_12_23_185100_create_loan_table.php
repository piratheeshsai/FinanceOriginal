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
        Schema::create('loan', function (Blueprint $table) {
            $table->id();
            $table->string('loan_number')->unique();  // Unique loan number
            $table->enum('loan_type', ['individual', 'group']); // Loan type: individual or group
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('cascade'); // Nullable for group loans
            $table->foreignId('group_id')->nullable()->constrained('groups')->onDelete('restrict'); // For group loans only
            $table->foreignId('center_id')->constrained('centers')->onDelete('restrict'); // Center of the loan
            $table->foreignId('scheme_id')->constrained('loan_schemes')->onDelete('restrict'); // Loan scheme details
            $table->decimal('loan_amount', 15, 2); // Loan amount
            $table->decimal('document_charge', 15, 2)->nullable(); // Loan amount charge
            $table->date('start_date')->nullable(); // Set after approval
            $table->unsignedBigInteger('loan_creator_name'); // Name of the loan creator (authenticated user)
            $table->timestamps();
            $table->softDeletes();



             $table->foreign('loan_creator_name')->references('id')->on('users')->onDelete('cascade');
            // Composite indexes
            $table->index(['loan_type', 'start_date']);
            $table->index(['center_id', 'created_at']);
            $table->index(['scheme_id', 'loan_amount']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan');
    }
};
