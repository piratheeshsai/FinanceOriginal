<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Branch extends Model
{
    use HasFactory,LogsActivity;

    protected $table = 'branches';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'user_id',
        'branch_code'
    ];



    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'phone', 'branch_code']) // Log only these fields
            ->logOnlyDirty() // Log only when values change
            ->dontSubmitEmptyLogs(); // Prevent empty logs
    }



    public function branchAccounts()
    {
        return $this->hasMany(BranchAccount::class);
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function centers()
    {
        return $this->hasMany(Center::class);
    }
}
