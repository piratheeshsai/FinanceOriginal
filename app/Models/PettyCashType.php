<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PettyCashType extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
    ];

    public function pettyCashRequests()
    {
        return $this->hasMany(PettyCashRequest::class, 'type_id');
    }
}
