<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Accounts extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('Accounting.DailyCashSummary');
    }

    public function cashDenomination()
    {
        // dd("Controller Method Works!");
        return view('Accounting.cashDenomination');
    }




    public function PettyCash()
    {
        // dd("Controller Method Works!");
        return view('Accounting.PettyCash');
    }

    public function payments()
    {
        // dd("Controller Method Works!");
        return view('Accounting.payment');
    }

    public function ManageTypes()
    {
        // dd("Controller Method Works!");
        return view('Accounting.ManageTypes');
    }

    public function ProfitLoss()
    {
        // dd("Controller Method Works!");
        return view('Accounting.profitLoss');
    }

    public function PaymentSupplier()
    {
        // dd("Controller Method Works!");
        return view('Accounting.PaymentSupplier');
    }
    public function PaymentCategory()
    {
        // dd("Controller Method Works!");
        return view('Accounting.paymentCategory');
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
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
