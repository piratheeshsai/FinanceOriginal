<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GeneralLedgerController extends Controller
{

    public function index()
    {
        return view('Accounting.GeneralLedger');
    }
}
