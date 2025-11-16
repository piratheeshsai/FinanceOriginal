<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo '----------------------------------------------------------------'."\n";
        echo '------------------------Role seeding-----------------------------'."\n";
        $role       = new Role();
        $role->name = 'Super Admin';
        $role->save();

        echo "------------------------Role Added=> $role->name-----------------------------"."\n";

        echo "------------------------assigning all permission to ' . $role->name .' -----------------------------"."\n";

        $permissions = Permission::get();

        foreach ($permissions as $key => $value) {
            $role->givePermissionTo($value->name);

            echo "------------------------Permission Added=> $value->name-----------------------------"."\n";
        }



        echo '------------------------Role seeding completed-----------------------------'."\n";
    }
}
