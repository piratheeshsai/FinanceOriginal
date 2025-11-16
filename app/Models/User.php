<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, HasFactory, HasProfilePhoto, TwoFactorAuthenticatable, HasRoles,LogsActivity;
    use HasRoles;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'branch_id',
        'last_seen_at'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'name',
                'email',
                'password',
                'branch_id'
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];


    public function collections()
    {
        return $this->hasMany(Collection::class, 'collector_id');
    }

    public function details()
    {
        return $this->hasOne(UserDetails::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }



    public function allowedFromAccounts()
    {
        return Account::query()
            ->where(function ($query) {
                // Get account types user has permission to transfer from
                $allowedTypes = $this->getAllowedTransferTypes('from');

                if (!empty($allowedTypes)) {
                    $query->whereIn('type', $allowedTypes);
                }

                // Branch managers can only access their branch accounts
                if ($this->hasRole('Branch Manager')) {
                    $query->where('branch_id', $this->branch_id);
                }
            });
    }

    public function allowedToAccounts()
    {
        return Account::query()
            ->where(function ($query) {
                $allowedTypes = $this->getAllowedTransferTypes('to');

                if (!empty($allowedTypes)) {
                    $query->whereIn('type', $allowedTypes);
                }

                if ($this->hasRole('Branch Manager')) {
                    $query->where('branch_id', $this->branch_id);
                }
            });
    }

    private function getAllowedTransferTypes(string $direction)
    {
        return $this->getPermissionsViaRoles()
            ->filter(fn ($permission) => str_starts_with($permission->name, "transfer {$direction} "))
            ->map(fn ($permission) => str_replace("transfer {$direction} ", '', $permission->name))
            ->unique()
            ->values()
            ->toArray();
    }

    public function createdTransactions()
    {
        return $this->hasMany(Transaction::class, 'created_by');  // Foreign key is created_by
    }

    // Relationship for transactions where the user is the approver
    public function approvedTransactions()
    {
        return $this->hasMany(Transaction::class, 'approved_by');  // Foreign key is approved_by
    }


}
