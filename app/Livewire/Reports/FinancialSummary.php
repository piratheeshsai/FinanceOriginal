<?php

namespace App\Livewire\Reports;

use App\Models\Transaction;
use Livewire\Component;

class FinancialSummary extends Component
{
    public $startDate, $endDate;

    public function mount()
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->endOfMonth()->format('Y-m-d');
    }

    public function render()
    {
        $data = Transaction::whereBetween('created_at', [$this->startDate, $this->endDate])->get();
        return view('livewire.reports.financial-summary', compact('data'));
    }

}
