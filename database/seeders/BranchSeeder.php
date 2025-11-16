<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branch= Branch::create([
            'name' => 'Main Office',
            'branch_code' => '001',
            'phone' => '12121212',
            'email' => 'brabchen@gmail.com',
            'user_id' => 1
        ]);

        User::where('id', 1) // Update specific user (e.g., admin)
            ->update(['branch_id' => $branch->id]);
    }
}
