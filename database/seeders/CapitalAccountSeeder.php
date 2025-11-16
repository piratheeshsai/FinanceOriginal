<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Branch;
use App\Models\Transaction;
use Illuminate\Database\Seeder;

class CapitalAccountSeeder extends Seeder
{
    public function run(): void
    {
        // Main Equity Account (Owner's Capital)
        $capital = Account::create([
            'branch_id' => null,
            'account_number' => 'CAPITAL-001',
            'account_name' => 'Owners Capital',
            'balance' => 0.00, // Start at 0
            'type' => 'capital',
            'category' => 'equity'
        ]);

        // Main Bank Account
        $mainBank = Account::create([
            'branch_id' => null,
            'account_number' => 'BANK-MAIN',
            'account_name' => 'Main Bank Account',
            'balance' => 0.00, // Start at 0
            'type' => 'bank',
            'category' => 'asset'
        ]);



        Account::create([
            'branch_id' => null,
            'account_number' => 'OWNER-DRAW-company',
            'account_name' => 'Owner’s Draw-company',
            'balance' => 0.00, // Starts at 0 (withdrawals increase this)
            'type' => 'owner_draw',
            'category' => 'equity',
        ]);

        Account::create([
            'branch_id' => null,
            'account_number' => 'CASH-DRAWER-company' ,
            'account_name' => 'Cash Drawer-company' ,
            'balance' => 0.00, // Initialize with 0
            'type' => 'company_cash_drawer',
            'category' => 'asset', // Asset account
        ]);


        // Branch-Specific Accounts
        $branches = Branch::all();
        foreach ($branches as $branch) {
            // Asset Accounts
            $branchBank = Account::create([
                'branch_id' => $branch->id,
                'account_number' => 'BANK-' . $branch->id,
                'account_name' => 'Branch Bank - ' . $branch->name,
                'balance' => 0.00,
                'type' => 'bank',
                'category' => 'asset',
            ]);

            Account::create([
                'branch_id' => $branch->id,
                'account_number' => 'CASH-DRAWER-' . $branch->id,
                'account_name' => 'Cash Drawer - ' . $branch->name,
                'balance' => 0.00, // Initialize with 0
                'type' => 'cash_drawer',
                'category' => 'asset', // Asset account
            ]);


            Account::create([
                'branch_id' => $branch->id,
                'account_number' => 'OWNER-DRAW-BR-' . $branch->id,
                'account_name' => 'Owner’s Draw - ' . $branch->name,
                'balance' => 0.00, // Starts at 0 (withdrawals increase this)
                'type' => 'owner_draw',
                'category' => 'equity',
            ]);



            // Create Branch Capital Account (0 balance initially)
            $branchCapital = Account::create([
                'branch_id' => $branch->id,
                'account_number' => 'CAPITAL-BR-' . $branch->id,
                'account_name' => 'Branch Capital - ' . $branch->name,
                'balance' => 0.00,
                'type' => 'branch_capital',
                'category' => 'equity',
            ]);


            // Transaction::create([
            //     'branch_id' => $branch->id,
            //     'debit_account_id' => $branchBank->id,    // Branch Asset ↑
            //     'credit_account_id' => $mainBank->id,     // Main Asset ↓
            //     'amount' => 100000.00,
            //     'description' => 'Funds transfer to branch',
            //     'transaction_type' => 'fund_transfer',
            //     'created_by' => 1,
            //     'transaction_date' => now(),
            // ]);


            // Transaction::create([
            //     'branch_id' => $branch->id,
            //     'debit_account_id' => $capital->id,  // Branch Equity ↑
            //     'credit_account_id' =>$branchCapital->id ,   // Main Equity ↓
            //     'amount' => 100000.00,
            //     'description' => 'Capital allocation recognition',
            //     'transaction_type' => 'capital_allocation',
            //     'created_by' => 1,
            //     'transaction_date' => now(),
            // ]);


            Account::create([
                'branch_id' => $branch->id,
                'account_number' => 'CASH-' . $branch->id,
                'account_name' => 'Cash in Hand - ' . $branch->name,
                'balance' => 0.00,
                'type' => 'cash',
                'category' => 'asset',
            ]);

            Account::create([
                'branch_id' => $branch->id,
                'account_number' => 'LOAN-RECEIVABLE-' . $branch->id,
                'account_name' => 'Loans Receivable - ' . $branch->name,
                'balance' => 0.00,
                'type' => 'loan_receivable',
                'category' => 'asset',
            ]);

            Account::create([
                'branch_id' => $branch->id,
                'account_number' => 'COLLECTION-CASH-' . $branch->id,
                'account_name' => 'Collection Cash - ' . $branch->name,
                'balance' => 0.00,
                'type' => 'collection_cash',
                'category' => 'asset',
            ]);

            Account::create([
                'branch_id' => $branch->id,
                'account_number' => 'PETTY-CASH-' . $branch->id,
                'account_name' => 'Petty Cash - ' . $branch->name,
                'balance' => 0.00,
                'type' => 'petty_cash',
                'category' => 'asset',
            ]);


            Account::create([
                'branch_id' => $branch->id,
                'account_number' => 'RETAINED-EARNINGS-' . $branch->id,
                'account_name' => 'Retained Earnings - ' . $branch->name,
                'balance' => 0.00,
                'type' => 'retained_earnings',
                'category' => 'equity',
            ]);

            // Revenue Accounts
            Account::create([
                'branch_id' => $branch->id,
                'account_number' => 'INTEREST-INCOME-' . $branch->id,
                'account_name' => 'Interest Income - ' . $branch->name,
                'balance' => 0.00,
                'type' => 'interest_income',
                'category' => 'revenue',
            ]);

            Account::create([
                'branch_id' => $branch->id,
                'account_number' => 'DOC-CHARGE-' . $branch->id,
                'account_name' => 'Document Charges - ' . $branch->name,
                'balance' => 0.00,
                'type' => 'document_charge_income',
                'category' => 'revenue',
            ]);

            Account::create([
                'branch_id' => $branch->id,
                'account_number' => 'LATE-FEES-' . $branch->id,
                'account_name' => 'Late Fees - ' . $branch->name,
                'balance' => 0.00,
                'type' => 'late_fee_income',
                'category' => 'revenue',
            ]);

            // Expense Accounts
            Account::create([
                'branch_id' => $branch->id,
                'account_number' => 'SALARIES-' . $branch->id,
                'account_name' => 'Salaries - ' . $branch->name,
                'balance' => 0.00,
                'type' => 'salary_expense',
                'category' => 'expense',
            ]);

            Account::create([
                'branch_id' => $branch->id,
                'account_number' => 'RENT-' . $branch->id,
                'account_name' => 'Rent Expense - ' . $branch->name,
                'balance' => 0.00,
                'type' => 'rent_expense',
                'category' => 'expense',
            ]);

            Account::create([
                'branch_id' => $branch->id,
                'account_number' => 'UTILITIES-' . $branch->id,
                'account_name' => 'Utilities - ' . $branch->name,
                'balance' => 0.00,
                'type' => 'utilities_expense',
                'category' => 'expense',
            ]);

            Account::create([
                'branch_id' => $branch->id,
                'account_number' => 'OFFICE-SUPPLIES-' . $branch->id,
                'account_name' => 'Office Supplies - ' . $branch->name,
                'balance' => 0.00,
                'type' => 'office_supplies_expense',
                'category' => 'expense',
            ]);


            Account::create([
                'branch_id' => $branch->id,
                'account_number' => 'PETTY-CASH-EXPENSE-' . $branch->id,
                'account_name' => 'petty Cash Expenses- ' . $branch->name,
                'balance' => 0.00,
                'type' => 'petty_cash_expenses',
                'category' => 'expense',
            ]);


            Account::create([
                'branch_id' => $branch->id,
                'account_number' => 'OTHER-EXPENSE-' . $branch->id,
                'account_name' => 'Other Expenses- ' . $branch->name,
                'balance' => 0.00,
                'type' => 'other_expenses',
                'category' => 'expense',
            ]);
        }
    }
}
