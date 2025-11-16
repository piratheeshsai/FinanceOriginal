<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectionInvoice extends Model
{
    use HasFactory;

    protected $table = 'collection_invoice';
    protected $fillable = ['invoice_number', 'loan_id', 'collection_id', 'collected_amount', 'type'];

    public function collection()
    {
        return $this->belongsTo(Collection::class);
    }

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}
