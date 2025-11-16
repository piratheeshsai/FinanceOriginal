<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class LoanApproval extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'loan_id',
        'status',
        'approved_by',
        'approved_at',
        'rejection_reason',  // Add rejection_reason to fillable
    ];
    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'loan_id',
                'status',
            ]) // Log only selected fields
            ->logOnlyDirty() // Log only when values change
            ->dontSubmitEmptyLogs(); // Prevents empty logs
    }



    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

}
