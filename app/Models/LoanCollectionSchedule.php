<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanCollectionSchedule extends Model
{
    use HasFactory;

    protected $table = 'loan_collection_schedules';


    protected $fillable = [
       'loan_id','status', 'date', 'description', 'principal', 'interest', 'penalty',
        'due', 'paid', 'pending_due', 'total_due', 'principal_due',
    ];


    public function loan()
    {
        return $this->belongsTo(Loan::class, 'loan_id');
    }

    // LoanCollectionSchedule.php

    protected $casts = [
        'date' => 'datetime',
    ];
}
