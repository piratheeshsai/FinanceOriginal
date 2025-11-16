<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanProgress extends Model
{
    use HasFactory;
    protected $table = 'loan_progress';  // Specify the table name if it's not the default plural of the model name

    protected $fillable = [
        'loan_id',           // Foreign key to loan table
        'total_amount',      // Total loan amount (principal + interest)
        'balance',           // Remaining balance (total amount - paid amount)
        'paid_amount',       // Amount paid so far
        'status',            // Loan status (Pending, Completed)
        'last_due_date',     // Last due date for the loan (loan completion date)
    ];

    // Define the relationship with the Loan model
    public function loan()
    {
        return $this->belongsTo(Loan::class, 'loan_id');
    }


    // Optionally, you can add a method to calculate remaining balance
    public function calculateRemainingBalance()
    {
        return $this->total_amount - $this->paid_amount;
    }

    // Optionally, you can add a method to check if the loan is completed
    public function isCompleted()
    {
        return $this->status == 'Completed';
    }

  
}
