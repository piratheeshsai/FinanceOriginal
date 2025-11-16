<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Company;
use App\Models\User;
use App\Models\UserDetails;
use App\Services\SMSService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('Super Admin')) {
            // Show only users who are Super Admins
            $users = User::with('details')->paginate(20);
        } elseif ($user->hasPermissionTo('View All Users')) {
            // Show all users except those with Admin role
            $users = User::with('details')
                ->whereDoesntHave('roles', function ($query) {
                    $query->where('name', 'Super Admin');
                })
                ->paginate(20);
        } else {
            // Show users from the same branch, excluding Super Admins
            $users = User::with('details')
                ->where('branch_id', $user->branch_id)
                ->whereDoesntHave('roles', function ($query) {
                    $query->where('name', 'Super Admin');
                })
                ->paginate(20);
        }




        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $branches = Branch::all();
        $branchesCount = $branches->count();
        return view('users.create', compact('branches', 'branchesCount'));
    }

    /**
     * Store a newly created resource in storage.
     */


    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'employee_id' => 'required|string|unique:user_details,employee_id',
            'nic_no' => 'required|string|unique:user_details,nic_no',
            'phone_number' => 'required|string|max:15', // Make phone required for SMS
            'address' => 'nullable|string|max:255',
            'gender' => 'required|in:male,female,other',
            'age' => 'required|integer|min:18',
            'branch_id' => 'required|exists:branches,id',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048' // Image validation
        ]);

        // Handle profile photo if uploaded
        $imageName = null;
        if ($request->hasFile('profile_photo')) {
            $imageName = time() . '.' . $request->profile_photo->extension();
            $request->profile_photo->storeAs('profile_photos', $imageName, 'public');
        }

        // Generate password
        $password = $this->generateStrongPassword();

        // Create user
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($password),
            'branch_id' => $request->input('branch_id'),
        ]);

        // Create user details
        UserDetails::create([
            'user_id' => $user->id,
            'employee_id' => $request->input('employee_id'),
            'nic_no' => $request->input('nic_no'),
            'profile_photo' => $imageName,
            'phone_number' => $request->input('phone_number'),
            'address' => $request->input('address'),
            'gender' => $request->input('gender'),
            'age' => $request->input('age'),
        ]);

        // Send password via SMS with CORRECT parameter order
        $smsService = new SMSService();

        try {
            $smsSent = $smsService->sendNewUserPassword(
                $request->input('phone_number'),  // phone
                $request->input('name'),          // name
                $request->input('email'),         // email (for username)
                $password                         // actual password
            );

            if ($smsSent) {
                return redirect()->route('users.index')->with('success', 'User created successfully and password sent via SMS.');
            } else {
                // SMS failed, show password on screen
                return redirect()->route('users.index')->with([
                    'success' => 'User created successfully.',
                    'user_created' => [
                        'name' => $user->name,
                        'email' => $user->email,
                        'employee_id' => $request->input('employee_id'),
                        'password' => $password,
                        'phone_number' => $request->input('phone_number')
                    ],
                    'warning' => 'SMS failed to send. Please share the password manually.'
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send SMS: ' . $e->getMessage());

            // Show password on screen as fallback
            return redirect()->route('users.index')->with([
                'success' => 'User created successfully.',
                'user_created' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'employee_id' => $request->input('employee_id'),
                    'password' => $password,
                    'phone_number' => $request->input('phone_number')
                ],
                'warning' => 'SMS service unavailable. Please share the password manually.'
            ]);
        }
    }





    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)

    {
        $user = User::findOrFail($id);
        $userDetail = UserDetails::where('user_id', $id)->first();
        $branches = Branch::all();
        $branchesCount = $branches->count();
        return view('users.edit', compact('user', 'branches', 'branchesCount', 'userDetail'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        // dd($request->all());

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'employee_id' => 'required|string|max:255|unique:user_details,employee_id,' . $id . ',user_id',
            'nic_no' => 'required|string|max:20',
            'phone_number' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
            'gender' => 'required|string|in:male,female,other',
            'age' => 'required|integer|min:18',
            'branch_id' => 'required|exists:branches,id', // If branches exist, validate the ID
        ]);

        // Retrieve the user and user details to update
        $user = User::findOrFail($id);
        $userDetail = UserDetails::where('user_id', $id)->first();

        // Handle file upload for the profile photo
        $imageName = $userDetail->profile_photo;  // Keep the old photo name if no new photo is uploaded

        if ($request->hasFile('profile_photo')) {
            // Delete the old profile photo if it exists
            $oldPhoto = $userDetail->profile_photo;
            if ($oldPhoto && Storage::disk('public')->exists('profile_photos/' . $oldPhoto)) {
                Storage::disk('public')->delete('profile_photos/' . $oldPhoto);  // Remove the old photo
            }

            // Store the new profile photo in the 'profile_photos' folder
            $imageName = time() . '.' . $request->profile_photo->extension();  // New photo name
            $request->profile_photo->storeAs('profile_photos', $imageName, 'public');  // Store in public disk
        }

        // Update the user in the 'users' table
        Log::info('Before update user branch', ['id' => $user->id, 'branch_before' => $user->branch_id, 'requested' => $validated['branch_id']]);
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->branch_id = $validated['branch_id'];
        $user->save();
        $user->refresh(); // reload from DB
        Log::info('After update user branch', ['id' => $user->id, 'branch_after' => $user->branch_id]);

        // Update the user's additional details in the 'user_details' table
        $userDetail->update([
            'employee_id' => $validated['employee_id'],
            'nic_no' => $validated['nic_no'],
            'phone_number' => $validated['phone_number'],
            'address' => $validated['address'],
            'gender' => $validated['gender'],
            'age' => $validated['age'],

            'profile_photo' => $imageName,  // Update profile photo if changed
        ]);

        // Redirect or return a response with success message
        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        if ($user->createdTransactions()->exists() || $user->approvedTransactions()->exists()) {
            // If there are related transactions, prevent deletion
            return redirect()->route('users.index')->with('error', 'Cannot delete user with existing transactions.');
        }



        $user->delete();  // Delete the user from the database

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    public function deactivate(string $id)
    {
        $user = User::findOrFail($id);  // Find the user by ID or fail
        $user->status = 'inactive'; // Change the status to 'inactive'
        $user->save(); // Save the changes

        return redirect()->route('users.index')->with('success', 'User deactivated successfully.');
    }

    public function activate(string $id)
    {
        $user = User::findOrFail($id);
        $user->status = 'active';
        $user->save();

        return redirect()->route('users.index')->with('success', 'User activated successfully.');
    }

    /**
     * Generate a secure random password
     */
    private function generateStrongPassword($length = 12)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789@#$%&*!';
        $password = '';
        $characterLength = strlen($characters);

        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[rand(0, $characterLength - 1)];
        }

        return $password;
    }
}
