<?php

namespace App\Livewire;

use Livewire\Component;

class PaymentTable extends Component
{



    // Listen for the event from the SchemeList component
    public $loanAmount;
    public $paymentDetails = [];
    public $selectedRow;

    // Method to calculate payments
    public function calculatePayments($rowId)
    {
        $this->selectedRow = $rowId;

        // Replace this with your actual calculation logic
        $this->paymentDetails[$rowId] = $this->generatePaymentDetails($this->loanAmount);

        // Reset loan amount after calculation
        $this->loanAmount = '';
    }

    private function generatePaymentDetails($loanAmount)
    {
        // Simple calculation example
        return [
            'dueAmount' => $loanAmount,
            'interestAmount' => $loanAmount * 0.05, // Example 5% interest
            'totalAmount' => $loanAmount * 1.05
        ];
    }
    public function render()
    {
        return view('livewire.payment-table');
    }
}
