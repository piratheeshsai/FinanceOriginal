<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Transaction extends Model
{
    use HasFactory,LogsActivity;

    protected $fillable = [
        'branch_id',
        'debit_account_id',
        'credit_account_id',
        'transfer_id',
        'transaction_date',
        'transaction_type',
        'amount',
        'description',
        'status',
        'pending',
        'created_by',
        'approved_by',
        'loan_id',
        'customer_id',
        'collection_id',

    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'branch_id',
                'debit_account_id',
                'credit_account_id',
                'transfer_id',
                'transaction_date',
                'transaction_type',
                'amount',
                'description',
                'status',
                'pending',
                'created_by',
                'approved_by',
                'loan_id',
                'customer_id',
                'collection_id',
            ]) 
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }


    protected $casts = [
        'amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    // A transaction belongs to an account
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    // A transaction can belong to a transfer (optional)
    public function transfer()
    {
        return $this->belongsTo(Transfer::class);
    }
    // A transaction is created by a user
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // A transaction is approved by a user (optional)
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }


    public function scopePettyCash($query)
    {
        return $query->where('type', 'petty_cash_approval');
    }


    public function debitAccount()
{
    return $this->belongsTo(Account::class, 'debit_account_id');
}

public function creditAccount()
{
    return $this->belongsTo(Account::class, 'credit_account_id');
}



}
