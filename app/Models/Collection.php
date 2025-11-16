<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\SoftDeletes;

class Collection extends Model
{
    use HasFactory, LogsActivity;
use SoftDeletes;
    protected $fillable = [
        'loan_id',
        'collector_id',
        'collected_amount',
        'collection_date',
        'transfer_id',
        'collection_method',
        'principal_amount',
        'interest_amount'
    ];



    public function collector()
    {
        return $this->belongsTo(User::class, 'collector_id');
    }

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }



 public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'collected_amount',
                'principal_amount',
                'interest_amount',
                'collection_date',
                'collection_method'
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(function(string $eventName) {
                return "Collection was {$eventName}";
            });
    }









    // Custom tap method to add additional context to the log
    public function tapActivity($activity, string $eventName)
    {
        // Get related loan info
        if ($this->loan) {
            $activity->properties = $activity->properties->merge([
                'loan_number' => $this->loan->loan_number ?? null,
                'borrower' => $this->loan->borrower->name ?? 'Unknown',
            ]);
        }

        // Add collector info
        if ($this->collector) {
            $activity->properties = $activity->properties->merge([
                'collector_name' => $this->collector->name ?? 'Unknown'
            ]);
        }

        // For amount changes, add specific details
        if ($eventName == 'updated' &&
            isset($activity->properties['attributes']['collected_amount']) &&
            isset($activity->properties['old']['collected_amount'])) {

            $oldAmount = $activity->properties['old']['collected_amount'];
            $newAmount = $activity->properties['attributes']['collected_amount'];

            $activity->description = "Collection amount modified from {$oldAmount} to {$newAmount}";
        }
    }


    // Relationship to Loan

    public function staff()
    {
        return $this->belongsTo(User::class, 'collector_id');
    }

    // Relationship to Transfer (if any)
    public function transfer()
    {
        return $this->belongsTo(Transfer::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function staffCollectionStatus(): HasOne
    {
        return $this->hasOne(StaffCollectionStatus::class, 'collection_id', 'id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'collection_id');
    }

    public static function filteredSum(array $filters, string $column)
    {
        return self::query()
            ->when($filters['dateFrom'], fn($q) => $q->whereDate('collection_date', '>=', $filters['dateFrom']))
            ->when($filters['dateTo'], fn($q) => $q->whereDate('collection_date', '<=', $filters['dateTo']))
            ->when($filters['collectorId'], fn($q) => $q->where('collector_id', $filters['collectorId']))
            ->when($filters['status'], fn($q) => $q->whereHas('staffCollectionStatus', fn($q) => $q->where('status', $filters['status'])))
            ->when($filters['branchId'], fn($q) => $q->whereHas('branch', fn($q) => $q->where('id', $filters['branchId'])))
            ->sum($column);
    }

    protected $casts = [
        'collection_date' => 'date', // or 'datetime' if you store time
    ];
}
