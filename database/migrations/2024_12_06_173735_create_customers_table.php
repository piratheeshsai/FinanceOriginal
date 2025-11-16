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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('center_id');
            $table->string('full_name');
            $table->string('customer_no')->unique();
            $table->string('nic')->unique();
            $table->string('permanent_address');
            $table->string('living_address')->nullable();
            $table->string('permanent_city')->nullable();
            $table->string('living_city')->nullable();
            $table->string('customer_phone');
            $table->string('date_of_birth');
            $table->string('occupation');
            $table->string('gender');
            $table->string('civil_status');

            $table->string('spouse_name')->nullable();
            $table->string('spouse_nic')->nullable();
            $table->string('Spouse_phone')->nullable();
            $table->string('spouse_occupation')->nullable();
            $table->string('spouse_age')->nullable();


            $table->string('home_phone')->nullable();
            $table->string('family_members')->nullable();
            $table->string('income_earners')->nullable();
            $table->string('family_income')->nullable();




            $table->string('photo')->nullable();
            $table->string('nic_copy')->nullable();


            $table->timestamps();
            $table->foreign('center_id')->references('id')->on('centers')->onDelete('cascade');



            $table->index(['center_id', 'full_name']);
            $table->index('center_id');
            $table->index('full_name');
            $table->index('nic'); 

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
