<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchAccount extends Model
{
    use HasFactory;
    protected $table = 'branch_accounts';

    protected $fillable = [
        'branch_id',
        'balance'];

        public function dailyCashSummaries()
    {
        return $this->morphMany(DailyCashSummary::class, 'account');
    }
}
