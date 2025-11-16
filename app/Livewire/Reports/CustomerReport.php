<?php

namespace App\Livewire\Reports;

use App\Exports\CustomerExport;
use App\Models\Center;
use App\Models\Customer;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class CustomerReport extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';
    public $center;
public $centers = [];

public function mount()
{
    $this->centers = Center::all();
}




    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        $this->sortDirection = ($this->sortBy === $field && $this->sortDirection === 'asc') ? 'desc' : 'asc';
        $this->sortBy = $field;
    }

    public function exportPDF()
    {
        $customers = Customer::where('full_name', 'like', "%{$this->search}%")
        ->when($this->center, fn($q) => $q->where('center_id', $this->center))
        ->orderBy($this->sortBy, $this->sortDirection)
        ->limit(1000)
        ->get();


            $pdf = Pdf::loadView('exports.customer-report', [
                'customers' => $customers,
                'search' => $this->search,
                'startDate' => $this->startDate ?? null, // Add if using date filters
                'endDate' => $this->endDate ?? null   ,   // Add if using date filters
                'centre' => $this->center ? Center::find($this->center)->name : null,
            ]);
        return response()->streamDownload(fn() => print($pdf->output()), 'customer_report.pdf');
    }

    public function exportExcel()
{
    return Excel::download(new CustomerExport(
        $this->search,
        $this->center,
        $this->sortBy,
        $this->sortDirection
    ), 'customer_report.xlsx');
}

    public function render()
    {
        $customers = Customer::with(['loans.approval'])->where('full_name', 'like', "%{$this->search}%")
            ->orderBy($this->sortBy, $this->sortDirection)
            ->when($this->center, fn($q) => $q->where('center_id', $this->center))
            ->paginate($this->perPage);

            return view('livewire.reports.customer-report', [
                'customers' => $customers,
                'center' => $this->centers
            ]);
    }
}
