<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use phpDocumentor\Reflection\Types\Nullable;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

            Schema::create('transactions', function (Blueprint $table) {
                $table->id();

                // Foreign keys with indexes
                $table->unsignedBigInteger('debit_account_id');
                $table->unsignedBigInteger('credit_account_id');
                $table->decimal('amount', 15, 2);

                // Nullable relationships
                $table->unsignedBigInteger('loan_id')->nullable()->index();
                $table->unsignedBigInteger('customer_id')->nullable()->index();
                $table->unsignedBigInteger('collection_id')->nullable()->index();

                // Transaction metadata
                $table->unsignedBigInteger('branch_id')->nullable()->index();
                $table->date('transaction_date')->useCurrent()->index();
                $table->string('transaction_type', 50)->index();
                $table->text('description')->nullable();

                // Approval workflow
                $table->string('status', 20)->default('pending')->index();
                $table->unsignedBigInteger('created_by')->index();
                $table->unsignedBigInteger('approved_by')->nullable()->index();

                $table->timestamps();

                // Foreign key constraints
                $table->foreign('debit_account_id')->references('id')->on('accounts');
                $table->foreign('credit_account_id')->references('id')->on('accounts');
                $table->foreign('loan_id')->references('id')->on('loan');
                $table->foreign('customer_id')->references('id')->on('customers');
                $table->foreign('branch_id')->references('id')->on('branches');
                $table->foreign('collection_id')->references('id')->on('collections');

                // Composite indexes for common query patterns
                $table->index(['branch_id', 'transaction_date']);
                $table->index(['transaction_type', 'transaction_date']);
                $table->index(['status', 'created_at']);
                $table->index('debit_account_id');
                $table->index('credit_account_id');

            });



    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
