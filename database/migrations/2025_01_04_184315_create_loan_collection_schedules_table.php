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
        Schema::create('loan_collection_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->constrained('loan')->onDelete('cascade');
            $table->date('date');
            $table->string('description');
            $table->decimal('principal', 10, 2)->nullable();
            $table->decimal('interest', 10, 2)->nullable();
            $table->decimal('penalty', 10, 2)->nullable();
            $table->decimal('due', 10, 2);
            $table->decimal('paid', 10, 2)->nullable();
            $table->decimal('pending_due', 10, 2)->nullable();
            $table->decimal('total_due', 10, 2);
            $table->decimal('principal_due', 10, 2)->nullable();
            $table->enum('status', ['pending', 'Arrears', 'MissedRepayment', 'paid'])->default('pending');
            $table->timestamps();


            $table->index('loan_id');
            $table->index('date');
            $table->index('status');

           
            $table->index(['loan_id', 'date']);
            $table->index(['date', 'status']);
            $table->index(['status', 'total_due']);


            $table->index('total_due');
            $table->index(['loan_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_collection_schedules');
    }
};
