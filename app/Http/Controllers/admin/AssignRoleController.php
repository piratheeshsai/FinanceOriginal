<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class AssignRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('Super Admin')) {
            // Show all users to Super Admin
            $users = User::with('roles')->get();
        } else {
            // Show users except those who are Super Admins
            $users = User::whereDoesntHave('roles', function ($query) {
                $query->where('name', 'Super Admin');
            })->with('roles')->get();
        }

        return view('admin.assignRole.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $authUser = Auth::user();

        if ($authUser->hasRole('Super Admin')) {
            $users = User::get();
        } else {

            $users = User::whereDoesntHave('roles', function ($query) {
                $query->where('name', 'Super Admin');
            })->get();
        }


        if ($authUser->hasRole('Super Admin')) {
            // Show all roles to Super Admin
            $roles = Role::get();
        } else {
            // Show only specific roles to other users
            $roles = Role::where('name', '!=', 'Super Admin')->get();
        }
        return view('admin.assignRole.create', compact('users', 'roles'))->render();
    }



    public function edit(string $id)
    {
        $authUser = Auth::user();
        $selectedUser = User::with('roles')->find($id);

        if (!$selectedUser) {
            abort(404, 'User not found');
        }

        // Users list (optional, if needed)
        if ($authUser->hasRole('Super Admin')) {
            $users = User::get();
        } else {
            $users = User::whereDoesntHave('roles', function ($query) {
                $query->where('name', 'Super Admin');
            })->get();
        }

        // Role filtering based on current user's role
        if ($authUser->hasRole('Super Admin')) {
            $roles = Role::get();
        } else {
            $roles = Role::where('name', '!=', 'Super Admin')->get();
        }

        return view('admin.assignRole.edit', compact('selectedUser', 'roles'))->render();
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $user = User::find($request->user_id);
        // $role = Role::find($request->role_id);
        $user->syncRoles($request->role_name);

        return redirect()->route('assign.index')->with('message', 'User assigned role');
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
    // public function edit(string $id)
    // {
    //     $selectedUser = User::with('roles')->find($id);
    //     $users       = User::get();
    //     $roles = Role::get();

    //     if (!$selectedUser) {
    //         abort(404, 'User not found');
    //     }

    //     return view('admin.assignRole.edit', compact('selectedUser', 'roles'))->render();
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id);
        // $role = Role::find($request->role_id);
        $user->syncRoles($request->role_id);

        if ($request->ajax()) {
            return response()->json(['message' => 'User roles updated successfully']);
        }

        // For non-AJAX requests (normal form submission)
        return redirect()->route('assign.index')->with('message', 'User roles updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $user = User::find($id);

        if ($user) {
            // Revoke all roles for the user
            $user->syncRoles([]);  // This will remove all roles from the user

            return response()->json(['message' => 'Role removed successfully']);
        }

        return response()->json(['message' => 'User not found'], 404);
    }
}
