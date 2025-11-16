<?php

namespace App\Livewire\Documents;

use App\Models\Loan;
use Livewire\Component;
use Livewire\WithPagination;

class Voucher extends Component
{



 use WithPagination;

    public $perPage = 10;
    public $search = '';
    public $dateFilter = '';


 public function mount()
    {
        $this->dateFilter = now()->toDateString();
    }






    public function render()
    {
        return view('livewire.documents.voucher',[
            'loans' => Loan::query()
                ->when($this->dateFilter, function ($query) {
                    $query->whereDate('loan_date', $this->dateFilter);
                })
                ->when($this->search, function ($query) {
                    $query->where(function ($q) {
                        $q->where('loan_number', 'like', '%'.$this->search.'%')
                        ->orWhereHas('customer', function ($subQuery) {
                            $subQuery->where('full_name', 'like', '%'.$this->search.'%')
                            ->orWhere('nic', 'like', '%'.$this->search.'%');
                        });
                    });
                })
                ->with('customer', 'approval', 'center.branch')
                ->paginate($this->perPage)
        ]);
    }

     public function Receipt($loanId)
    {
        $loan = Loan::findOrFail($loanId);

        // Dispatch browser event to trigger download
        $this->dispatch('download-pdf', [
            'url' => route('loan.Receipt.export', $loan->id)
        ]);

        $this->dispatch('show-alert', [
        'type' => 'success',
        'title' => 'Export Started',
        'message' => "Exporting loan agreement..."
        ]);
    }


      public function ReceiptBulk()
    {

        $loansCount = Loan::query()
            ->when($this->dateFilter, function ($query) {
                $query->whereDate('loan_date', $this->dateFilter);
            })
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('loan_number', 'like', '%'.$this->search.'%')
                    ->orWhereHas('customer', function ($subQuery) {
                        $subQuery->where('full_name', 'like', '%'.$this->search.'%')
                        ->orWhere('nic', 'like', '%'.$this->search.'%');
                    });
                });
            })
            ->count();

        if ($loansCount === 0) {
            session()->flash('error', 'No loans found for the selected filters.');
            return;
        }

        // Dispatch browser event to trigger bulk download
        $this->dispatch('download-bulk-pdf', [
            'url' => route('loan.Receipt.bulk-export'),
            'dateFilter' => $this->dateFilter,
            'search' => $this->search
        ]);

        $this->dispatch('show-alert', [
        'type' => 'success',
        'title' => 'Export Started',
        'message' => "Exporting {$loansCount} loan agreements..."
    ]);
    }

    public function updated($property)
    {
        if (in_array($property, ['search', 'dateFilter', 'perPage'])) {
            $this->resetPage();
        }
    }
}
