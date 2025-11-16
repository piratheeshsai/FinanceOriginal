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
        Schema::create('payment_supplier_pivot', function (Blueprint $table) {
            $table->foreignId('payment_id')->constrained();
            $table->foreignId('supplier_id')->constrained();
            $table->decimal('amount', 10, 2); // Read-only for salary
            $table->primary(['payment_id', 'supplier_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_supplier_pivot');
    }
};
