<?php

namespace Database\Seeders;

use App\Models\PermissionGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions =[


        [
            'name' => 'branch Create',
            'permission_group_id' => PermissionGroup::where('name','branch')->first()->id

        ],
        [
            'name' => 'branch Update',
            'permission_group_id' => PermissionGroup::where('name','branch')->first()->id

        ],
        [
            'name' => 'branch Delete',
            'permission_group_id' => PermissionGroup::where('name','branch')->first()->id

        ],

        [
            'name' => 'Branch Fund Allocation',
            'permission_group_id' => PermissionGroup::where('name','branch')->first()->id

        ],
        [
            'name' => 'branch View',
            'permission_group_id' => PermissionGroup::where('name','branch')->first()->id

        ],
        [
            'name' => 'user View',
            'permission_group_id' => PermissionGroup::where('name','user')->first()->id

        ],
        [
            'name' => 'user Create',
            'permission_group_id' => PermissionGroup::where('name','user')->first()->id

        ],
        [
            'name' => 'user update',
            'permission_group_id' => PermissionGroup::where('name','user')->first()->id

        ],
        [
            'name' => 'User delete',
            'permission_group_id' => PermissionGroup::where('name','user')->first()->id

        ],
        [
            'name' => 'View All Users',
            'permission_group_id' => PermissionGroup::where('name','user')->first()->id

        ],
        [
            'name' => 'User status Access',
            'permission_group_id' => PermissionGroup::where('name','user')->first()->id

        ],

        [
            'name' => 'approve-loan',
            'permission_group_id' => PermissionGroup::where('name','Notifications')->first()->id

        ],
        [
            'name' => 'view all branches',
            'permission_group_id' => PermissionGroup::where('name','Branch Access')->first()->id

        ],
        [
            'name' => 'view own branch all data',
            'permission_group_id' => PermissionGroup::where('name','Branch Access')->first()->id

        ],

        [
            'name' => 'Collect Loan',
            'permission_group_id' => PermissionGroup::where('name','Loan Collection')->first()->id

        ],

        [
            'name' => 'Collection Transfer',
            'permission_group_id' => PermissionGroup::where('name','Loan Collection')->first()->id

        ],
        [
            'name' => 'Loan C.T approval',
            'permission_group_id' => PermissionGroup::where('name','Loan Collection')->first()->id

        ],

        [
            'name' => 'Collection Account Access',
            'permission_group_id' => PermissionGroup::where('name','Accounts Collection')->first()->id

        ],
        [
            'name' => 'approve petty cash transfer',
            'permission_group_id' => PermissionGroup::where('name','Approval')->first()->id

        ],
        [
            'name' => 'approve-payment',
            'permission_group_id' => PermissionGroup::where('name','Approval')->first()->id

        ],

        [
            'name' => 'Approve All Transfer',
            'permission_group_id' => PermissionGroup::where('name','Approval')->first()->id

        ],

        [
            'name' => 'New Loan Approval',
            'permission_group_id' => PermissionGroup::where('name','Approval')->first()->id
        ],

        [
            'name' => 'Collection Approval page',
            'permission_group_id' => PermissionGroup::where('name','Approval')->first()->id
        ],


        [
            'name' => 'Companies Fund Transfer',
            'permission_group_id' => PermissionGroup::where('name','Fund Transfer')->first()->id

        ],

        [
            'name' => 'View Accounts cards',
            'permission_group_id' => PermissionGroup::where('name','Dashboard')->first()->id

        ],

        [
            'name' => 'View Common Cards',
            'permission_group_id' => PermissionGroup::where('name','Dashboard')->first()->id

        ],



        [
            'name' => 'Monthly Collection Chart',
            'permission_group_id' => PermissionGroup::where('name','Dashboard')->first()->id

        ],
        [
            'name' => 'Account Overview Graph',
            'permission_group_id' => PermissionGroup::where('name','Dashboard')->first()->id

        ],

        [
            'name' => 'View All Customers',
            'permission_group_id' => PermissionGroup::where('name','Customers')->first()->id

        ],
        [
            'name' => 'Edit Customers',
            'permission_group_id' => PermissionGroup::where('name','Customers')->first()->id
        ],

        [
            'name' => 'Create Schemes',
            'permission_group_id' => PermissionGroup::where('name','Schemes')->first()->id
        ],
        [
            'name' => 'View Schemes',
            'permission_group_id' => PermissionGroup::where('name','Schemes')->first()->id
        ],
        [
            'name' => 'Delete Schemes',
            'permission_group_id' => PermissionGroup::where('name','Schemes')->first()->id
        ],
        [
            'name' => 'Edit Schemes',
            'permission_group_id' => PermissionGroup::where('name','Schemes')->first()->id
        ],
        [
            'name' => 'View All Branch Groups',
            'permission_group_id' => PermissionGroup::where('name','Groups')->first()->id
        ],
        [
            'name' => 'Delete Groups',
            'permission_group_id' => PermissionGroup::where('name','Groups')->first()->id
        ],
        [
            'name' => 'Edit Groups',
            'permission_group_id' => PermissionGroup::where('name','Groups')->first()->id
        ],
        [
            'name' => 'Create Groups',
            'permission_group_id' => PermissionGroup::where('name','Groups')->first()->id
        ],

        [
            'name' => 'View All Branch Loans',
            'permission_group_id' => PermissionGroup::where('name','Loan')->first()->id
        ],
        [
            'name' => 'Edit Loans',
            'permission_group_id' => PermissionGroup::where('name','Loan')->first()->id
        ],
        [
            'name' => 'View Loan Progress',
            'permission_group_id' => PermissionGroup::where('name','Loan')->first()->id
        ],
        [
            'name' => 'Edit Loan',
            'permission_group_id' => PermissionGroup::where('name','Loan')->first()->id
        ],
        [
            'name' => 'Delete Loan',
            'permission_group_id' => PermissionGroup::where('name','Loan')->first()->id
        ],
        [
            'name' => 'Create Loan',
            'permission_group_id' => PermissionGroup::where('name','Loan')->first()->id
        ],
        [
            'name' => 'View All collections',
            'permission_group_id' => PermissionGroup::where('name','Collection')->first()->id
        ],
        [
            'name' => 'View branch collection only',
            'permission_group_id' => PermissionGroup::where('name','Collection')->first()->id
        ],

        [
            'name' => 'Fund transfer',
            'permission_group_id' => PermissionGroup::where('name','Accounting')->first()->id
        ],

        [
            'name' => 'Daily cash summary',
            'permission_group_id' => PermissionGroup::where('name','Accounting')->first()->id
        ],

        [
            'name' => 'cash summary Denomination',
            'permission_group_id' => PermissionGroup::where('name','Accounting')->first()->id
        ],
        [
            'name' => 'Profit and Loss',
            'permission_group_id' => PermissionGroup::where('name','Accounting')->first()->id
        ],
        [
            'name' => 'Payments',
            'permission_group_id' => PermissionGroup::where('name','Accounting')->first()->id
        ],
        [
            'name' => 'Petty cash',
            'permission_group_id' => PermissionGroup::where('name','Accounting')->first()->id
        ],
        [
            'name' => 'Customer Reports',
            'permission_group_id' => PermissionGroup::where('name','Reports')->first()->id
        ],

        [
            'name' => 'loan Reports',
            'permission_group_id' => PermissionGroup::where('name','Reports')->first()->id
        ],
        [
            'name' => 'collection Reports',
            'permission_group_id' => PermissionGroup::where('name','Reports')->first()->id
        ],
        [
            'name' => 'balance Sheet',
            'permission_group_id' => PermissionGroup::where('name','Reports')->first()->id
        ],
        [
            'name' => 'Role Assign',
            'permission_group_id' => PermissionGroup::where('name','Settings')->first()->id
        ],
        [
            'name' => 'Permission assign role',
            'permission_group_id' => PermissionGroup::where('name','Settings')->first()->id
        ],
        [
            'name' => 'Delete Own Account',
            'permission_group_id' => PermissionGroup::where('name','Settings')->first()->id
        ],
        [
            'name' => 'Company Create',
            'permission_group_id' => PermissionGroup::where('name','Settings')->first()->id
        ],
        [
            'name' => 'withdrawal from Company',
            'permission_group_id' => PermissionGroup::where('name','Settings')->first()->id
        ],
        [
            'name' => 'Activity log',
            'permission_group_id' => PermissionGroup::where('name','Activity log')->first()->id
        ],
        [
            'name' => 'Trial Balance',
            'permission_group_id' => PermissionGroup::where('name','Reports')->first()->id
        ],
        [
            'name' => 'Overalls Report',
            'permission_group_id' => PermissionGroup::where('name','Reports')->first()->id
        ],
        [
            'name' => 'User role remove',
            'permission_group_id' => PermissionGroup::where('name','Settings')->first()->id
        ],
        [
            'name' => 'Role assign to user',
            'permission_group_id' => PermissionGroup::where('name','Settings')->first()->id
        ],
        [
            'name' => 'Create Role',
            'permission_group_id' => PermissionGroup::where('name','Settings')->first()->id
        ],
        [
            'name' => 'Edit user Role',
            'permission_group_id' => PermissionGroup::where('name','Settings')->first()->id
        ],
        [
            'name' => 'Permission Edit',
            'permission_group_id' => PermissionGroup::where('name','Settings')->first()->id
        ],
        [
            'name' => 'Delete Role',
            'permission_group_id' => PermissionGroup::where('name','Super Admin')->first()->id
        ],
        ];



        $types = ['cash', 'branch bank', 'cash drawer','petty cash', 'collection cash'];
        $Fund_TransferGroupId = PermissionGroup::where('name', 'Fund Transfer')->first()->id;

        foreach ($types as $type) {
            $permissions[] = [
                'name' => "transfer from $type",
                'permission_group_id' => $Fund_TransferGroupId,
            ];
            $permissions[] = [
                'name' => "transfer to $type",
                'permission_group_id' => $Fund_TransferGroupId,
            ];
        }




        echo '----------------------------------------------------------------'."\n";
        echo '---------Creating Permission ---------'."\n";
        foreach ($permissions as $key => $value) {
            $permission = new Permission();
            $permission->name = $value['name'];
            $permission->permission_group_id = $value['permission_group_id'];
            $permission->save();

            echo "--------Created Permission  Name=> $permission->name-----"."\n";
        }
        echo '---------Create completed---------'."\n";
    }
}
