<?php

namespace App\Livewire\LoanDetails;

use App\Models\LoanCollectionSchedule;
use Livewire\Component;

class Schedule extends Component
{
    public $loanId;

    public function mount($loanId)
    {
        $this->loanId = $loanId;

    }
    public function render()
    {

        $schedules = LoanCollectionSchedule::where('loan_id', $this->loanId)
        ->orderBy('date', 'asc')
        ->get();
        

       $totalPaid = $schedules->where('description', 'Payment Received +')->sum('paid');
        $totalDue = $schedules->sum('total_due') - $totalPaid;

        return view('livewire.loan-details.schedule', [
            'schedules' => $schedules,
            'totalPaid' => $totalPaid,
            'totalDue' => $totalDue
        ]);
    }
}
