<?php

namespace App\Livewire;

use App\Models\Center;
use App\Models\Customer;
use App\Models\Type; // Changed from CustomerType to Type
use Livewire\Component;
use Livewire\WithPagination;
use Auth;

class CustomerTable extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $search = '';
    public $selectedCenter = '';
    public $selectedType = ''; // For filtering by customer type

    protected $queryString = [
        'search' => ['except' => ''],
        'selectedCenter' => ['except' => ''],
        'selectedType' => ['except' => ''],
        'perPage' => ['except' => 10],
    ];

    // Reset page when filters change
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSelectedCenter()
    {
        $this->resetPage();
    }

    public function updatedSelectedType()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Customer::query()->with(['types', 'loans.approval', 'center']);


        if (!auth()->user()->can('View All Customers')) {
            $userBranchId = auth()->user()->branch_id;


            $query->whereHas('center', function($q) use ($userBranchId) {
                $q->where('branch_id', $userBranchId);
            });
        }


        if (!empty($this->selectedCenter)) {
            $query->where('center_id', $this->selectedCenter);
        }


        if (!empty($this->selectedType)) {
            $query->whereHas('types', function($q) {
                $q->where('type_id', $this->selectedType);
            });
        }

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('full_name', 'like', '%' . $this->search . '%')
                  ->orWhere('nic', 'like', '%' . $this->search . '%')
                  ->orWhere('customer_no', 'like', '%' . $this->search . '%')
                  ->orWhere('customer_phone', 'like', '%' . $this->search . '%');
            });
        }

        $customers = $query->orderBy('created_at', 'desc')
                        ->paginate($this->perPage);

        // For center dropdown, only show centers from user's branch if they don't have 'view all branch' permission
        $centersQuery = Center::query();
        if (!auth()->user()->can('View All Customers')) {
            $userBranchId = auth()->user()->branch_id;
            $centersQuery->where('branch_id', $userBranchId);
        }
        $centers = $centersQuery->get();

        return view('livewire.customer-table', [
            'customers' => $customers,
            'centers' => $centers,
            'types' => Type::all(), 
        ]);
    }
}
