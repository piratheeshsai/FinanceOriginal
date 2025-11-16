<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupMember extends Model
{
    use HasFactory;


    protected $table = 'group_members';


    public function customer()
{
    return $this->belongsTo(Customer::class, 'customer_id');
}

 public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
