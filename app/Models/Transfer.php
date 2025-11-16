<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'from_account_id',
        'to_account_id',
        'amount',
        'description',
        'status',
        'created_by',
        'approved_by',
        'rejection_reason',
        'collector_id'
    ];


    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    // A transfer has one source account



    public function fromAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'from_account_id');
    }

    public function toAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'to_account_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // A transfer is approved by a user (optional)
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // A transfer can have multiple transactions
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

}
