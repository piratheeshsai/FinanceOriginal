<?php

namespace App\Http\Controllers\Collection;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use Illuminate\Http\Request;
use Log;
use View;

use function Ramsey\Uuid\v1;

class CollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return view('Collection.Collection');
    }


    public function allCollections()
    {
        // Pass the collections data to the view
        return view('Collection.allCollection');
    }


    public function bulkCollection(){
        return view('Collection.bulkCollection');
    }

    public function collectionTransfer(){
        return view('Collection.collectionTransfer');
    }

    public function collectionTransferApproval(){
        return view('Collection.collectionTransferAprovel');
    }
    /**
     *Show the form for creating a new resource.
    **/
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

    public function loanProgress() {

    return view('Collection.loanProgress'); }


}
