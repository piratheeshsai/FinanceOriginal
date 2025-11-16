<?php

namespace Database\Seeders;

use App\Models\PaymentCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //


        $paymentCategories = [
            ['name' => 'salary'],
            ['name' => 'Supplier Payments'],
            ['name' => 'Utilities'],
           
            ['name' => 'Rent'],
            ['name' => 'Travel Expenses'],
            ['name' => 'Marketing'],
            ['name' => 'Maintenance'],

        ];

        // Insert data into the payment_categories table
        foreach ($paymentCategories as $category) {
            PaymentCategory::create($category);
        }
    }
}
