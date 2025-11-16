<?php
namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomerExport implements FromQuery, WithMapping, WithHeadings
{
    public function __construct(public $search, public $center, public $sortBy, public $sortDirection) {}

    public function query()
    {
        return Customer::query()
            ->when($this->search, fn($q) => $q->where('full_name', 'like', "%{$this->search}%"))
            ->when($this->center, fn($q) => $q->where('center_id', $this->center))
            ->orderBy($this->sortBy, $this->sortDirection);
    }

    public function map($customer): array
    {
        return [
            $customer->id,
            $customer->full_name,
            $customer->center->name ?? '',
            $customer->created_at->format('Y-m-d'),
        ];
    }

    public function headings(): array
    {
        return ['ID', 'Full Name', 'Center', 'Created Date'];
    }
}
