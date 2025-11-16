<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Branch;
use App\Models\BranchAccount;
use App\Models\Center;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BranchesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {



        $branches = Branch::with('creator')->get();;
        return view('branches.index', compact('branches'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('branches.create');
    }






    public function store(Request $request)
    {
        dd($request->all());


        // Validate the request
        $validated = $request->validate([
            'branchName' => 'required|string|max:255',
            'branchEmail' => 'required|email',
            'branchPhone' => 'required|string|max:15',
        ]);

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Generate a new branch code
            $lastBranch = Branch::latest('id')->first();
            $lastBranchCode = $lastBranch ? (int) substr($lastBranch->branch_code, 1) : 0;
            $newBranchCode = str_pad($lastBranchCode + 1, 3, '0', STR_PAD_LEFT);

            // Create a new branch
            $branch = Branch::create([
                'name' => $validated['branchName'],
                'email' => $validated['branchEmail'],
                'phone' => $validated['branchPhone'],
                'user_id' => Auth::id(),
                'branch_code' => $newBranchCode,
            ]);









            // Commit the transaction if everything is successful
            DB::commit();

            return response()->json([
                'message' => 'Branch and accounts created successfully',
                'branch' => $branch->name
            ], 201);
        } catch (Exception $e) {
            // Rollback the transaction if any error occurs
            DB::rollBack();

            return response()->json([
                'message' => 'Error creating branch',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $branch = Branch::findOrFail($id);
        return response()->json($branch);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
        ]);

        $branch = Branch::findOrFail($id);
        $branch->update($validatedData);

        return response()->json($branch);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $branch = Branch::findOrFail($id);
        $branch->delete();

        return response()->json(['message' => 'Branch deleted successfully']);
    }


    public function getCenters($branchId)
    {


        $centers = Center::where('branch_id', $branchId)->get(); // Adjust per your schema
        return response()->json($centers);
    }
}
