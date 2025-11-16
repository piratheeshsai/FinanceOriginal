<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Center;
use Illuminate\Http\Request;

class CenterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'name' => 'required',
    //         'center_code' => 'required',
    //         'branch_id' => 'required|exists:branches,id', // Ensure that branch_id exists in the branches table
    //     ]);
    //     $center = Center::create($validated);

    //     return response()->json($center, 201);
    //     // Create a new center with the provided data
    //     $center = Center::create([
    //         'name' => $validated['name'],
    //         'center_code' => $validated['center_code'],
    //         'branch_id' => $validated['branch_id'],  // Save the selected branch ID
    //     ]);

    //     // Fetch the branch name associated with the created center
    //     $branch = Branch::find($center->branch_id);
    //     $center->branch_name = $branch ? $branch->name : 'Unknown';

    //     // Return the newly created center along with the branch name
    //     return response()->json($center);

    // }
    public function store(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'name' => 'required',
            'branch_id' => 'required|exists:branches,id', // Ensure that branch_id exists in the branches table
        ]);

        // Get the branch ID
        $branchId = $validated['branch_id'];

        // Find the highest existing center code for the branch and increment it
        $latestCenter = Center::where('branch_id', $branchId)->latest()->first();

        // Generate the new center code. Start at '001' for each branch.
        $newCenterCode = $latestCenter
            ? str_pad((intval($latestCenter->center_code) + 1), 3, '0', STR_PAD_LEFT)
            : '001';  // Start at '001' if it's the first center for the branch.

        // Create the center with the generated center code
        $center = Center::create([
            'name' => $validated['name'],
            'center_code' => $newCenterCode, // Store the generated center code
            'branch_id' => $validated['branch_id'], // Save the selected branch ID
        ]);

        return response()->json($center, 201);
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
        $center = Center::findOrFail($id); // Find the center by ID
        return response()->json($center);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Find the center by its primary database ID
        $center = Center::findOrFail($id); // Find the center by ID

    // Validate input data
    $request->validate([
        'name' => 'required|string|max:255',
    ]);

    // Update the center's name
    $center->name = $request->name;
    $center->save();

    // Return the updated center's data
    return response()->json([
        'center' => $center
    ]);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $center = Center::find($id);

        // Check if the center exists
        if ($center) {
            // Delete the center
            $center->delete();

            // Return a success response
            return response()->json(['success' => true], 200);
        }

        // Return error if the center is not found
        return response()->json(['success' => false, 'message' => 'Center not found'], 404);
    }

}
