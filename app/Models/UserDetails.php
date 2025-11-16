<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetails extends Model
{
    use HasFactory;

    protected $table = 'user_details';

    protected $fillable = [
        'user_id',
        'employee_id',
        'nic_no',
        'profile_photo',
        'address',
        'phone_number',
        'gender',
        'age',
    ];



    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
