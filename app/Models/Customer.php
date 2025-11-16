<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Builder;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Log;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Customer extends Model
{
    use HasFactory,LogsActivity;

    protected $table = 'customers';

    protected $fillable = [
        'center_id',
        'full_name',
        'customer_no',
        'nic',
        'permanent_address',
        'living_address',
        'permanent_city',
        'living_city',
        'customer_phone',
        'living_address',
        'city',
        'date_of_birth',
        'gender',
        'civil_status',
        'occupation',


        // Relationships
        'spouse_name',
        'spouse_nic',
        'Spouse_phone',
        'spouse_occupation',
        'spouse_age',

        // Family
        'home_phone',
        'family_members',
        'income_earners',
        'family_income',

        'photo',
        'nic_copy',
    ];


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'full_name',
                'customer_no',
                'nic',
                'customer_phone',

            ]) // Log only selected fields
            ->logOnlyDirty() // Log only when values change
            ->dontSubmitEmptyLogs(); // Prevents empty logs
    }



    public function loans()
    {
        return $this->hasMany(Loan::class, 'customer_id');
    }

    public function groupMembers()
    {
        return $this->hasMany(GroupMember::class, 'customer_id');
    }


    public function scopeSearch($query, $value)
    {
        $query->where('full_name', 'like', "%{$value}%")->orWhere('nic', 'like', "%{$value}%")->orWhere('spouse_nic', 'like', "%{$value}%");
    }

    // public function types()
    // {
    //     return $this->belongsToMany(Type::class);
    // }
    public function types()
    {
        return $this->belongsToMany(Type::class, 'customer_type', 'customer_id', 'type_id')->withTimestamps();
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_members', 'customer_id', 'group_id');
    }


    public function center()
    {
        return $this->belongsTo(Center::class, 'center_id');
    }



    // Define the relationship with LoanGuarantor
    public function loanGuarantors()
    {
        return $this->hasMany(LoanGuarantor::class);
    }


    public function branch()
    {
        return $this->hasOneThrough(Branch::class, Center::class, 'id', 'id', 'center_id', 'branch_id');
    }
    public function scopeFilterByRoleAndBranch(Builder $query, $user)
    {
        if ($user->hasRole(['admin', 'CEO'])) {
            return $query; // Show all data for admin and CEO
        }
        Log::info('User Branch ID: ' . $user->branch_id);
        // Other users see only customers in their branch
        return $query->whereHas('center.branch', function ($q) use ($user) {
            $q->where('branches.id', $user->branch_id); // Explicitly reference 'branches.id'
        });


    }


    public function scopeExcludeQuarter($query)
    {
        return $query->where('role', '!=', 'quarter');  // Exclude "quarter" role customers
    }
}
