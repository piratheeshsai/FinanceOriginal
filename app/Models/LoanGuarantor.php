<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanGuarantor extends Model
{
    use HasFactory;



    protected $table = 'loan_guarantors';

    public function loan()
    {
        return $this->belongsTo(Loan::class, 'loan_id', 'id');
    }


    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function guaranteedLoans()
    {
        return $this->belongsToMany(Loan::class, 'loan_guarantors', 'guarantor_id', 'loan_id')
            ->withTimestamps();
    }
}
