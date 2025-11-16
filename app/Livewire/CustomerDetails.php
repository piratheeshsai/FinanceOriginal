<?php

namespace App\Livewire;

use App\Models\Customer;
use Livewire\Component;

class CustomerDetails extends Component
{


    public $customerId;

    public function mount($customerId)
    {
        $this->customerId = $customerId;
    }
    public function render()
    {

        $customer = Customer::findOrFail($this->customerId);

        return view('livewire.customer-details', compact('customer'));

    }
}
