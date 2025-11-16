<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('daily_cash_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained();
            $table->foreignId('account_id')->constrained();
            $table->date('date');
            $table->decimal('counted_amount', 15, 2)->default(0);
            $table->decimal('system_amount', 15, 2)->default(0);
            $table->decimal('difference', 15, 2)->default(0);
            $table->text('remarks')->nullable();
            $table->foreignId('counted_by')->constrained('users');
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            // Each account can only have one daily summary per date
            $table->unique(['branch_id', 'account_id', 'date']);
        });

        Schema::create('cash_denominations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daily_cash_summary_id')->constrained()->onDelete('cascade');
            $table->decimal('value', 8, 2); // The denomination (5000, 1000, etc)
            $table->integer('count')->default(0); // How many of this denomination
            $table->boolean('is_coin')->default(false); // Whether this is a coin or note
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cash_denominations');
        Schema::dropIfExists('daily_cash_summaries');
    }
};
