<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'voucher_number',
        'type',
        'reference_id',
        'date',
        'amount',
        'payee_details',
        'description',
        'account_id',
        'approved_by',
        'created_by',
        'branch_id',
          'loan_id',
        'customer_id',
    ];

    protected $casts = [
        'date' => 'date', // Cast the custom date column to a Carbon instance
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
     public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
