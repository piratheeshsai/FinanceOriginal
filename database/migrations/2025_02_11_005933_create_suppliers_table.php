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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_category_id')->constrained();
            $table->string('name');
            $table->string('nic')->nullable();
            $table->decimal('salary', 10, 2)->nullable(); // Only for salary category
            $table->string('bank_account_name')->nullable();
            $table->string('bank_account_number')->nullable(); // Encrypted
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
