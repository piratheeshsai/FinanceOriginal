<?php

namespace App\Livewire;

use App\Models\Customer;
use Livewire\Component;
use Livewire\WithPagination;

class CustomerList extends Component
{

    use WithPagination;

    public $perPage = 6; // Default entries per page

    public function render()
    {
        $customers = Customer::with(['types', 'loans.approval']) // Eager load types, loans, and loan approvals
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);
        return view('livewire.customer-list', compact('customers'));
    }

    // Reset pagination when perPage changes
    public function updatedPerPage()
    {
        $this->resetPage(); // Reset to the first page
    }
}


