<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentSupplierPivot extends Model
{
    use HasFactory;

    protected $table = 'payment_supplier_pivot';

    protected $fillable = ['payment_id', 'supplier_id', 'amount'];
}
