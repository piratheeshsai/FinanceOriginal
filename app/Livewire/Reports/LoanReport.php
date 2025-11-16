<?php

namespace App\Livewire\Reports;

use Livewire\Component;


    use App\Models\Loan;
    use App\Models\Centre;

    use Livewire\WithPagination;
    use Barryvdh\DomPDF\Facade\Pdf;
    use Maatwebsite\Excel\Facades\Excel;
    use App\Exports\LoanReportExport;
use App\Models\Center;

    class LoanReport extends Component
    {
        use WithPagination;

        public $search = '';
        public $center = '';
        public $statusFilter = '';
        public $startDate;
        public $endDate;
        public $perPage = 100;
        public $sortBy = 'created_at';
        public $sortDirection = 'desc';
        public $centers = [];

        public function mount()
        {
            $this->centers = Center::all();
            $this->startDate = now()->subMonth()->format('Y-m-d');
            $this->endDate = now()->format('Y-m-d');
        }

        public function updatingSearch()
        {
            $this->resetPage();
        }

        public function sortBy($field)
        {
            if ($this->sortBy === $field) {
                $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                $this->sortDirection = 'asc';
            }
            $this->sortBy = $field;
        }

        public function exportPDF($action = 'download')
        {
            $loans = $this->query()->limit(1000)->get();


            $pdf = Pdf::loadView('exports.loan-report', [
                'loans' => $loans,
                'search' => $this->search,
                'center' => $this->center ? Center::find($this->center)->name : null,
                'startDate' => $this->startDate,
                'endDate' => $this->endDate
            ])->setPaper('a4', 'landscape');

            if($action === 'print') {
                return response()->streamDownload(
                    fn() => print($pdf->output()),
                    'loan-report.pdf'
                );
            }

            return response()->streamDownload(
                fn() => print($pdf->output()),
                'loan-report-'.now()->format('Ymd-His').'.pdf'
            );
        }

        // public function exportExcel()
        // {
        //     return Excel::download(
        //         new LoanReportExport($this->query()->get()),
        //         'loan-report.xlsx'
        //     );
        // }

        private function query()
        {
            return Loan::with(['customer', 'approval'])
                ->whereHas('customer', function($query) {
                    $query->where('full_name', 'like', "%{$this->search}%");
                })
                ->when($this->center, fn($q) => $q->where('center_id', $this->center))
                ->when($this->statusFilter, function($q) {
                    $q->whereHas('approval', function($query) {
                        $query->where('status', $this->statusFilter);
                    });
                })
                ->when($this->startDate, fn($q) => $q->whereDate('created_at', '>=', $this->startDate))
                ->when($this->endDate, fn($q) => $q->whereDate('created_at', '<=', $this->endDate))
                ->orderBy($this->sortBy, $this->sortDirection);
        }

        public function render()
        {
            $loans = $this->query()->paginate($this->perPage);

            return view('livewire.reports.loan-report', [
                'loans' => $loans,
                'centers' => $this->centers
            ]);
        }
    }

