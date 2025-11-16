<?php

namespace App\Http\Controllers\reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class reportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('reports.index');
    }

public function customerList(){
        return view('reports.customerList');
    }

    public function loanReport(){
        return view('reports.loanReport');
    }

    public function collectionReport(){
        return view('reports.collectionReport');
    }

    public function pendingCollection(){
        return view('reports.pendingCollection');
    }

    public function balanceSheet(){
        return view('reports.balanceSheet');
    }

    public function branchFinancialReport(){
        return view('reports.branchFinancialReport');
    }

    public function TrialBalance(){
        return view('reports.TrialBalance');
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
