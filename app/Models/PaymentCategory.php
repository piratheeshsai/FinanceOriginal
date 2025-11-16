<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name'];
    const SALARY_ID = 1;
   

    // In PaymentCategory.php
public function suppliers()
{
    return $this->hasMany(PaymentSupplier::class);
}

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
