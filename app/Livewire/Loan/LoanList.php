<?php

namespace App\Livewire\Loan;

use App\Models\Loan;
use App\Models\Center; // Add this import
use Livewire\Component;
use Livewire\WithPagination;

class LoanList extends Component
{
    public $search = '';
    public $centerId = ''; // Add center filter property
    use WithPagination;
    public $errorMessage;
    public $perPage = 50;
    public $delete_id;

    protected $listeners = ['deleteloan' => 'deleteLoan'];

    // Reset pagination when centerId changes
    public function updatedCenterId()
    {
        $this->resetPage();
    }

    // Reset pagination when search changes
    public function updatedSearch()
    {
        $this->resetPage();
    }

    // Reset pagination when perPage changes
    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function LoanDeleteConformation($id)
    {
        $this->delete_id = $id;
        $this->dispatch('show-loan-delete');
    }

    public function deleteLoan()
    {
        $loan = Loan::where('id', $this->delete_id)->first();
        if (!$loan) {
            return;
        }

        // Check if the loan status is 'active'
        if ($loan->approval->status == 'Active') {
            // If the loan is active, prevent deletion and emit an error message
            $this->dispatch('LoanDeletionFailed', ['message' => 'Cannot delete an active loan.']);
            return;
        }

        $loan->delete();
        $this->dispatch('LoanDeleted');
    }

    public function render()
    {
        $query = Loan::with(['customer', 'approval', 'loanProgress', 'center.branch']);

        if (!auth()->user()->hasPermissionTo('View All Branch Loans')) {
            // If not, only show loans from user's branch
            $userBranchId = auth()->user()->branch_id;
            $query->whereHas('center', function($query) use ($userBranchId) {
                $query->where('branch_id', $userBranchId);
            });
        }

        // Apply center filtering
        if ($this->centerId) {
            $query->where('center_id', $this->centerId);
        }

        // Apply search filtering
        $loans = $query->when($this->search, function($query) {
            $query->where(function($query) {
                $query->where('loan_number', 'like', '%' . $this->search . '%')
                    ->orWhereHas('customer', function($query) {
                        $query->where('full_name', 'like', '%' . $this->search . '%')
                            ->orWhere('nic', 'like', '%' . $this->search . '%');
                    });
            });
        })
        ->paginate($this->perPage);

        // Get centers for the dropdown
        $centersQuery = Center::query();

        // If user doesn't have permission to view all branches, filter centers by user's branch
        if (!auth()->user()->hasPermissionTo('View All Branch Loans')) {
            $centersQuery->where('branch_id', auth()->user()->branch_id);
        }

        $centers = $centersQuery->get();

        return view('livewire.loan.loan-list', compact('loans', 'centers'));
    }
}

