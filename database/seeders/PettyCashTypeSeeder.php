<?php

namespace Database\Seeders;

use App\Models\PettyCashType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PettyCashTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //


        $PettyCashType = [



            ['type' => 'Office Supplies'],
            ['type' => 'Local travel'],
            ['type' => 'Marketing'],
            ['type' => 'Employee Advance '],
            ['type' => 'Maintenance'],

        ];

        // Insert data into the payment_categories table
        foreach ($PettyCashType as $category) {
            PettyCashType::create($category);
        }
    }
}
