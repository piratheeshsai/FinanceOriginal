<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;


use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     *@return void
     */

    // public function run(): void
    // {
    //     $this->call(PermissionGroupTableSeeder::class);
    //     $this->call(RoleTableSeeder::class);
    //     $this->call(UserSeeder::class);
    //     $this->call(BranchSeeder::class);
    //     $this->call(UserDetailsSeeder::class);
    //     $this->call(CenterSeeder::class);


    // }


    public function run(){
        $this->call([
            PermissionGroupTableSeeder::class,
            PermissionTableSeeder::class,
            RoleTableSeeder::class,
            userSeeder::class,
            BranchSeeder::class,
            UserDetailsSeeder::class,
            CenterSeeder::class,
            CustomerSeeder::class,
            TypeSeeder::class,
            CitySeeder::class,
            LoanSchemesSeeder::class,
            LoanSeeder::class,
            CapitalAccountSeeder::class,
            PaymentCategorySeeder::class,
            PettyCashTypeSeeder::class,

        ]);
    }
}

