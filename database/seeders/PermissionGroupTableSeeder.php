<?php

namespace Database\Seeders;

use App\Models\PermissionGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionGroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    $permissionGroups =[
        [
            'name' => 'branch'

        ],
        [
            'name' => 'user',

        ],
        [
            'name' => 'Notifications',

        ],
        [
            'name' => 'Branch Access',

        ],
        [
            'name' => 'Loan Collection',

        ],
        [
            'name' => 'Accounts Collection',

        ],

        [
            'name' => 'Dashboard',
        ],


        [
            'name' => 'Fund Transfer',
        ],

        [
            'name' => 'Approval ',
        ],
        [
            'name' => 'Customers',
        ],
        [
            'name' => 'Schemes',
        ],
        [
            'name' => 'Groups',
        ],

        [
            'name' => 'Loan',
        ],
        [
            'name' => 'Collection',
        ],

        [
            'name' => 'Accounting',
        ],

        [
            'name' => 'Reports',
        ],
        [
            'name' => 'Activity log',
        ],

        [
            'name' => 'Settings',
        ],


        [
            'name' => 'Super Admin',
        ],

        ];

        echo '----------------------------------------------------------------'."\n";
        echo '---------Creating Permission Groups---------'."\n";
        foreach ($permissionGroups as $key => $value) {
            $permissionGroup = new PermissionGroup();
            $permissionGroup->name = $value['name'];
            $permissionGroup->save();

            echo "--------Created Permission Group Name=> $permissionGroup->name-----"."\n";
        }
        echo '---------Create completed---------'."\n";
    }
}
