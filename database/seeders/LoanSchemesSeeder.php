<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LoanSchemesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        DB::table('loan_schemes')->insert([
            [
                'loan_name' => 'Personal Loan',
                'loan_type' => 'individual',
                'interest_rate' => 5.5,
                'collecting_duration' => 'monthly',
                'loan_term' => 12, // 12 months repayment period
            ],
           
        ]);
    }

}
