<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FinancialReportExport implements FromCollection, FromArray, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $reportData;
    protected $data;

    public function collection()
    {
        //
        return collect($this->data);
    }

    public function __construct(array $reportData)
    {
        $this->reportData = $reportData;
    }

    public function array(): array
    {
        $exportData = [];

        foreach ($this->reportData as $branchId => $branchData) {
            if ($branchId === 'totals') {
                continue; // Skip total entry
            }

            $exportData[] = [
                'Branch Name' => $branchData['branch']->name,
                'Total Assets' => $branchData['summary']['total_assets'],
                'Total Liabilities' => $branchData['summary']['total_liabilities'],
                'Total Equity' => $branchData['summary']['total_equity'],
                'Total Revenue' => $branchData['summary']['total_revenue'],
                'Total Expenses' => $branchData['summary']['total_expenses'],
                'Net Income' => $branchData['summary']['net_income'],
            ];
        }

        return $exportData;
    }

    public function headings(): array
    {
        return ['Branch Name', 'Total Assets', 'Total Liabilities', 'Total Equity', 'Total Revenue', 'Total Expenses', 'Net Income'];
    }
}


