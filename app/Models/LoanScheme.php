<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanScheme extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_name',
        'loan_type',
        'interest_rate',
        'collecting_duration',
        'loan_term',
    ];

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
}
