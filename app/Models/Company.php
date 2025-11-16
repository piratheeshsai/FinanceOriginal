<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'logo',
        'capital_balance',
        'name',
        'email',
        'phone',
        'address',
        'website',
        'registration_no',
    ];



}
