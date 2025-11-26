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

    public $perPage = 40;
    public $dateFrom;
    public $dateTo;
    public $centerId;
    public $status;

    private function getQuery()
    {
        return LoanCollectionSchedule::with(['loan.customer', 'loan.center'])
            ->whereHas('loan') // Only get schedules with existing loans
            ->where('description', 'Installment Payment')
            ->where('pending_due', '>', 0)
            ->whereDate('date', '<', now()) // Only overdue (past due date)
            ->when($this->dateFrom, fn($q) => $q->whereDate('date', '>=', $this->dateFrom))
            ->when($this->dateTo, fn($q) => $q->whereDate('date', '<=', $this->dateTo))
            ->when($this->centerId, fn($q) => $q->whereHas('loan', fn($q) => $q->where('center_id', $this->centerId)))
            ->when(!Auth::user()->hasPermissionTo('view all branches'), function($q) {
                $q->whereHas('loan.center', fn($q) => $q->where('branch_id', Auth::user()->branch_id));
            })
            ->orderBy('date', 'desc') // Changed from 'asc' to 'desc'
            ->select('id', 'date', 'loan_id', 'due', 'paid', 'pending_due', 'description');
    }

    // Get today's overdue amount (payments due today but not paid)
    private function getTodayOverdueAmount()
    {
        return LoanCollectionSchedule::whereHas('loan')
            ->where('description', 'Installment Payment')
            ->where('pending_due', '>', 0)
            ->whereDate('date', '=', now()->toDateString())
            ->when($this->centerId, fn($q) => $q->whereHas('loan', fn($q) => $q->where('center_id', $this->centerId)))
            ->when(!Auth::user()->hasPermissionTo('view all branches'), function($q) {
                $q->whereHas('loan.center', fn($q) => $q->where('branch_id', Auth::user()->branch_id));
            })
            ->sum('pending_due');
    }

    // Get today's overdue count
    private function getTodayOverdueCount()
    {
        return LoanCollectionSchedule::whereHas('loan')
            ->where('description', 'Installment Payment')
            ->where('pending_due', '>', 0)
            ->whereDate('date', '=', now()->toDateString())
            ->when($this->centerId, fn($q) => $q->whereHas('loan', fn($q) => $q->where('center_id', $this->centerId)))
            ->when(!Auth::user()->hasPermissionTo('view all branches'), function($q) {
                $q->whereHas('loan.center', fn($q) => $q->where('branch_id', Auth::user()->branch_id));
            })
            ->distinct('loan_id')
            ->count('loan_id');
    }

    private function getAverageDelay()
    {
        return LoanCollectionSchedule::whereHas('loan')
            ->where('description', 'Installment Payment')
            ->where('pending_due', '>', 0)
            ->whereDate('date', '<', now())
            ->when($this->dateFrom, fn($q) => $q->whereDate('date', '>=', $this->dateFrom))
            ->when($this->dateTo, fn($q) => $q->whereDate('date', '<=', $this->dateTo))
            ->when($this->centerId, fn($q) => $q->whereHas('loan', fn($q) => $q->where('center_id', $this->centerId)))
            ->when(!Auth::user()->hasPermissionTo('view all branches'), function($q) {
                $q->whereHas('loan.center', fn($q) => $q->where('branch_id', Auth::user()->branch_id));
            })
            ->selectRaw('AVG(DATEDIFF(NOW(), date)) as average_delay')
            ->value('average_delay') ?? 0;
    }

    public function exportPdf()
    {
        set_time_limit(300);
        ini_set('memory_limit', '512M');

        try {
            $baseQuery = $this->getQuery();

            $data = [
                'collections' => $baseQuery->limit(1000)->get(),
                'totalOverdue' => $baseQuery->sum('pending_due'),
                'overdueCount' => $baseQuery->count(),
                'averageDelay' => round($this->getAverageDelay()),
                'todayOverdue' => $this->getTodayOverdueAmount(),
                'todayCount' => $this->getTodayOverdueCount(),
                'filters' => [
                    'dateFrom' => $this->dateFrom,
                    'dateTo' => $this->dateTo,
                    'centerId' => $this->centerId
                ]
            ];

            $pdf = Pdf::loadView('exports.pending-collections-pdf', $data)
                ->setPaper('a4', 'landscape')
                ->setOption('enable-local-file-access', true);

            return response()->streamDownload(
                fn () => print($pdf->output()),
                'overdue-collections-'.now()->format('Ymd-His').'.pdf'
            );
        } catch (\Exception $e) {
            \Log::error('PDF Export Error: ' . $e->getMessage());
            session()->flash('error', 'Failed to generate PDF. Please try again.');
            return null;
        }
    }

    public function exportExcel()
    {
        $data = $this->getQuery()->get();

        return Excel::download(
            new PendingCollectionsExport($data),
            'overdue-collections-'.now()->format('Ymd-His').'.xlsx'
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
            'averageDelay' => round($this->getAverageDelay()),
            'todayOverdueAmount' => $this->getTodayOverdueAmount(),
            'todayOverdueCount' => $this->getTodayOverdueCount()
        ]);
    }
}
