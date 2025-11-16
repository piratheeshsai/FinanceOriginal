<?php

namespace App\Livewire\Loan;

use App\Models\Center;
use App\Models\LoanCollectionSchedule;
use App\Services\LoanCollectionService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class LoanCollectionTable extends Component
{
    use WithPagination;

    public $perPage = 250;
    public $centerId;
    public $searchTerm = '';
    public $selectedCollection = null;
    public $showModal = false;
    public $startDate;
    public $endDate;
    public $dateToday;
    public $loanType;



    public $repaymentAmount;
    public $repaymentMethod;
    public $description;
    public $collectedBy;
    public $collectionDate;
    public $loanId;

    public function viewCollection($scheduleId)
    {

        $this->dispatch('openCollectionModal', $scheduleId);
    }



    public function mount()
    {
        // Set default date to today
        $this->dateToday = now()->format('Y-m-d');
        $this->startDate = now()->subMonth()->format('Y-m-d'); // Default: last month
        $this->endDate = now()->format('Y-m-d'); // Default: today
    }




    public function exportPdf()
    {
        $baseQuery = $this->getQuery();

        $data = [
            'collections' => $baseQuery->get(),
            'totalPending' => $baseQuery->sum('pending_due'),
            'totalDue' => $baseQuery->sum('due'),
            'collectionCount' => $baseQuery->count(),
            'date' => now()->format('Y-m-d'),
            'filters' => [
                'centerId' => $this->centerId,
                'searchTerm' => $this->searchTerm
            ]
        ];

        $pdf = Pdf::loadView('exports.today-collections-pdf', $data)
            ->setPaper('a4', 'landscape');

        return response()->streamDownload(
            fn() => print($pdf->output()),
            'today-collections-' . now()->format('Ymd-His') . '.pdf'
        );
    }


    public function closeModal()
    {
        $this->showModal = false;
        $this->showModal = false;
        $this->selectedCollection = null;
        $this->reset(['repaymentAmount', 'repaymentMethod', 'description', 'loanId']);
    }




    private function getQuery()
    {
        return LoanCollectionSchedule::with(['loan.customer', 'loan.center'])
            ->where('description', 'Installment Payment')
            ->where('pending_due', '>', 20)
            ->when($this->centerId, fn($q) => $q->whereHas('loan', fn($q) => $q->where('center_id', $this->centerId)))
            ->when($this->searchTerm, function ($q) {
                $searchTerm = '%' . trim($this->searchTerm) . '%';
                $q->whereHas('loan.customer', function ($query) use ($searchTerm) {
                    $query->where(function ($q) use ($searchTerm) {
                        $q->where('full_name', 'like', $searchTerm)
                            ->orWhere('customer_phone', 'like', $searchTerm);
                    });
                });
            })
            ->when($this->startDate && $this->endDate, function ($q) {
                $q->whereBetween('date', [$this->startDate, $this->endDate]);
            })
            ->when($this->loanType, function ($q) {
                $q->whereHas('loan', fn($q) => $q->where('loan_type', $this->loanType));
            })
            ->when(!Auth::user()->hasPermissionTo('view all branches'), function ($q) {
                $q->whereHas('loan.center', fn($q) => $q->where('branch_id', Auth::user()->branch_id));
            })
            ->orderBy('date', 'desc')->orderBy('created_at', 'desc');;
    }


    public function render()
    {
        $baseQuery = $this->getQuery();


        $centers = Center::when(
            !Auth::user()->hasPermissionTo('view all branches'),
            fn($q) => $q->where('branch_id', Auth::user()->branch_id)
        )->get();

        return view('livewire.loan.loan-collection-table', [
            'schedules' => $baseQuery->paginate($this->perPage),
            'centers' => $centers,
            'totalPending' => $baseQuery->sum('pending_due'),
            'collectionCount' => $baseQuery->count(),
            'totalDue' => $baseQuery->sum('due')
        ]);
    }
}
