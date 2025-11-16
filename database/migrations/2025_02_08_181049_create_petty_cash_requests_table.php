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
        // Ensure petty_cash_types table is created first
        Schema::create('petty_cash_types', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->timestamps();
        });

        // Create petty_cash_requests table
        Schema::create('petty_cash_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained();
            $table->foreignId('account_id')->constrained();
            $table->decimal('amount', 15, 2);
            $table->foreignId('type_id')->constrained('petty_cash_types');
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->string('rejection_reason')->nullable();
            $table->foreignId('request_employee')->constrained('users');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->text('attachments')->nullable();
            $table->timestamps();
        });

        // Update transactions table
       
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['request_id']);
            $table->dropColumn('request_id');
        });
        Schema::dropIfExists('petty_cash_requests');
        Schema::dropIfExists('petty_cash_types'); // Drop this table last
    }
};
