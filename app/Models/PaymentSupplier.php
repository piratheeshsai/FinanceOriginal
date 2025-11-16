<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentSupplier extends Model
{
    use HasFactory;




    protected $table = 'suppliers';



    protected $fillable = [
        'payment_category_id',
        'name',
        'nic',
        'salary',
        'bank_account_name',
        'bank_account_number'
    ];






public function paymentCategory()
{
    return $this->belongsTo(PaymentCategory::class, 'payment_category_id');
}

//    public function payments()
//     {
//         return $this->belongsToMany(Payment::class, 'payment_supplier_pivot', 'supplier_id', 'payment_id')
//                     ->withPivot('amount');
//     }

public function payments()
    {
        return $this->belongsToMany(Payment::class, 'payment_supplier_pivot', 'supplier_id', 'payment_id')
                    ->withPivot('amount');
    }
}
