<?php

namespace App\Livewire\Settings;

use App\Models\Account;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Transaction;
use File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Log;

class CompanySettings extends Component
{


    use WithFileUploads;

    public $company;
    public $logo;
    public $name;
    public $email;
    public $phone;
    public $address;
    public $website;
    public $registration_no;
    public $capital_balance,$bankBalance,$ownerDrawBalance;

    // Withdrawal modal properties
    public $showWithdrawalModal = false;
    public $showOpenAddFundsModal = false;
    public $withdrawal_amount;
    public $withdrawal_description;
    public $amount;

    public $branches = [];
    public $branch_id; // For the selected branch in the modal

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'required|string|max:20',
        'address' => 'required|string|max:255',
        'website' => 'nullable|url|max:255',
        'registration_no' => 'nullable|string|max:50',
        'logo' => 'nullable|image|max:1024', // 1MB Max
    ];

    protected $messages = [
        'name.required' => 'Company name is required',
        'email.required' => 'Email address is required',
        'email.email' => 'Please enter a valid email address',
        'phone.required' => 'Phone number is required',
        'address.required' => 'Company address is required',
        'website.url' => 'Website must be a valid URL',
        'logo.image' => 'Logo must be an image file',
        'logo.max' => 'Logo must not exceed 1MB',
    ];
    public function mount()
    {
        $this->company = Company::first();
        $this->loadCompanyData();

        // Load all branches for dropdown
        $this->branches = Branch::orderBy('name')->get();

        // Get capital balance from the capital account
        $capitalAccount = Account::where('account_number', 'CAPITAL-001')
                                 ->where('type', 'capital')
                                 ->first();

        if ($capitalAccount) {
            $this->capital_balance = $capitalAccount->balance;
        }

        // Get bank account balance
        $bankAccount = Account::where('account_number', 'BANK-MAIN')
                            ->where('type', 'bank')
                            ->first();

        if ($bankAccount) {
            $this->bankBalance = $bankAccount->balance;
        }

        // Get owner draw account balance
        $ownerDrawAccount = Account::where('account_number', 'OWNER-DRAW-company')
                                   ->where('type', 'owner_draw')
                                   ->first();

        if ($ownerDrawAccount) {
            $this->ownerDrawBalance = $ownerDrawAccount->balance;
        }
    }


    private function loadCompanyData()
    {
        if ($this->company) {
            $this->name = $this->company->name;
            $this->email = $this->company->email;
            $this->phone = $this->company->phone;
            $this->address = $this->company->address;
            $this->website = $this->company->website;
            $this->registration_no = $this->company->registration_no;
            $this->capital_balance = $this->company->capital_balance;
        }
    }



    public function render()
    {



        return view('livewire.settings.company-settings');
    }





    public function updateCompany()
    {
        $this->validate();

        try {
            if ($this->company) {
                // Update existing company
                $this->company->update([
                    'name' => $this->name,
                    'email' => $this->email,
                    'phone' => $this->phone,
                    'address' => $this->address,
                    'website' => $this->website,
                    'registration_no' => $this->registration_no,
                ]);

                // Handle logo upload
                if ($this->logo) {
                    // Process logo upload
                    $filename = time() . '_' . $this->logo->getClientOriginalName();
                    $this->logo->storeAs('public/logos', $filename);
                    $this->company->update(['logo' => $filename]);
                }

                session()->flash('success', 'Company details updated successfully');
            } else {
                // Create new company
                $company = Company::create([
                    'name' => $this->name,
                    'email' => $this->email,
                    'phone' => $this->phone,
                    'address' => $this->address,
                    'website' => $this->website,
                    'registration_no' => $this->registration_no,
                    'capital_balance' => $this->capital_balance ?? 0,
                ]);

                if ($this->logo) {
                    $filename = time() . '_' . $this->logo->getClientOriginalName();
                    $this->logo->storeAs('public/logos', $filename);
                    $company->update(['logo' => $filename]);
                }

                $this->company = $company;

                $this->dispatch('show-success-alert', message: 'Company created successfully.');
            }

            // Add debug
            session()->flash('debug', 'Method called successfully');
        } catch (\Exception $e) {

            $this->dispatch('show-error-alert', message: $e->getMessage());
        }
    }




    public function openWithdrawalModal()
    {
        $this->showWithdrawalModal = true;
        $this->dispatch('showWithdrawalModal');
    }

    public function openAddFundsModal()
    {
        $this->showOpenAddFundsModal = true;
        $this->dispatch('showOpenAddFundsModal');
    }



    public function closeAddFundsModal()
    {
        $this->showOpenAddFundsModal = false;
        $this->resetWithdrawalFields();
    }


    public function closeWithdrawalModal()
    {
        $this->showOpenAddFundsModal = false;
        $this->resetOpenAddFunds();
    }


    private function resetWithdrawalFields()
    {
        $this->withdrawal_amount = null;
        $this->withdrawal_description = null;
    }

    private function resetOpenAddFunds()
    {
        $this->amount = null;

    }

    public function AddFundProcess()
    {
        // Validate the withdrawal input
        $this->validate([
            'amount' => 'required|numeric|min:1',

        ], [
            'amount.required' => 'Please enter a withdrawal amount',
            'amount.numeric' => 'Amount must be a number',
            'amount.min' => 'Amount must be at least 1',
            'amount.max' => 'Amount cannot exceed 15 digits',

        ]);

        try {

            $capitalAccount = Account::where('account_number', 'CAPITAL-001')
                ->where('type', 'capital')
                ->first();


            $mainBankAccount = Account::where('account_number', 'BANK-MAIN')
                ->where('type', 'bank')
                ->first();


            Transaction::create([
                'branch_id' => null,
                'debit_account_id' =>$mainBankAccount->id,
                'credit_account_id' => $capitalAccount->id,
                'amount' => $this->amount,
                'description' => 'Fund_Added',
                'transaction_date' => now(),
                'created_by' => Auth::id(),
                'transaction_type' => 'Fund Added'
            ]);




            $this->resetOpenAddFunds();

            // Close the modal
            $this->dispatch('closeAddFundsModal');

            $this->dispatch('show-success-alert', message: 'Fund Added successfully.');

        } catch (\Exception $e) {
            // Dispatch error alert on failure
            $this->dispatch('show-error-alert', message: $e->getMessage());
            return false;
        }
    }



    public function processWithdrawal()
    {
        // Validate the withdrawal input
        $this->validate([
            'withdrawal_amount' => 'required|numeric|min:1',
            'withdrawal_description' => 'required|string|max:255',
            'branch_id' => 'required|exists:branches,id',
        ], [
            'withdrawal_amount.required' => 'Please enter a withdrawal amount',
            'withdrawal_amount.numeric' => 'Amount must be a number',
            'withdrawal_amount.min' => 'Amount must be at least 1',
            'withdrawal_description.required' => 'Please provide a description for this withdrawal',
            'branch_id.required' => 'Please select a branch',
            'branch_id.exists' => 'Selected branch does not exist',
        ]);

        try {
            // Fetch branch bank account
            $branchBankAssetAccount = Account::where('branch_id', $this->branch_id)
                ->where('type', 'bank')
                ->first();

            $branchBankCapitalEquityAccount = Account::where('branch_id', $this->branch_id)
                ->where('type', 'branch_capital')
                ->first();

            // Fetch branch owner draw account
            $branchOwnerDrawEquityAccount = Account::where('branch_id', $this->branch_id)
                ->where('type', 'owner_draw')
                ->first();

             $branchCashDrawerAssetAccount = Account::where('branch_id', $this->branch_id)
                ->where('type', 'cash_drawer')
                ->first();

            // Check if the required accounts exist
            if (!$branchBankAssetAccount || !$branchOwnerDrawEquityAccount || !$branchBankCapitalEquityAccount || !$branchCashDrawerAssetAccount) {
                throw new \Exception('Required branch accounts not found');
            }

            // Check if there's enough balance in the branch bank account
            if ($branchBankAssetAccount->balance < $this->withdrawal_amount) {
                throw new \Exception('Insufficient branch bank balance');
            }

            // Create the transaction: Debit branch bank, Credit branch owner draw


             Transaction::create([
                'branch_id' => $this->branch_id,
                'debit_account_id' => $branchOwnerDrawEquityAccount->id,    // Debit owner draw (equity, increases)
                'credit_account_id' => $branchBankAssetAccount->id,         // Credit bank (asset, decreases)
                'amount' => $this->withdrawal_amount,
                'description' => $this->withdrawal_description,
                'transaction_date' => now(),
                'created_by' => Auth::id(),
                'transaction_type' => 'branch_owner_withdrawal'
            ]);



            // Reset the withdrawal fields
            $this->resetWithdrawalFields();

            // Close the modal
            $this->dispatch('closeWithdrawalModal');

            // Dispatch success alert
            $this->dispatch('show-success-alert', message: 'Branch withdrawal processed successfully.');

        } catch (\Exception $e) {
            // Dispatch error alert on failure
            $this->dispatch('show-error-alert', message: $e->getMessage());
            return false;
        }
    }



}
