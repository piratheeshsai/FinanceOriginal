<?php

namespace App\Livewire\LoanDetails;

use App\Exports\CollectionsExport;
use App\Models\Collection;
use App\Models\CollectionInvoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Excel;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class AllCollections extends Component
{
    public $search = '';
    public $perPage = 200;
    public $from_date;
    public $to_date;
    public $loan_type;

    public $sortColumn = 'collection_date'; // Default column
    public $sortDirection = 'asc';
    use WithPagination;

    public function generateInvoice($collectionId, $type)
    {
        $invoice = CollectionInvoice::where('collection_id', $collectionId)
            ->where('type', $type)
            ->first();

        if (!$invoice) {
            // If no invoice exists, create one
            $collection = Collection::findOrFail($collectionId);
            $invoice = CollectionInvoice::create([
                'invoice_number' => 'INV-' . now()->year . str_pad(CollectionInvoice::count() + 1, 4, '0', STR_PAD_LEFT),
                'loan_id' => $collection->loan_id,
                'collection_id' => $collection->id,
                'collected_amount' => $collection->collected_amount,
                'type' => $type
            ]);
        }

        // Redirect to appropriate invoice method
        if ($type === 'pos') {
            return redirect()->route('invoice.print', ['id' => $invoice->id]);
        } elseif ($type === 'a4') {
            return redirect()->route('invoice.download', ['id' => $invoice->id]);
        }
    }

    public function exportExcel()
    {
        // We need to update the export to respect permissions as well
        return Excel::download(new CollectionsExport(
            $this->getBranchFilteredQuery()->get()
        ), 'collections.xlsx');
    }

    public function exportPDF()
    {
        // Use the same branch-filtered query for PDF export
        $collections = $this->getBranchFilteredQuery()->get();

        $pdf = Pdf::loadView('exports.allCollection', compact('collections'));
        return response()->streamDownload(fn() => print($pdf->output()), "collections.pdf");
    }

    public function updateDateFilter()
    {
        $this->resetPage(); // Reset pagination to the first page
    }

    public function updatedFromDate()
    {
        $this->resetPage(); // Reset pagination when 'from_date' is updated
    }

    public function updatedToDate()
    {
        $this->resetPage(); // Reset pagination when 'to_date' is updated
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    /**
     * Get base query with branch permissions applied
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function getBranchFilteredQuery()
    {
        $user = Auth::user();
        $query = Collection::query();

        // Apply branch permissions based on user role
        if ($user->hasPermissionTo('View All collections')) {

        } elseif ($user->hasPermissionTo('View branch collection only')) {

            $userBranchId = $user->branch_id; // Assuming user has a branch_id

            $query->whereHas('loan', function ($q) use ($userBranchId) {
                $q->whereHas('center', function ($q) use ($userBranchId) {
                    $q->where('branch_id', $userBranchId);
                });
            });
        } else {
            // Default: User can only see their own collections
            $query->where('collector_id', $user->id);
        }

        return $query;
    }

    // Update the date filter directly in the render method
    public function render()
    {
        // Get base query with branch permissions applied
        $query = $this->getBranchFilteredQuery();

        // Apply loan type filter if provided
        if ($this->loan_type) {
            $query->whereHas('loan', function ($q) {
                $q->where('loan_type', $this->loan_type);
            });
        }

        // Apply the search filter if provided
        if ($this->search) {
            $query->where(function ($q) {
                // Search by loan number
                $q->whereHas('loan', function ($q) {
                    $q->where('loan_number', 'like', '%' . $this->search . '%');
                })
                // Search by customer name
                ->orWhereHas('loan.customer', function ($q) {
                    $q->where('full_name', 'like', '%' . $this->search . '%');
                })
                // Search by collection amount
                ->orWhere('collected_amount', 'like', '%' . $this->search . '%')
                // Search by collector name
                ->orWhereHas('collector', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                });
            });
        }

        // Apply date range filter if provided
        if ($this->from_date) {
            $query->whereDate('collection_date', '>=', $this->from_date);
        }

        if ($this->to_date) {
            $query->whereDate('collection_date', '<=', $this->to_date);
        }

        // Simple order by collection date (descending to show latest first)
        $query->orderBy('collection_date', 'desc');

        // Get paginated results
        $collections = $query->paginate($this->perPage);

        return view('livewire.loan-details.all-collections', compact('collections'));
    }

    public function sortBy($column)
    {
        if ($this->sortColumn === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortColumn = $column;
            $this->sortDirection = 'asc';
        }
    }
}
