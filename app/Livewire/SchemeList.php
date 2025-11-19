<?php

namespace App\Livewire;

use App\Exports\PaymentExport;
use App\Models\Loan;
use App\Models\LoanScheme;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class SchemeList extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $selectedSchemeId;    // To store selected scheme
    public $amount;              // User input loan amount
    public $payments = [];
    public $editSchemeId;
    public $editLoanName;
    public $editLoanType;
    public $editInterestRate;
    public $editRepaymentDuration;
    public $editLoanTerm;
    public $editDocumentChargePercentage;  // Added property for document charge percentage
    // Results to display calculated values
    protected $listeners = ['deleteConf' => 'deleteLoanSchemes'];



    public function openEditModal($schemeId)
    {
        $scheme = LoanScheme::find($schemeId);

        if ($scheme) {
            $this->editLoanName = $scheme->loan_name;
            $this->editLoanType = $scheme->loan_type;
            $this->editInterestRate = $scheme->interest_rate;
            $this->editRepaymentDuration = $scheme->collecting_duration;
            $this->editLoanTerm = $scheme->loan_term;
            $this->editDocumentChargePercentage = $scheme->document_charge_percentage; // <-- Add this line
            $this->editSchemeId = $scheme->id;

            $this->dispatch('show-edit-modal'); // Trigger the edit modal display
        }
    }
    public function updateLoanScheme()
    {
        $this->validate([
            'editLoanName' => 'required|string|max:255',
            'editLoanType' => 'required|string|max:255',
            'editInterestRate' => 'required|numeric|min:0|max:100',
            'editRepaymentDuration' => 'required|string|max:255',
            'editLoanTerm' => 'required|max:255',
            'editDocumentChargePercentage' => 'nullable|numeric|min:0|max:100', // Added validation
        ]);

        $scheme = LoanScheme::find($this->editSchemeId);

        if ($scheme) {
            $scheme->update([
                'loan_name' => $this->editLoanName,
                'loan_type' => $this->editLoanType,
                'interest_rate' => $this->editInterestRate,
                'collecting_duration' => $this->editRepaymentDuration,
                'loan_term' => $this->editLoanTerm,
                'document_charge_percentage' => $this->editDocumentChargePercentage, // Added update
            ]);

            $this->dispatch('hide-edit-modal');
            $this->dispatch('SchemeUpdated');
        }
    }



    public function openPaymentModal($schemeId)
    {
        $this->selectedSchemeId = $schemeId;
        $this->amount = null;   // Reset amount
        $this->payments = [];    // Reset results
        $this->dispatch('showModal'); // Trigger modal open event
    }



    public function calculateDue()
    {
        $scheme = LoanScheme::find($this->selectedSchemeId);

        if ($scheme && $this->amount) {
            $term = $scheme->loan_term;
            $interestRate = ($scheme->interest_rate * $term) / 100;

            $dueAmount = $this->amount / $term;
            $dueInterest = ($this->amount * $interestRate) / $term;
            $dueTotal = $dueAmount + $dueInterest;

            $this->payments = collect(range(1, $term))->map(function ($i) use ($dueAmount, $dueInterest, $dueTotal) {
                return [
                    'due' => $dueAmount,
                    'interest' => $dueInterest,
                    'total' => $dueTotal,
                ];
            })->toArray();
        }
    }

    public function exportExcel()
    {
        return Excel::download(new PaymentExport($this->payments), 'payments.xlsx');
    }

    public function exportCsv()
    {
        // Make sure payments are calculated before export
        if (empty($this->payments)) {
            $this->calculateDue();
        }

        return Excel::download(new PaymentExport($this->payments), 'payments.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    public function exportPDF()
    {
        $pdf = Pdf::loadView('exports.payment-pdf', ['payments' => $this->payments]);
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'payments.pdf');
    }


    public $delete_id;



    public function deleteConformation($id)
    {

        $this->delete_id = $id;
        $this->dispatch('show-delete-conformation');
    }

    public function deleteLoanSchemes()
    {
        // First check if there are any loans using this scheme
        $hasLoans = Loan::where('scheme_id', $this->delete_id)->exists();

        if ($hasLoans) {
            // If loans exist, show error alert
            $this->dispatch(
                'showAlert',
                type: 'error',
                message: 'Cannot delete scheme. There are existing loans associated with it.'
            );
            return;
        }

        // Proceed with deletion if no loans exist
        $loanSchemes = LoanScheme::find($this->delete_id);

        if ($loanSchemes) {
            $loanSchemes->delete();
            $this->dispatch('LoanSchemeDeleted');
        }
    }

    public function render()
    {
        $loanSchemes = LoanScheme::orderBy('created_at', 'desc')->paginate($this->perPage);
        return view('livewire.scheme-list', compact('loanSchemes'));
    }
}
