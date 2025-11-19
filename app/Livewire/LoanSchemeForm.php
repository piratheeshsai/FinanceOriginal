<?php

namespace App\Livewire;

use App\Models\LoanScheme;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class LoanSchemeForm extends Component
{
    public $loan_name, $loan_type, $interest_rate, $collecting_duration, $loan_term, $document_charge_percentage;

    // Validation rules
    protected $rules = [
        'loan_name' => 'required|string|max:255',
        'loan_type' => 'required|in:group,individual',
        'interest_rate' => 'required|numeric|min:0',
        'collecting_duration' => 'required|in:daily,weekly,monthly',
        'loan_term' => 'required|integer|min:1',
        'document_charge_percentage' => 'nullable|numeric|min:0|max:100',
    ];

    // Store the loan scheme
    public function save()
    {
        $this->validate();

        LoanScheme::create([
            'loan_name' => $this->loan_name,
            'loan_type' => $this->loan_type,
            'interest_rate' => $this->interest_rate,
            'collecting_duration' => $this->collecting_duration,
            'loan_term' => $this->loan_term,
            'document_charge_percentage' => $this->document_charge_percentage,
        ]);

        session()->flash('success', 'Loan Scheme Created Successfully.');



        return redirect('/loan-schemes');




    }

    public function render()
    {
        return view('livewire.loan-scheme-form');
    }
}
