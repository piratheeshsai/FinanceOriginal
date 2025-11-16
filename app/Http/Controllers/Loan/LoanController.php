<?php

namespace App\Http\Controllers\Loan;


use App\Events\NewLoanCreated;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Center;
use App\Models\Customer;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\Loan;
use App\Models\LoanScheme;
use App\Models\Transaction;
use App\Models\User;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Log;
use Notification;

class LoanController extends Controller
{

    use Notifiable;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {



        return view('loan.index');
    }

    public function approval()
    {
        return view('loan.approval');
    }

    public function loanDetails($id)
    {

        $loan = Loan::findOrFail($id);

        return view('loan.loanDetails', compact('loan'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $user = auth()->user(); // Get the authenticated user

        // Fetch groups based on user's role and branch
        $groups = Group::filterByRoleAndBranch($user)->get();
        $loan_schemes = LoanScheme::all();
        $centers = Center::all();

        $Guarantor = Customer:: // Filter by role and branch (ensure this method works as intended)filterByRoleAndBranch($user)
            where(function ($query) {
                $query->whereHas('types', function ($typeQuery) {
                    $typeQuery->where('name', '!=', ' Customer');  // Include customers who are not only "Guarantor"
                })
                    ->orWhere(function ($orQuery) {
                        $orQuery->whereHas('types', function ($typeQuery) {
                            $typeQuery->where('name', 'Guarantor');  // Customer type exists
                        })
                            ->whereHas('types', function ($typeQuery) {
                                $typeQuery->where('name', ' Customer');  // Guarantor type also exists
                            });
                    });
            })
            ->get();


        return view('loan.create', compact('groups', 'loan_schemes', 'centers', 'Guarantor'));
    }








    public function fetchCustomers(Request $request)
    {
        $groupId = $request->query('group_id');
        $user = auth()->user(); // Get the logged-in user

        if ($groupId && strtolower($groupId) !== 'null') {
            // Fetch customers related to the group
            $customers = GroupMember::where('group_id', $groupId)
                ->with('customer')
                ->get()
                ->map(function ($groupMember) {
                    return $groupMember->customer;
                });
        } else {
            // Fetch customers not part of any group
            $customers = Customer::whereDoesntHave('groupMembers')  // Ensures no group members
                // ->filterByRoleAndBranch($user) // Filter by role and branch (ensure this method works as intended)
                ->where(function ($query) {
                    $query->whereHas('types', function ($typeQuery) {
                        $typeQuery->where('name', '!=', 'Guarantor');  // Include customers who are not only "Guarantor"
                    })
                        ->orWhere(function ($orQuery) {
                            $orQuery->whereHas('types', function ($typeQuery) {
                                $typeQuery->where('name', ' Customer');  // Customer type exists
                            })
                                ->whereHas('types', function ($typeQuery) {
                                    $typeQuery->where('name', 'Guarantor');  // Guarantor type also exists
                                });
                        });
                })
                ->get();
        }
        return response()->json($customers);
    }




    public function store(Request $request)
    {

        $validated = $request->validate([
            'loan_type' => 'required',
            'scheme_id' => 'required|exists:loan_schemes,id',
            'loan_amount' => 'required|numeric',
            'document_charge' => 'required|numeric',
            'loan_start' => 'required|date',
            'loan_date' => 'required|date',
            'center_id' => 'required|exists:centers,id',  // Ensure it's a valid center ID
            'loan_guarantor' => 'required|array|min:1',  // Guarantor(s) array validation
            'customer_id' => 'required|exists:customers,id',  // Ensure it's a valid customer ID
        ]);

        try {
            // Fetch branch_id from the center
            $branch_id = Center::where('id', $validated['center_id'])->value('branch_id');

            // Ensure branch_id exists
            if (!$branch_id) {
                return back()->withErrors('Invalid center. Associated branch not found.');
            }

            // Fetch the capital account for the branch
            $loanAccount = Account::where('branch_id', $branch_id)
                ->where('type', 'loan_receivable') // Ensure it's a capital account
                ->first();

            $branchAccount = Account::where('branch_id', $branch_id)
                ->where('type', 'bank') // or 'cashier' depending on disbursement method
                ->firstOrFail();

            if (!$loanAccount) {
                return back()->withErrors(['error' => 'loanAccount account not found for the selected branch.']);
            }

            // Generate loan number based on the selected center
            $loan_number = $this->generateLoanNumber($validated['center_id']);

            // Declare $loan variable outside the transaction closure
            $loan = null;

            // Use a database transaction to ensure atomicity
            DB::transaction(function () use ($validated, $branch_id, $branchAccount, $loanAccount, $loan_number, &$loan) {
                // Create the loan record
                $loan = Loan::create([
                    'loan_number' => $loan_number,  // Use generated loan number
                    'loan_type' => $validated['loan_type'],
                    'scheme_id' => $validated['scheme_id'],  // Loan scheme ID
                    'loan_amount' => $validated['loan_amount'],
                    'document_charge' => $validated['document_charge'],
                    'start_date' => $validated['loan_start'],
                    'loan_date' => $validated['loan_date'],  // Loan date
                    'customer_id' => $validated['customer_id'],  // Customer ID
                    'center_id' => $validated['center_id'],  // Center ID
                    'loan_creator_name' => auth()->id(),  // Name of the authenticated user
                ]);



                // Attach loan guarantors to the loan
                $loan->guarantors()->attach($validated['loan_guarantor']);
            });




            // Fetch approvers and fire the event
            $approvers = User::permission('approve-loan')->get();
            event(new NewLoanCreated($loan, $approvers));

            // Redirect back with success message
            return redirect()->route('loan.index')->with('status', 'Loan created successfully!');
        } catch (Exception $e) {
            // Log the error
            Log::error('Error creating loan: ' . $e->getMessage());

            // Redirect back with error message
            return redirect()->route('loan.index')->with('error', 'There was an error creating the loan. Please try again.');
        }
    }








    private function generateLoanNumber($center_id)
    {
        $center = Center::findOrFail($center_id);
        $branch_code = str_pad($center->branch_id, 3, '0', STR_PAD_LEFT);
        // Use the actual center_code from the center model
        $center_code = $center->center_code;

        return DB::transaction(function () use ($branch_code, $center_code, $center_id) {
            // Get the highest existing loan number part for this center
            $max_loan = Loan::withTrashed() // Include soft-deleted to prevent number reuse
                ->where('center_id', $center_id)
                ->where('loan_number', 'like', 'LN' . $branch_code . '/' . $center_code . '/%')
                ->lockForUpdate()
                ->selectRaw("MAX(CAST(SUBSTRING_INDEX(loan_number, '/', -1) AS UNSIGNED)) as max_num")
                ->first();

            $next_number = $max_loan && $max_loan->max_num ? $max_loan->max_num + 1 : 1;

            // Format with 4 digits (0001, 0002, etc.) to show loan count for this center
            $new_number = 'LN' . $branch_code . '/' . $center_code . '/' . str_pad($next_number, 4, '0', STR_PAD_LEFT);

            // Double-check for existence (race condition protection)
            $exists = Loan::withTrashed()
                ->where('loan_number', $new_number)
                ->exists();

            if ($exists) {
                throw new \Exception("Duplicate loan number detected: " . $new_number);
            }

            return $new_number;
        });
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
        $user = auth()->user(); // Get the authenticated user
        // Fetch the loan to be edited
        $loan = Loan::with(['customer', 'guarantors', 'loanScheme', 'center'])->findOrFail($id);
        Log::info('Loan Start Date: ' . $loan->loan_start);

        // Fetch additional data needed for the edit form
        $groups = Group::filterByRoleAndBranch($user)->get();
        $loan_schemes = LoanScheme::all();
        $centers = Center::all();

        // Fetch eligible guarantors
        $Guarantor = Customer::filterByRoleAndBranch($user)
            ->where(function ($query) {
                $query->whereHas('types', function ($typeQuery) {
                    $typeQuery->where('name', '!=', ' Customer');  // Include customers who are not only "Guarantor"
                })
                    ->orWhere(function ($orQuery) {
                        $orQuery->whereHas('types', function ($typeQuery) {
                            $typeQuery->where('name', 'Guarantor');  // Customer type exists
                        })
                            ->whereHas('types', function ($typeQuery) {
                                $typeQuery->where('name', ' Customer');  // Guarantor type also exists
                            });
                    });
            })
            ->get();
        $customer = $loan->customer;
        // Return the view or API response
        return view('loan.edit', compact('loan', 'groups', 'loan_schemes', 'centers', 'Guarantor', 'customer'));
    }




    public function update(Request $request, string $id)
    {

        // dd($request->all());
        $validated = $request->validate([
            'loan_type' => 'required|string|in:individual,group',
            'scheme_id' => 'required|exists:loan_schemes,id',
            'loan_amount' => 'required|numeric|min:1',
            'document_charge' => 'required|numeric',
            'loan_start' => 'required|date',
            'loan_date' => 'required|date',
            'center_id' => 'required|exists:centers,id',
            'group_id' => 'nullable|exists:groups,id', // Optional for individual loans
            'customer_id' => 'required|exists:customers,id', // Mandatory customer assignment
            'loan_guarantor' => 'required|array|min:1',
            'loan_guarantor.*' => 'exists:customers,id',
        ]);
        // dd($validated);
        try {
            // Fetch the loan
            $loan = Loan::findOrFail($id);

            // Fetch branch_id from the center
            $branch_id = Center::where('id', $validated['center_id'])->value('branch_id');

            // Ensure branch_id exists
            if (!$branch_id) {
                return back()->withErrors('Invalid center. Associated branch not found.');
            }

            // Fetch the capital account for the branch
            $branchAccount = Account::where('branch_id', $branch_id)
                ->where('type', 'bank') // Ensure it's a capital account
                ->first();

            if (!$branchAccount) {
                return back()->withErrors(['error' => 'Capital account not found for the selected branch.']);
            }

            // Use a database transaction to ensure atomicity
            DB::transaction(function () use ($validated, $loan, $branch_id, $branchAccount) {
                // Update loan details
                $loan->update([
                    'loan_type' => $validated['loan_type'],
                    'scheme_id' => $validated['scheme_id'],
                    'loan_amount' => $validated['loan_amount'],
                    'document_charge' => $validated['document_charge'],
                    'start_date' => $validated['loan_start'],
                    'loan_date' => $validated['loan_date'],
                    'center_id' => $validated['center_id'],
                    'group_id' => $validated['group_id'] ?? null,
                    'customer_id' => $validated['customer_id'],
                ]);

                // Update the transaction record
                $transaction = Transaction::where('loan_id', $loan->id)->first();
                if ($transaction) {
                    $transaction->update([
                        'branch_id' => $branch_id,
                        'amount' => $validated['loan_amount'],
                        'description' => 'Loan disbursement to ' . $loan->customer->full_name,
                        'status' => 'pending',
                        'updated_by' => auth()->id(),
                    ]);
                }

                $loan->guarantors()->sync($validated['loan_guarantor']);
            });

            // Redirect with success message
            return redirect()->route('loan.index')->with('status', 'Loan updated successfully!');
        } catch (Exception $e) {
            // Handle any exceptions and redirect with an error message
            return redirect()->route('loan.index')->with('error', 'There was an issue updating the loan. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}














 // public function fetchCustomers(Request $request)
    // {
    //     $groupId = $request->query('group_id');

    //     if ($groupId && strtolower($groupId) !== 'null') {
    //         // Fetch customers related to the group
    //         $customers = GroupMember::where('group_id', $groupId)
    //             ->with('customer')
    //             ->get()
    //             ->map(function ($groupMember) {
    //                 return $groupMember->customer;
    //             });
    //     } else {
    //         // Fetch customers not part of any group
    //         $customers = Customer::whereDoesntHave('groupMembers')->get();
    //     }

    //     return response()->json($customers);
    // }
// Notify each approver about the loan approval
    // $approvers = User::permission('approve-loan')->get();
    // foreach ($approvers as $approver) {
    //     $approver->notify(new LoanApprovalNotification($loan));
    // }

    // $approvers = User::permission('approve-loan')->get();
    // foreach ($approvers as $approver) { $approver->notify(new LoanApprovalNotification($loan)); }
    // Notify the users who can approve the loan
    // event(new NewLoanNotification($loan));
