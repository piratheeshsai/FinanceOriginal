<?php

namespace App\Exports;

use App\Models\LoanCollectionSchedule;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PendingCollectionsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $collections;

    public function __construct($collections)
    {
        $this->collections = $collections;
    }

    public function collection()
    {
        return $this->collections;
    }

    public function headings(): array
    {
        return [
            'Date',
            'Loan Account',
            'Customer',
            'Due Amount',
            'Paid Amount',
            'Pending Amount',
            'Center',
        ];
    }

    public function map($collection): array
    {
        return [
            $collection->date->format('d M Y'),
            $collection->loan->loan_number,
            $collection->loan->customer->name,
            $collection->due,
            $collection->paid,
            $collection->pending_due,
            $collection->loan->center->name,
        ];
    }
}
