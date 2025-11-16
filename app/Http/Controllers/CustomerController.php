<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Center;
use App\Models\City;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $perPage = $request->input('per_page', 10);

        // // Fetch customers in descending order with pagination
        // $customers = Customer::orderBy('created_at', 'desc');
        //                     // ->paginate($perPage);

        return view('customer.index',);
        // compact('customers', )
    }


    public function create()
    {
        $cities = City::orderBy('name', 'asc')->get();
        if (auth()->user()->hasPermissionTo('view all branches')) {
            $centers = Center::orderBy('name')->get();
        } else {
            $centers = Center::where('branch_id', auth()->user()->branch_id)
                ->orderBy('name')
                ->get();
        }

        return view('customer.create', compact('centers', 'cities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_types' => 'required|array',
            'full_name' => 'required|string|max:255',
            'nic' => 'required|string|unique:customers,nic',
            'customer_phone' => 'required|string',
            'center_id' => 'required|exists:centers,id',
            'date_of_birth' => 'required|date',
            'gender' => 'required|string',
            'occupation' => 'required|string',
            'permanent_address' => 'required|string',
            'living_address' => 'required|string',
            'permanent_city' => 'required|string',
            'living_city' => 'required|string',
            'civil_status' => 'required|string',
        ]);

        try {
            $customerNumber = null;

            DB::transaction(function () use ($request, &$customerNumber) {
                // Get center and branch for customer number generation
                $center = Center::with('branch')->findOrFail($request->center_id);
                $branch = $center->branch;

                // Use fallback values if codes don't exist
                $branchCode = $branch->branch_code ?? 'BR';
                $centerCode = $center->center_code ?? 'CT';

                $lastCustomer = Customer::lockForUpdate()
                    ->where('center_id', $request->center_id)
                    ->orderBy('customer_no', 'desc')
                    ->first();

                $nextCustomerNumber = $lastCustomer
                    ? intval(substr($lastCustomer->customer_no, -3)) + 1
                    : 1;

                // Format: BRANCHCODE/CENTERCODE/NUMBER
                $customerNumber = "{$branchCode}/{$centerCode}/" . str_pad($nextCustomerNumber, 3, '0', STR_PAD_LEFT);

                while (Customer::where('customer_no', $customerNumber)->exists()) {
                    $nextCustomerNumber++;
                    $customerNumber = "{$branchCode}/{$centerCode}/" . str_pad($nextCustomerNumber, 3, '0', STR_PAD_LEFT);
                }

                // Create the new customer - CORRECTED ORDER TO MATCH MIGRATION
                $customer = new Customer();
                $customer->center_id = $request->center_id;
                $customer->full_name = $request->full_name;
                $customer->customer_no = $customerNumber;
                $customer->nic = $request->nic;
                $customer->permanent_address = $request->permanent_address;
                $customer->living_address = $request->living_address;
                $customer->permanent_city = $request->permanent_city;
                $customer->living_city = $request->living_city;
                $customer->customer_phone = $request->customer_phone;
                $customer->date_of_birth = $request->date_of_birth;
                $customer->occupation = $request->occupation;
                $customer->gender = $request->gender;
                $customer->civil_status = $request->civil_status;

                // Spouse details (nullable fields)
                $customer->spouse_name = $request->spouse_name;
                $customer->spouse_nic = $request->spouse_nic;
                $customer->Spouse_phone = $request->Spouse_phone; // Note: Capital 'S' to match migration
                $customer->spouse_occupation = $request->spouse_occupation;
                $customer->spouse_age = $request->spouse_age;

                // Family details (nullable fields)
                $customer->home_phone = $request->home_phone;
                $customer->family_members = $request->family_members;
                $customer->income_earners = $request->income_earners;
                $customer->family_income = $request->family_income;

                // Handle file uploads
                if ($request->hasFile('photo')) {
                    $photo = $request->file('photo');
                    $photoName = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
                    $customer->photo = $photo->storeAs('customers/photos', $photoName, 'public');
                }

                if ($request->hasFile('nic_copy')) {
                    $nicCopy = $request->file('nic_copy');
                    $nicCopyName = time() . '_' . uniqid() . '.' . $nicCopy->getClientOriginalExtension();
                    $customer->nic_copy = $nicCopy->storeAs('customers/nic_copies', $nicCopyName, 'public');
                }

                $customer->save();

                // Attach customer types
                if ($request->has('customer_types')) {
                    $customer->types()->sync($request->input('customer_types'));
                }
            });

            return redirect()->route('customer.index')->with('success', "Customer created successfully! Customer Number: {$customerNumber}");

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()->with('error', 'Database error: ' . $e->getMessage())->withInput();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to create customer: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $customer = Customer::findOrFail($id);

        return view('customer.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        $customer = Customer::find($id);


        $cities = City::orderBy('name', 'asc')->get();
        if (auth()->user()->hasPermissionTo('view all branches')) {
            $centers = Center::orderBy('name')->get();
        } else {
            $centers = Center::where('branch_id', auth()->user()->branch_id)
                ->orderBy('name')
                ->get();
        }

        return view('customer.edit', compact('centers', 'customer', 'cities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'customer_types' => 'required|array',
        ]);

        try {
            DB::transaction(function () use ($request, $id) {
                $customer = Customer::findOrFail($id);

                // Update fields in correct order
                $customer->center_id = $request->center_id;
                $customer->full_name = $request->full_name;
                $customer->nic = $request->nic;
                $customer->permanent_address = $request->permanent_address;
                $customer->living_address = $request->living_address;
                $customer->permanent_city = $request->permanent_city; // FIXED: was $request->living_city
                $customer->living_city = $request->living_city;
                $customer->customer_phone = $request->customer_phone;
                $customer->date_of_birth = $request->date_of_birth;
                $customer->occupation = $request->occupation;
                $customer->gender = $request->gender;
                $customer->civil_status = $request->civil_status;

                // Spouse details
                $customer->spouse_name = $request->spouse_name;
                $customer->spouse_nic = $request->spouse_nic;
                $customer->Spouse_phone = $request->Spouse_phone; // Capital 'S'
                $customer->spouse_occupation = $request->spouse_occupation;
                $customer->spouse_age = $request->spouse_age;

                // Family details
                $customer->home_phone = $request->home_phone;
                $customer->family_members = $request->family_members;
                $customer->income_earners = $request->income_earners;
                $customer->family_income = $request->family_income;

                // Handle file uploads
                if ($request->hasFile('photo')) {
                    if ($customer->photo && Storage::disk('public')->exists($customer->photo)) {
                        Storage::disk('public')->delete($customer->photo);
                    }
                    $photo = $request->file('photo');
                    $photoName = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
                    $customer->photo = $photo->storeAs('customers/photos', $photoName, 'public');
                }

                if ($request->hasFile('nic_copy')) {
                    if ($customer->nic_copy && Storage::disk('public')->exists($customer->nic_copy)) {
                        Storage::disk('public')->delete($customer->nic_copy);
                    }
                    $nicCopy = $request->file('nic_copy');
                    $nicCopyName = time() . '_' . uniqid() . '.' . $nicCopy->getClientOriginalExtension();
                    $customer->nic_copy = $nicCopy->storeAs('customers/nic_copies', $nicCopyName, 'public');
                }

                if ($request->has('customer_types')) {
                    $customer->types()->sync($request->input('customer_types'));
                }

                $customer->save();
            });

            return redirect()->route('customer.index')->with('success', 'Customer updated successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to update customer: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $customer = Customer::with(['loans.approval'])->findOrFail($id);

        // Check for active loans (status = Approved)
        if ($customer->loans->contains(function ($loan) {
            return $loan->approval && $loan->approval->status === 'Approved';
        })) {
            return redirect()->route('customer.index')
                ->with('error', 'Cannot delete customer with active approved loans!');
        }

        $customer->delete();

        return redirect()->route('customer.index')
            ->with('success', 'Customer deleted successfully!');

    }


    public function getCustomers(Request $request)
    {
        // Check if a group_id is passed in the request
        $groupId = $request->input('group_id');

        if ($groupId) {
            // Fetch customers that belong to the specified group
            $customers = Customer::where('group_id', $groupId)->get();
        } else {
            // Fetch all customers if no group_id is provided
            $customers = Customer::all();
        }

        // Return the customers as a JSON response

        return response()->json($customers);
    }
}
