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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();

            $table->string('voucher_number')->unique();
            $table->enum('type', ['Payment', 'Petty cash', 'Loan Disbursement']);
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->date('date');
            $table->decimal('amount', 10, 2);
            $table->text('payee_details')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('account_id')->nullable(); // Made nullable

            // Loan-related fields
            $table->unsignedBigInteger('loan_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();

            $table->unsignedBigInteger('approved_by');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('branch_id');

            // Foreign keys
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('loan_id')->references('id')->on('loan')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
