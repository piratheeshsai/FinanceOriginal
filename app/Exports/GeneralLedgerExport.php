<?php
// filepath: f:\Finance\RDI\Finance - Copy\app\Exports\GeneralLedgerExport.php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class GeneralLedgerExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $fromDate;
    protected $toDate;
    protected $search;

    public function __construct($fromDate = null, $toDate = null, $search = null)
    {
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
        $this->search = $search;
    }

    public function collection()
    {
        $query = Transaction::with(['debitAccount', 'creditAccount'])
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($this->fromDate) {
            $query->whereDate('created_at', '>=', $this->fromDate);
        }

        if ($this->toDate) {
            $query->whereDate('created_at', '<=', $this->toDate);
        }

        if ($this->search) {
            $query->where(function($q) {
                $q->where('description', 'like', '%' . $this->search . '%')
                  ->orWhereHas('debitAccount', function($sub) {
                      $sub->where('account_name', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('creditAccount', function($sub) {
                      $sub->where('account_name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Date',
            'Time',
            'Description',

            'Debit Account',

            'Credit Account',

            'Amount (Rs.)',
            'Debit Effect',
            'Credit Effect'
        ];
    }

    public function map($transaction): array
    {
        $debitAccount = $transaction->debitAccount;
        $creditAccount = $transaction->creditAccount;

        $debitCategory = $debitAccount->category ?? 'unknown';
        $creditCategory = $creditAccount->category ?? 'unknown';

        $isDebitIncrease = in_array($debitCategory, ['asset', 'expense']);
        $isCreditIncrease = in_array($creditCategory, ['liability', 'equity', 'revenue']);

        return [
            $transaction->created_at->format('Y-m-d'),
            $transaction->created_at->format('H:i:s'),
            $transaction->description ?? 'Transaction Entry',

            $debitAccount->account_name ?? 'Unknown Account',

            $creditAccount->account_name ?? 'Unknown Account',

            number_format($transaction->amount, 2),
            ($isDebitIncrease ? '+' : '-') . ' Rs. ' . number_format($transaction->amount, 2),
            ($isCreditIncrease ? '+' : '-') . ' Rs. ' . number_format($transaction->amount, 2),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
            'A:K' => ['alignment' => ['horizontal' => 'left']],
            'I:K' => ['alignment' => ['horizontal' => 'right']],
        ];
    }
}
