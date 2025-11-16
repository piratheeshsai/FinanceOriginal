<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'date',
        'cashier_balance',
        'interest_balance',
        'capital_balance'
    ];
}
