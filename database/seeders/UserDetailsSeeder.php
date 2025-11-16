<?php

namespace Database\Seeders;

use App\Models\UserDetails;
use Illuminate\Database\Seeder;

class UserDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seeding user details
        UserDetails::create([
           'user_id' => 1,  // Assuming this user_id exists in the 'users' table
            'employee_id' => 'EMP001',
            'nic_no' => '2121212112',
            'profile_photo' => 'path/to/photo.jpg', // Optional field
            'address' => '123 Main St, Some City, Some Country',
            'phone_number' => '075745454',
            'gender' => 'male',
            'age' => 30
        ]);

        
    }
}
