<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\PermissionGroup;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        if (auth()->user()->hasRole('Super Admin')) {
            // Show all roles
            $roles = Role::with('permissions')->get();
        } else {
            // Exclude "Super Admin" role
            $roles = Role::with('permissions')
                        ->where('name', '!=', 'Super Admin')
                        ->get();
        }
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissionGroups = PermissionGroup::with('permissions')->get();
        return view('admin.roles.create', compact('permissionGroups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {

    //     $role = new Role();
    //     $role->name = $request->name;
    //     $role->save();

    //     // Cast the permission IDs to integers
    //     $permissionIds = array_map('intval', $request->permission_ids);

    //     $role->syncPermissions($permissionIds);

    //     return redirect()->back()->with('message', 'Role created');
    // }


    public function store(Request $request)
{
    // Optional: validate input first
    $request->validate([
        'name' => 'required|string|max:255',
        'permission_ids' => 'required|array',
    ]);

    // Check if the role already exists for the 'web' guard
    $existingRole = Role::where('name', $request->name)
                        ->where('guard_name', 'web')
                        ->first();

    if ($existingRole) {
        return redirect()->back()->with('message', 'Role already exists.');

    }

    // Create the new role
    $role = new Role();
    $role->name = $request->name;
    $role->guard_name = 'web'; // important if you want to avoid defaulting wrong guard
    $role->save();

    // Sync permissions
    $permissionIds = array_map('intval', $request->permission_ids);
    $role->syncPermissions($permissionIds);

    return redirect()->back()->with('message', 'Role created Permission Assigned successfully.');
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $permissionGroups = PermissionGroup::with('permissions')->get();
        return view('admin.roles.edit', compact('role', 'permissionGroups'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // return $request;
        $role = Role::find($id);
        $role->name = $request->name;
        $role->save();
        $permissionIds = array_map('intval', $request->permission_ids);
        $role->syncPermissions($permissionIds);
        return redirect()->back()->with('message', 'Permission updated successfully');


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Find the role by ID and delete it
            $role = Role::findOrFail($id);
            $role->delete();

            // Redirect back with a success message
            return redirect()->route('role.index')->with('message', 'Role deleted successfully');
        } catch (\Exception $e) {
            // Handle any errors (e.g., role not found)
            return redirect()->route('role.index')->withErrors('Failed to delete role: ' . $e->getMessage());
        }
    }
}
