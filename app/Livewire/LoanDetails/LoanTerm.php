<?php

namespace App\Livewire\LoanDetails;

use App\Models\LoanProgress;
use Livewire\Component;

class LoanTerm extends Component
{
    public $loanId;

    public function mount($loanId)
    {
        $this->loanId = $loanId;

    }
    public function render()
    {



       
        $loanProgress = LoanProgress::where('loan_id', $this->loanId)->first();
        return view('livewire.loan-details.loan-term',[ 'loanProgress' => $loanProgress]);
    }
}
