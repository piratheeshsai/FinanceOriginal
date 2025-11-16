<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class PettyCashRequest extends Model
{
    use HasFactory,LogsActivity;

    protected $fillable = [
        'branch_id',
        'account_id',
        'amount',
        'type_id',
        'status',
        'rejection_reason',
        'request_employee',
        'created_by',
        'approved_by',
        'attachments',
    ];


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
        'branch_id',
        'account_id',
        'amount',
        'type_id',
        'status',
        'rejection_reason',
        'request_employee',
        'created_by',
        'approved_by',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }




    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function type()
    {
        return $this->belongsTo(PettyCashType::class, 'type_id');
    }

    public function requestEmployee()
    {
        return $this->belongsTo(User::class, 'request_employee');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
    public function voucher()
    {
        return $this->hasOne(Voucher::class, 'reference_id')
                   ->where('type', 'Petty Cash');
    }
}
