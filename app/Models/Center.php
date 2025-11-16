<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Center extends Model
{
    use HasFactory;


    protected $table = 'centers';
    protected $fillable = ['branch_id', 'name', 'center_code'];


    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
// branch_id
