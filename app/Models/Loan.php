<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Loan extends Model
{
    use HasFactory, LogsActivity;

    use SoftDeletes;


    protected $table = 'loan';

    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];
     protected $casts = [
        'start_date' => 'datetime',
    ];


    protected $fillable = [
        'loan_number',       // Unique loan number
        'loan_type',         // Loan type: individual or group
        'customer_id',       // Nullable for group loans
        'group_id',          // For group loans only
        'center_id',         // Center of the loan
        'scheme_id',            // Loan scheme details
        'loan_amount',       // Loan amount
        'start_date',        // Set after approval
        'loan_creator_name', // Name of the loan creator
        'status',
        'document_charge',
        'loan_date'// Loan status (Pending, Approved, etc.)
    ];


    public function approval()
    {
        return $this->hasOne(LoanApproval::class);
    }


    public function center()
    {
        return $this->belongsTo(Center::class);
    }



    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'loan_number',       // Unique loan number
                'loan_type',         // Loan type: individual or group
                'customer_id',       // Nullable for group loans
                'group_id',          // For group loans only
                'center_id',         // Center of the loan
                'scheme_id',            // Loan scheme details
                'loan_amount',       // Loan amount
                'start_date',        // Set after approval
                'loan_date',         // Date when the loan was created
                'loan_creator_name', // Name of the loan creator
                'status',
                'document_charge'
            ]) // Log only selected fields
            ->logOnlyDirty() // Log only when values change
            ->dontSubmitEmptyLogs(); // Prevents empty logs
    }




    public function loanProgress()
    {
        return $this->hasOne(LoanProgress::class, 'loan_id');
    }

    public function loanScheme()
    {
        return $this->belongsTo(LoanScheme::class, 'scheme_id');
    }


    public function collections()
    {
        return $this->hasMany(LoanCollectionSchedule::class, 'loan_id');
    }

    public function loanCollections()
    {
        return $this->hasMany(Collection::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }



    ///this is for loanGuarantors pivot table
    public function guarantors()
    {
        return $this->belongsToMany(Customer::class, 'loan_guarantors', 'loan_id', 'customer_id')
            ->withTimestamps();
    }

    public function loanCollectionSchedules()
    {
        return $this->hasMany(LoanCollectionSchedule::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }


    // public function loanGuarantors()
    // {
    //     return $this->hasMany(LoanGuarantor::class);
    // }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($loan) {
            // Automatically create a corresponding LoanApproval record
            $loan->approval()->create([
                'status' => 'Pending',
            ]);
        });
    }


    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeBranchFilter($query, $branchId)
    {
        return $branchId === 'all'
            ? $query
            : $query->where('branch_id', $branchId);
    }

     public function voucher()
    {
        return $this->hasOne(Voucher::class, 'reference_id')
            ->where('type', 'Loan Disbursement');
    }



    
}
