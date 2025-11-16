<?php

namespace App\Livewire\Reports;

use App\Models\LoanCollectionSchedule;
use App\Models\Center;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PendingCollectionsExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class PendingCollection extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $dateFrom;
    public $dateTo;
    public $centerId;

   
    private function getQuery()
    {
        return LoanCollectionSchedule::with(['loan.customer', 'loan.center'])
            ->where('description', 'Installment Payment')
            ->where('pending_due', '>', 20)
            ->when($this->dateFrom, fn($q) => $q->whereDate('date', '>=', $this->dateFrom))
            ->when($this->dateTo, fn($q) => $q->whereDate('date', '<=', $this->dateTo))
            ->when($this->centerId, fn($q) => $q->whereHas('loan', fn($q) => $q->where('center_id', $this->centerId)))
            // Spatie permission check for branch access
            ->when(!Auth::user()->hasPermissionTo('view all branches'), function($q) {
                $q->whereHas('loan.center', fn($q) => $q->where('branch_id', Auth::user()->branch_id));
            })
            ->whereDate('date', '<', now())
            ->orderBy('date', 'desc')
            ->select(['date', 'loan_id', 'due', 'paid', 'pending_due', 'description']);
    }

    public function exportPdf()
    {
        $baseQuery = $this->getQuery();

        $data = [
            'collections' => $baseQuery->get(),
            'totalPending' => $baseQuery->sum('pending_due'),
            'overdueCount' => $baseQuery->count(),
            'averageDelay' => $this->getAverageDelay(),
            'filters' => [
                'dateFrom' => $this->dateFrom,
                'dateTo' => $this->dateTo,
                'centerId' => $this->centerId
            ]
        ];

        $pdf = Pdf::loadView('exports.pending-collections-pdf', $data)
            ->setPaper('a4', 'landscape');

        return response()->streamDownload(
            fn () => print($pdf->output()),
            'pending-collections-'.now()->format('Ymd-His').'.pdf'
        );
    }

    private function getAverageDelay()
    {
        return LoanCollectionSchedule::where('description', 'Installment Payment')
            ->where('pending_due', '>', 20)
            ->when($this->dateFrom, fn($q) => $q->whereDate('date', '>=', $this->dateFrom))
            ->when($this->dateTo, fn($q) => $q->whereDate('date', '<=', $this->dateTo))
            ->when($this->centerId, fn($q) => $q->whereHas('loan', fn($q) => $q->where('center_id', $this->centerId)))
            // Spatie permission check for branch access
            ->when(!Auth::user()->hasPermissionTo('view all branches'), function($q) {
                $q->whereHas('loan.center', fn($q) => $q->where('branch_id', Auth::user()->branch_id));
            })
            ->whereDate('date', '<', now())
            ->selectRaw('AVG(DATEDIFF(NOW(), date)) as average_delay')
            ->value('average_delay') ?? 0;
    }


    public function exportExcel()
    {
        $data = $this->getQuery()->get();

        return Excel::download(
            new PendingCollectionsExport($data),
            'pending-collections-'.now()->format('Ymd-His').'.xlsx'
        );
    }



    public function render()
    {
        $baseQuery = $this->getQuery();

        // Get centers based on Spatie permission
        $centers = Center::when(
            !Auth::user()->hasPermissionTo('view all branches'),
            fn($q) => $q->where('branch_id', Auth::user()->branch_id)
        )->get();

        return view('livewire.reports.pending-collection', [
            'schedules' => $baseQuery->paginate($this->perPage),
            'centers' => $centers,
            'totalPending' => $baseQuery->sum('pending_due'),
            'overdueCount' => $baseQuery->count(),
            'averageDelay' => round($this->getAverageDelay())
        ]);
    }
}
