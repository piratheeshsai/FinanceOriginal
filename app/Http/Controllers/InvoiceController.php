<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\CollectionInvoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{


    public function printPOS($id)
    {
        $invoice = CollectionInvoice::findOrFail($id);
        return view('exports.collectionPos', compact('invoice'));
    }

    // Generate A4 PDF Invoice
    public function downloadA4($id)
    {
        $invoice = CollectionInvoice::findOrFail($id);
        $pdf = Pdf::loadView('exports.collectionA4', compact('invoice'));
        return $pdf->download('Invoice_' . $invoice->invoice_number . '.pdf');
    }

}
