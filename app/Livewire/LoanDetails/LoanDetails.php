<?php

namespace App\Livewire\LoanDetails;

use Livewire\Component;
use Log;

class LoanDetails extends Component
{


    public $loanId;

    public $currentComponent = 'collect-due';




    public function mount($loanId) { $this->loanId = $loanId; }
    public function render()
    {
        return view('livewire.loan-details.loan-details');
    }
}
