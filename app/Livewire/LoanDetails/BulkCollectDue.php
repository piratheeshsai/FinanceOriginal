<?php

namespace App\Livewire\LoanDetails;

use App\Models\Collection;
use App\Models\Loan;
use App\Models\LoanCollectionSchedule;
use App\Models\LoanProgress;
use Carbon\Carbon;
use DB;
use Livewire\Component;
use Log;

class BulkCollectDue extends Component
{



    public $collections = [];
    protected $maxRows = 15;

    // Modify rules to only validate filled rows
    protected function rules()
    {
        return [
            'collections.*.loan_id' => 'required_with:collections.*.amount|exists:loan,id',
            'collections.*.amount' => 'required_with:collections.*.loan_id|numeric|min:1',
            'collections.*.method' => 'required_with:collections.*.loan_id|string|in:Cash,ATM',
            'collections.*.collection_date' => 'required_with:collections.*.loan_id|date',
            'collections.*.collected_by' => 'required_with:collections.*.loan_id|string',
            'collections.*.description' => 'nullable|string',
        ];
    }

    public function mount()
    {
        // Initialize empty rows
        for ($i = 0; $i < $this->maxRows; $i++) {
            $this->collections[] = [
                'loan_id' => '',
                'amount' => '',
                'method' => $i === 0 ? 'ATM' : 'Cash',
                'collection_date' => now()->format('Y-m-d'),
                'collected_by' => auth()->user()->name,
                'description' => ''
            ];
        }
    }

    public function submit()
    {
        $filledCollections = [];

        foreach ($this->collections as $row) {
            // Ensure loan_id is an integer or null, and amount is a float or zero
            $loanId = is_array($row['loan_id']) ? ($row['loan_id']['value'] ?? null) : $row['loan_id'];
            $amount = (float) ($row['amount'] ?? 0); // Convert to float to ensure numeric value

            // Add row to filledCollections if it has valid data
            if (!empty($loanId) && $amount > 0) {
                $filledCollections[] = [
                    'loan_id' => $loanId,   // Ensure it's not empty
                    'amount' => $amount,     // Ensure it's a valid numeric value
                    'method' => $row['method'] ?? null,
                    'collection_date' => $row['collection_date'] ?? null,
                    'collected_by' => $row['collected_by'] ?? null,
                    'description' => $row['description'] ?? null,
                ];
            }
        }

        // Check if filledCollections is empty after filtering
        if (empty($filledCollections)) {
            session()->flash('error', 'Please fill at least one row');
            return;
        }

        // Validate form data
        $this->validate();

        try {
            // Start a database transaction to ensure all entries are inserted or none
            DB::transaction(function () use ($filledCollections) {
                foreach ($filledCollections as $collection) {
                    // Insert collection data into the 'collections' table
                    Collection::create([
                        'loan_id' => $collection['loan_id'],
                        'collector' => auth()->id(),
                        'collected_amount' => $collection['amount'],
                        'collection_date' => $collection['collection_date'],
                        'collection_method' => $collection['method'],
                        'notes' => $collection['description']
                    ]);

                    // Create Payment Received+ entry for each collection
                    LoanCollectionSchedule::create([
                        'loan_id' => $collection['loan_id'],
                        'date' => Carbon::parse($collection['collection_date']),
                        'description' => 'Payment Received +',
                        'principal' => 0,
                        'interest' => 0,
                        'penalty' => 0,
                        'due' => 0,
                        'paid' => $collection['amount'],
                        'pending_due' => null,
                        'total_due' => 0,
                        'principal_due' => null,
                        'status' => 'paid',
                    ]);

                    // Update the schedule pending dues and loan progress
                    $this->updateSchedulePendingDues($collection['loan_id']);
                    $this->updateLoanProgress($collection['loan_id']);
                }
            });

            // Success message and reset the form
            session()->flash('success', 'Payments recorded successfully!');
            $this->mount(); // Reset the form

        } catch (\Exception $e) {
            // Log the error and show an error message to the user
            Log::error('Bulk collection error: ' . $e->getMessage());
            session()->flash('error', 'Error recording payments: ' . $e->getMessage());
        }
    }


    private function updateSchedulePendingDues($loanId)
    {
        $allRecords = LoanCollectionSchedule::where('loan_id', $loanId)
            ->orderBy('date', 'asc')
            ->get();

        $runningBalance = 0;

        foreach ($allRecords as $record) {
            if ($record->description === 'Payment Received +') {
                $runningBalance -= $record->paid;
            } else {
                $runningBalance += $record->due;
                $record->pending_due = $runningBalance;

                if ($runningBalance <= 0) {
                    $record->status = 'paid';
                } elseif ($record->date < now() && $runningBalance > 0) {
                    $record->status = 'Arrears';
                } else {
                    $record->status = 'pending';
                }

                $record->total_due = $record->due;
                $record->save();
            }
        }

        if ($runningBalance <= 0) {
            $loanProgress = LoanProgress::where('loan_id', $loanId)->first();
            if ($loanProgress) {
                $loanProgress->status = 'completed';
                $loanProgress->save();
            }
        }
    }

    private function updateLoanProgress($loanId)
    {
        $schedules = LoanCollectionSchedule::where('loan_id', $loanId)->get();
        $totalPaid = $schedules->where('description', 'Payment Received +')->sum('paid');
        $totalDue = $schedules->where('description', 'Repayment')->sum('due');

        $loanProgress = LoanProgress::where('loan_id', $loanId)->first();

        if ($loanProgress) {
            $loanProgress->total_paid_amount = $totalPaid;
            $loanProgress->balance = $loanProgress->total_amount - $totalPaid;
            $loanProgress->status = $loanProgress->balance <= 0 ? 'Complete' : 'Active';

            if ($loanProgress->status === 'Complete') {
                $loanProgress->last_due_date = now();
            }

            $loanProgress->save();
        }
    }

    public function setDefault($index, $field)
    {
        // Handle setting default values for specific fields
        switch ($field) {
            case 'method':
                $this->collections[$index]['method'] = $index === 0 ? 'ATM' : 'Cash';
                break;
            case 'collection_date':
                $this->collections[$index]['collection_date'] = now()->format('Y-m-d');
                break;
            case 'collected_by':
                $this->collections[$index]['collected_by'] = auth()->user()->name;
                break;
            default:
                $this->collections[$index][$field] = '';
                break;
        }
    }



    public function getTotalAmountProperty()
    {
        return array_reduce($this->collections, function ($total, $collection) {
            return $total + (float)($collection['amount'] ?? 0);
        }, 0);
    }


    public function render()
{
    return view('livewire.loan-details.bulk-collect-due', [
        'loans' => Loan::with('customer')->select('id', 'customer_id')->get()
            ->map(function($loan) {
                return [
                    'id' => $loan->id,
                    'borrower_name' => $loan->customer->full_name,
                    'nic' => $loan->customer->nic
                ];
            })
    ]);
}
}
