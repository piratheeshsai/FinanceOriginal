<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Account extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'account_number',
        'account_name',
        'branch_id',
        'type',
        'balance',
        'category',
        ];


        public function getActivitylogOptions(): LogOptions
        {
            return LogOptions::defaults()
                ->logOnly(['account_number', 'account_name', 'type', 'balance'])
                // Optional: Log changes to relations
                ->logOnlyDirty()
                ->dontSubmitEmptyLogs();
        }




        public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    // An account can be the source for multiple transfers
    public function outgoingTransfers()
    {
        return $this->hasMany(Transfer::class, 'from_account_id');
    }
    public function incomingTransfers()
    {
        return $this->hasMany(Transfer::class, 'to_account_id');
    }

    // An account can have multiple transactions
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
    protected $casts = [
        'balance' => 'float'
    ];
}
