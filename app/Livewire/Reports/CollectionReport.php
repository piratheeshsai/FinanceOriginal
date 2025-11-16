<?php

namespace App\Livewire\Reports;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Collection;
use App\Models\User;
use App\Models\Branch;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CollectionsExport;
use Barryvdh\DomPDF\Facade\Pdf;

class CollectionReport extends Component
{
    use WithPagination;

    public $dateFrom;
    public $dateTo;
    public $collectorId;
    public $status;
    public $branchId;
    public $perPage = 150;
    public $sortBy = 'collection_date';
    public $sortDirection = 'desc';

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortBy = $field;
    }

    public function exportExcel()
    {
        $filters = $this->getFilters();
        return Excel::download(
            new CollectionsExport($filters),
            $this->generateFileName('xlsx')
        );
    }

    public function exportPdf()
    {
        $filters = $this->getFilters();
        $data = $this->getExportData();

        // Fix UTF-8 encoding issues
        array_walk_recursive($data, function (&$item) {
            if (is_string($item)) {
                $item = mb_convert_encoding($item, 'UTF-8', 'auto');
                $item = html_entity_decode($item, ENT_QUOTES, 'UTF-8');
                $item = iconv('UTF-8', 'UTF-8//IGNORE', $item);
            }
        });

        $pdf = Pdf::loadView('exports.collectionsPdf', $data)
            ->setPaper('a4', 'portrait')
            ->setOption('isUnicode', true)
            ->setOption('isRemoteEnabled', true);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, $this->generateFileName('pdf'));
    }



    private function getFilters()
    {
        return [
            'dateFrom' => $this->dateFrom,
            'dateTo' => $this->dateTo,
            'collectorId' => $this->collectorId,
            'status' => $this->status,
            'branchId' => $this->branchId,
            'sortBy' => $this->sortBy,
            'sortDirection' => $this->sortDirection
        ];
    }

    private function generateFileName($extension)
    {
        return 'collections-report-'.now()->format('Ymd-His').'.'.$extension;
    }

    private function getExportData()
    {
        return [
            'collections' => Collection::with([
                    'loan.customer',
                    'collector',
                    'staffCollectionStatus',
                    'branch'
                ])
                ->when($this->dateFrom, fn($q) => $q->whereDate('collection_date', '>=', $this->dateFrom))
                ->when($this->dateTo, fn($q) => $q->whereDate('collection_date', '<=', $this->dateTo))
                ->when($this->collectorId, fn($q) => $q->where('collector_id', $this->collectorId))
                ->when($this->status, fn($q) => $q->whereHas('staffCollectionStatus', fn($q) => $q->where('status', $this->status)))
                ->when($this->branchId, fn($q) => $q->whereHas('branch', fn($q) => $q->where('id', $this->branchId)))
                ->orderBy($this->sortBy, $this->sortDirection)
                ->get(),
            'totalPrincipal' => Collection::filteredSum($this->getFilters(), 'principal_amount'),
            'totalInterest' => Collection::filteredSum($this->getFilters(), 'interest_amount'),
            'totalCollections' => Collection::filteredSum($this->getFilters(), 'collected_amount'),
            'filters' => $this->getFilters()
        ];
    }

    
    public function render()
    {
        $collections = Collection::with([
                'loan.customer',
                'collector',
                'staffCollectionStatus',
                'branch'
            ])
            ->when($this->dateFrom, function ($query) {
                $query->whereDate('collection_date', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function ($query) {
                $query->whereDate('collection_date', '<=', $this->dateTo);
            })
            ->when($this->collectorId, function ($query) {
                $query->where('collector_id', $this->collectorId);
            })
            ->when($this->status, function ($query) {
                $query->whereHas('staffCollectionStatus', function ($q) {
                    $q->where('status', $this->status);
                });
            })
            ->when($this->branchId, fn($q) => $q->whereHas('loan.center.branch', fn($q) => $q->where('id', $this->branchId)))
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        $totalPrincipal = $collections->sum('principal_amount');
        $totalInterest = $collections->sum('interest_amount');
        $totalCollections = $collections->sum('collected_amount');

        return view('livewire.reports.collection-report', [
            'collections' => $collections,
            'collectors' => User::whereHas('collections')->get(),
            'branches' => Branch::all(),
            'totalPrincipal' => $totalPrincipal,
            'totalInterest' => $totalInterest,
            'totalCollections' => $totalCollections
        ]);
    }
}
