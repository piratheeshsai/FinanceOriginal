<?php

namespace App\Exports;

use App\Models\LoanScheme;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PaymentExport implements FromCollection, WithHeadings
{

    public $payments;

    public function __construct($payments)
    {
        $this->payments = $payments;
    }

    public function collection()
    {
        // Return data as a Collection for export
        return collect($this->payments)->map(function ($payment) {
            return [
                'Due Amount' => $payment['due'],
                'Due Interest' => $payment['interest'],
                'Due Total' => $payment['total'],
            ];
        });
    }
    public function headings(): array
    {
        // Define column headers
        return [
            'Due Amount',
            'Due Interest',
            'Due Total',
        ];
    }


}
