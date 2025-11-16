<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffCollectionStatus extends Model
{
    use HasFactory;

    protected $table = 'staff_collection_status';

    protected $fillable = [
        'collection_id',
        'transaction_id',
        'status'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }



    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class, 'collection_id', 'id'); // Ensure correct foreign key
    }



}
