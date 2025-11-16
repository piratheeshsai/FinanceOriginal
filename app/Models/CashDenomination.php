<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashDenomination extends Model
{
    use HasFactory;

    protected $fillable = [
        'daily_cash_summary_id',
        'value',
        'count',
        'is_coin',
    ];

    protected $casts = [
        'value' => 'float',
        'count' => 'float',
    ];
    

    public function dailyCashSummary()
    {
        return $this->belongsTo(DailyCashSummary::class);
    }
}
