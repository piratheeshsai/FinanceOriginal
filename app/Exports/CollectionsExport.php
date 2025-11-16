<?php
namespace App\Exports;

use App\Models\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class CollectionsExport implements FromCollection, WithHeadings, WithColumnFormatting, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Collection::with(['loan', 'staff']) // Ensure necessary relationships
            ->get()
            ->map(function ($collection) {
                return [
                    'Date' => $collection->collection_date,
                    'Customer Name' => optional($collection->loan->customer)->full_name,
                    'Loan Number' => optional($collection->loan)->loan_number,
                    'Staff Name' => optional($collection->staff)->name,
                    'Method' => $collection->collection_method,
                    'Collected Amount' => $collection->collected_amount,
                ];
            });
    }

    /**
    * Return the headings for the export.
    *
    * @return array
    */
    public function headings(): array
    {
        return ['Date', 'Customer Name', 'Loan Number', 'Staff Name', 'Method', 'Collected Amount'];
    }

    /**
    * Return the column formats for the export.
    *
    * @return array
    */
    public function columnFormats(): array
    {
        return [
            'F' => NumberFormat::FORMAT_NUMBER_00,
        ];
    }

    /**
    * Register the events for the export.
    *
    * @return array
    */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (\Maatwebsite\Excel\Events\AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow() + 1;
                $totalCollectedAmount = Collection::sum('collected_amount');

                $sheet->setCellValue('E' . $lastRow, 'Total');
                $sheet->setCellValue('F' . $lastRow, $totalCollectedAmount);
            },
        ];
    }
}
