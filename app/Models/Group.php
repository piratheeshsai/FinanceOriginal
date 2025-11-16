<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Log;

class Group extends Model
{
    use HasFactory;

    protected $table = 'groups';

    protected $fillable = [
        'group_code',
        'group_leader',
        'center_id',
        'creator_id',
        'branch_id',
    ];


    public function members()
    {
        return $this->belongsToMany(Customer::class, 'group_members', 'group_id', 'customer_id');
    }

    public function leader()
    {
        return $this->belongsTo(Customer::class, 'group_leader');
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function center()
    {
        return $this->belongsTo(Center::class, 'center_id');
    }

    public function branch()
    {
        return $this->hasOneThrough(
            Branch::class,
            Center::class,
            'id', // Foreign key on Center table
            'id', // Foreign key on Branch table
            'center_id', // Local key on Group table
            'branch_id'  // Local key on Center table
        );
    }


    public function scopeFilterByRoleAndBranch(Builder $query, $user)
    {
        if ($user->hasRole(['admin', 'CEO'])) {
            return $query; // Admin and CEO can view all groups
        }

        if (is_null($user->branch_id)) {
            Log::warning('User with ID ' . $user->id . ' has no branch assigned.');
            return $query->whereRaw('1 = 0'); // No results for users without a branch
        }

        return $query->whereHas('center.branch', function ($q) use ($user) {
            $q->where('branches.id', $user->branch_id);
        });
    }


    public static function generateGroupCode($branchId, $centerId)
{
    // Fetch the branch and center codes
    $branch = Branch::find($branchId);
    $center = Center::find($centerId);

    if (!$branch || !$center) {
        throw new \Exception('Invalid Branch or Center.');
    }

    $branchCode = str_pad($branch->branch_code, 3, '0', STR_PAD_LEFT); // Ensure 3 digits
    $centerCode = str_pad($center->center_code, 3, '0', STR_PAD_LEFT); // Ensure 3 digits

    // Get the last group number for this branch and center
    $lastGroup = Group::where('branch_id', $branchId)
                      ->where('center_id', $centerId)
                      ->latest('id') // Get the last created group
                      ->first();

    $lastGroupNumber = $lastGroup ? (int) substr($lastGroup->group_code, -3) : 0;

    // Increment the group number
    $newGroupNumber = str_pad($lastGroupNumber + 1, 3, '0', STR_PAD_LEFT);

    // Generate the full group code
    return "G{$branchCode}/{$centerCode}/{$newGroupNumber}";
}

}
