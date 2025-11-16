<?php

namespace App\Livewire\Branch;

use App\Models\Account;
use App\Models\Branch as ModelsBranch;
use App\Models\Center;
use App\Models\Transaction;
use Auth;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class Branch extends Component
{

    public $showAddFundsModal = false;
    public $amount;

    public $mainBankBalance;

    public $branchId = null;
    public $branchName = '';
    public $branchEmail = '';
    public $branchPhone = '';
    public $isEditing = false;
    public $selectedBranch = null;
    public $centers = [];

    public $initial_balance;
    public $centerId = null;
    public $centerName = '';
    public $centerBranchId = '';
    public $isCenterEditing = false;

    protected $rules = [
        'branchName' => 'required|string|max:255',
        'branchEmail' => 'required|email',
        'branchPhone' => 'required|string|max:15',
        'initial_balance' => 'required|numeric|min:0',

    ];



    public function store()
    {
        $this->validate();

        // Start a database transaction
        DB::beginTransaction();
        try {
            if ($this->isEditing) {
                // Update existing branch
                $branch = ModelsBranch::findOrFail($this->branchId);
                $branch->update([
                    'name' => $this->branchName,
                    'email' => $this->branchEmail,
                    'phone' => $this->branchPhone,
                ]);

                $message = 'Branch updated successfully.';
            } else {

                // Generate a new branch code
                $lastBranch = ModelsBranch::latest('id')->first();
                $lastBranchCode = $lastBranch ? (int) substr($lastBranch->branch_code, 1) : 0;
                $newBranchCode = str_pad($lastBranchCode + 1, 3, '0', STR_PAD_LEFT);

                // Create a new branch
                $branch = ModelsBranch::create([
                    'name' => $this->branchName,
                    'email' => $this->branchEmail,
                    'phone' => $this->branchPhone,
                    'user_id' => auth()->id(),
                    'branch_code' => $newBranchCode,
                ]);



                $branchBank = Account::create([
                    'branch_id' => $branch->id,
                    'account_number' => 'BANK-' . $branch->id,
                    'account_name' => 'Branch Bank - ' . $branch->name,
                    'balance' => 0.00,
                    'category' => 'asset',
                    'type' => 'bank',

                ]);

                $branchCapitalAccount = Account::create([
                    'branch_id' => $branch->id,
                    'account_number' => 'CAPITAL-BR-' . $branch->id,
                    'account_name' => 'Branch Capital - ' . $branch->name,
                    'balance' => 0.00,
                    'category' => 'equity',
                    'type' => 'branch_capital',

                ]);

                // Other asset accounts
                $accounts = [
                    ['CASH-DRAWER-', 'Cash Drawer', 'cash_drawer', 'asset'],
                    ['OWNER-DRAW-BR-', 'Owner’s Draw', 'owner_draw', 'equity'],
                    ['CASH-', 'Cash in Hand', 'cash', 'asset'],
                    ['LOAN-RECEIVABLE-', 'Loans Receivable', 'loan_receivable', 'asset'],
                    ['COLLECTION-CASH-', 'Collection Cash', 'collection_cash', 'asset'],
                    ['PETTY-CASH-', 'Petty Cash', 'petty_cash', 'asset'],
                    ['RETAINED-EARNINGS-', 'Retained Earnings', 'retained_earnings', 'equity'],
                    ['INTEREST-INCOME-', 'Interest Income', 'interest_income', 'revenue'],
                    ['LATE-FEES-', 'Late Fees', 'late_fee_income', 'revenue'],
                    ['SALARIES-', 'Salaries', 'salary_expense', 'expense'],
                    ['RENT-', 'Rent Expense', 'rent_expense', 'expense'],
                    ['UTILITIES-', 'Utilities', 'utilities_expense', 'expense'],
                    ['OFFICE-SUPPLIES-', 'Office Supplies', 'office_supplies_expense', 'expense'],
                    ['PETTY-CASH-EXPENSE-', 'Petty Cash Expenses', 'petty_cash_expenses', 'expense'],
                    ['OTHER-EXPENSE-', 'Other Expenses', 'other_expenses', 'expense'],
                    ['DOC-CHARGE-', 'Document Charges', 'document_charge_income', 'revenue'],




                ];

                foreach ($accounts as $acc) {
                    Account::create([
                        'branch_id' => $branch->id,
                        'account_number' => $acc[0] . $branch->id,
                        'account_name' => $acc[1] . ' - ' . $branch->name,
                        'balance' => 0.00,
                        'type' => $acc[2],
                        'category' => $acc[3],
                    ]);
                }


               $initialBalance = (float) $this->initial_balance;

                // Fetch capital and main bank accounts
                $capitalAccount = Account::where('account_number', 'CAPITAL-001')->first();
                $mainBankAccount = Account::where('account_number', 'BANK-MAIN')->first();


                // Check if the accounts exist and have enough balance
                if (!$capitalAccount || !$mainBankAccount) {
                    throw new \Exception("Required accounts not found.");
                }

                if ($initialBalance > $capitalAccount->balance) {
                    throw new \Exception("Insufficient capital balance for allocation.");
                }

                if ($initialBalance > $mainBankAccount->balance) {
                    throw new \Exception("Insufficient main bank balance for fund transfer.");
                }

                // If checks pass, continue with transactions
                Transaction::create([
                    'branch_id' => $branch->id,
                    'debit_account_id' => $branchBank->id,  // Branch Asset ↑
                    'credit_account_id' => $mainBankAccount->id,
                    'amount' => $initialBalance,
                    'description' => 'Funds transfer to branch',
                    'transaction_type' => 'fund_transfer',
                    'created_by' => auth()->id(),
                    'transaction_date' => now(),
                ]);

                Transaction::create([
                    'branch_id' => $branch->id,
                    'debit_account_id' => $capitalAccount->id,
                    'credit_account_id' => $branchCapitalAccount->id,
                    'amount' => $initialBalance,
                    'description' => 'Capital allocation recognition',
                    'transaction_type' => 'capital_allocation',
                    'created_by' => auth()->id(),
                    'transaction_date' => now(),
                ]);
            }
            $this->dispatch('hide-modal');
            $message = 'Branch and accounts created successfully.';
            DB::commit();
            $this->reset();

            $this->dispatch('show-success-alert', message: $message);
        } catch (Exception $e) {
            // Rollback the transaction if any error occurs
            DB::rollBack();

            $this->dispatch('show-error-alert', message: 'Error creating branch: ' . $e->getMessage());
        }
    }



    public function openAddFundsModal($branchId)
    {
        $this->branchId = $branchId;
        $this->mainBankBalance = Account::where('account_number', 'BANK-MAIN')
            ->value('balance');
        $this->resetValidation();
        $this->reset('amount');
        $this->showAddFundsModal = true;
    }

    protected $listeners = ['closeModal' => 'closeModal'];

    public function closeModal()
    {
        $this->showAddFundsModal = false;
        $this->dispatch('remove-backdrop'); // Add this line
    }






    public function addFundProcess()
    {
        $this->validate([
            'amount' => [
                'required',
                'numeric',
                'min:1',
                'max:' . $this->mainBankBalance,
            ],
        ], [
            'amount.required' => 'Please enter the transfer amount',
            'amount.numeric' => 'Amount must be a number',
            'amount.min' => 'Minimum transfer amount is 1',
            'amount.max' => 'Amount exceeds main bank balance',
        ]);

        try {
            DB::transaction(function () {
                // Get accounts with lock
                $mainBank = Account::where('account_number', 'BANK-MAIN')
                    ->where('type', 'bank')
                    ->lockForUpdate()
                    ->firstOrFail();

                $MainCapitalAccount = Account::where('account_number', 'CAPITAL-001')
                    ->where('type', 'capital')->lockForUpdate()
                    ->first();


                $branchBank = Account::where('branch_id', $this->branchId)
                    ->where('type', 'bank')
                    ->lockForUpdate()
                    ->firstOrFail();

                $BranchCapital = Account::where('branch_id', $this->branchId)
                    ->where('type', 'branch_capital')->lockForUpdate()
                    ->first();

                // Check balance again after lock
                if ($this->amount > $mainBank->balance) {
                    throw new \Exception('Insufficient funds in main bank account');
                }

                // Create transaction
                Transaction::create([
                    'branch_id' => $this->branchId,
                    'debit_account_id' => $MainCapitalAccount->id,    // Branch Asset ↑
                    'credit_account_id' => $BranchCapital->id,
                    'amount' => $this->amount,
                    'description' => 'Funds transfer to branch',
                    'transaction_type' => 'fund_transfer',
                    'created_by' => auth()->id(),
                    'transaction_date' => now(),
                ]);

                Transaction::create([
                    'branch_id' => $this->branchId,
                    'debit_account_id' => $branchBank->id,
                    'credit_account_id' => $mainBank->id,
                    'amount' => $this->amount,
                    'description' => 'Branch add Funds',
                    'transaction_type' => 'fund_transfer',
                    'created_by' => auth()->id(),
                    'transaction_date' => now(),
                ]);



            });

            $this->closeModal();
            $this->dispatch('show-success-alert', message: 'Funds transferred successfully!');
        } catch (\Exception $e) {
            $this->dispatch('show-error-alert', message: $e->getMessage());
        }
    }













    public function fetchCenters($branchId)
    {
        $this->selectedBranch = ModelsBranch::findOrFail($branchId);
        $this->centers = Center::where('branch_id', $branchId)->get();
    }

    public function openCreateCenterModal()
    {
        $this->resetCenterForm();
        $this->dispatch('show-create-center-modal');
    }
    public function resetCenterForm()
    {
        $this->centerId = null;
        $this->centerName = '';
        $this->centerBranchId = '';
        $this->isCenterEditing = false;
    }
    public function openEditCenterModal($centerId)
    {
        $center = Center::findOrFail($centerId);
        $this->centerId = $center->id;
        $this->centerName = $center->name;
        $this->centerBranchId = $center->branch_id;
        $this->isCenterEditing = true;
        $this->dispatch('show-create-center-modal');
    }






    public function storeCenter()
    {
        $this->validate([
            'centerName' => 'required|string|max:255',
            'centerBranchId' => 'required|exists:branches,id',
        ]);

        DB::beginTransaction();
        try {
            if ($this->isCenterEditing) {
                // Update existing center
                $center = Center::findOrFail($this->centerId);
                $center->update([
                    'name' => $this->centerName,
                    'branch_id' => $this->centerBranchId,
                ]);
                $message = 'Center updated successfully.';
            } else {
                // Create new center
                $latestCenter = Center::where('branch_id', $this->centerBranchId)
                    ->latest('center_code')
                    ->first();

                $newCenterCode = $latestCenter
                    ? str_pad((intval($latestCenter->center_code) + 1), 3, '0', STR_PAD_LEFT)
                    : '001';

                Center::create([
                    'name' => $this->centerName,
                    'center_code' => $newCenterCode,
                    'branch_id' => $this->centerBranchId,
                ]);
                $message = 'Center created successfully.';
            }

            // Commit transaction for both cases
            DB::commit();
            $this->fetchCenters($this->centerBranchId);
            // Reset form and close modal
            $this->resetCenterForm();
            $this->dispatch('hide-create-center-modal');
            $this->dispatch('show-success-alert', message: $message);
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatch('show-error-alert', message: 'Error: ' . $e->getMessage());
        }
    }

    #[On('delete-center')] // Add this attribute above the deleteCenter method
    public function deleteCenter($centerId)
    {
        DB::beginTransaction();
        try {
            $center = Center::findOrFail($centerId);
            $center->delete();

            // Refresh the list of centers (ensure $selectedBranch is not null)
            if ($this->selectedBranch) {
                $this->fetchCenters($this->selectedBranch->id);
            }

            DB::commit();

            $this->dispatch('show-success-alert', message: 'Center deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('show-error-alert', message: 'Error deleting center: ' . $e->getMessage());
        }
    }

    // Add this attribute above the deleteCenter method

    #[On('deleteBranch')]
    public function deleteBranch($branchId)
    {
        DB::beginTransaction();
        try {
            // Find the branch
            $branch = ModelsBranch::findOrFail($branchId);

            // Delete all transactions related to this branch
            $transactions = Transaction::where('branch_id', $branchId)->get();
            foreach ($transactions as $transaction) {
                $transaction->delete(); // This will trigger the observer
            }


            // Delete all accounts related to this branch

            Account::where('branch_id', $branchId)->delete();

            // Now delete the branch
            $branch->delete();

            DB::commit();

            // Show success message

            $this->dispatch('show-success-alert', message: 'Branch deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            // Show error message
            $this->dispatch('show-error-alert', message: 'Error deleting branch: ' . $e->getMessage());
        }
    }






    public function mount($branch = null)
    {
        if ($branch) {
            $this->branchId = $branch->id;
            $this->branchName = $branch->name;
            $this->branchEmail = $branch->email;
            $this->branchPhone = $branch->phone;
            $this->isEditing = true;
        } else {
            $this->reset();
        }
    }


    public function openEditModal($branchId)
    {
        $branch = ModelsBranch::findOrFail($branchId);
        $this->mount($branch);
        $this->dispatch('show-branch-modal');
    }

    public function openCreateBranchModal()
    {
        $this->reset();
        $this->dispatch('show-branch-modal');
    }


    public function render()
    {

        $branches = ModelsBranch::with('creator')->get();;

        return view('livewire.branch.branch',  [
            'branches' => $branches,
            'selectedBranch' => $this->selectedBranch,
            'centers' => $this->centers
        ]);
    }
}
