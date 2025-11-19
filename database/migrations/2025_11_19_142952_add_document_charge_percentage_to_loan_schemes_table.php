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
        Schema::table('loan_schemes', function (Blueprint $table) {
             $table->decimal('document_charge_percentage', 5, 2)->nullable()->after('interest_rate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_schemes', function (Blueprint $table) {
             $table->dropColumn('document_charge_percentage');
        });
    }
};
