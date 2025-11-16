<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Payment extends Model
{
    use HasFactory, LogsActivity;
    protected $fillable = [
        'payment_category_id',
        'total_amount',
        'status',
        'rejection_reason',
        'attachments',
        'created_by',
        'approved_by',
        'branch_id'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'payment_category_id',
                'total_amount',
                'status',
                'rejection_reason',
                'attachments',
                'created_by',
                'approved_by',
                'branch_id'
            ]) 
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
    public function paymentCategory()
    {
        return $this->belongsTo(PaymentCategory::class);
    }


    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function suppliers()
    {
        return $this->belongsToMany(PaymentSupplier::class, 'payment_supplier_pivot', 'payment_id', 'supplier_id')
            ->withPivot('amount');
    }

    public function voucher()
    {
        return $this->hasOne(Voucher::class, 'reference_id')
            ->where('type', 'Payment');
    }
}
