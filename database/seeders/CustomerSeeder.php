<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            [
                'center_id' => 1,
                'full_name' => 'John Doe',
                'customer_no' => 'CUST1001',
                'nic' => '1235456789V',
                'permanent_address' => '123 Main Street, City',
                'customer_phone' => '0771234567',
                'date_of_birth' => '1990-01-01',
                'gender' => 'Male',
                'civil_status' => 'Married',
                'occupation' => 'Engineer',
                'spouse_name' => 'Jane Doe',
                'spouse_nic' => '9876254321V',
                'spouse_occupation' => 'Teacher',
                'spouse_age' => 30,
                'home_phone' => '0115678901',
                'family_members' => 4,
                'income_earners' => 2,
                'family_income' => '150000',
                'photo' => 'path/to/phloto1.jpg',
                'nic_copy' => 'path/to/nic_cop2y1.pdf',
            ],

           


        ];

        foreach ($customers as $customerData) {
            $customer = new Customer();
            foreach ($customerData as $key => $value) {
                $customer->$key = $value;
            }
            $customer->save();
        }
    }
}
