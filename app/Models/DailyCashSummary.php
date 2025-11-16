<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyCashSummary extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'account_id',
        'date',
        'counted_amount',
        'system_amount',
        'difference',
        'remarks',
        'counted_by',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'date' => 'date',
        'counted_amount' => 'decimal:2',
        'system_amount' => 'decimal:2',
        'difference' => 'decimal:2',
        'verified_at' => 'datetime',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function countedBy()
    {
        return $this->belongsTo(User::class, 'counted_by');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function denominations()
    {
        return $this->hasMany(CashDenomination::class);
    }
}
