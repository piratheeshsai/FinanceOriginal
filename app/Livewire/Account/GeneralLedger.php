<?php

namespace App\Livewire\Account;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Transaction;
use Carbon\Carbon;
use App\Exports\GeneralLedgerExport;
use Maatwebsite\Excel\Facades\Excel;

class GeneralLedger extends Component
{
    use WithPagination;

    // Filter properties
    public $from_date;
    public $to_date;
    public $search = '';
    public $perPage = 3;

    // Initialize default dates
    public function mount()
    {
        $this->from_date = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->to_date = Carbon::now()->format('Y-m-d');
    }

    // Reset pagination when filters change
    public function updatedFromDate()
    {
        $this->resetPage();
    }

    public function updatedToDate()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    // Clear filters
    public function clearFilters()
    {
        $this->from_date = '';
        $this->to_date = '';
        $this->search = '';
        $this->resetPage();
    }

    // Get transactions with filters
    public function getTransactions()
    {
        $query = Transaction::query()
            ->with(['debitAccount', 'creditAccount']) // Load related accounts
            ->orderBy('created_at', 'desc');

        // Date filtering
        if ($this->from_date) {
            $query->whereDate('created_at', '>=', $this->from_date);
        }

        if ($this->to_date) {
            $query->whereDate('created_at', '<=', $this->to_date);
        }

        // Search filtering (if you have description field)
        if ($this->search) {
            $query->where(function($q) {
                $q->where('description', 'like', '%' . $this->search . '%')
                  ->orWhereHas('debitAccount', function($sub) {
                      $sub->where('account_name', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('creditAccount', function($sub) {
                      $sub->where('account_name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        return $query->paginate($this->perPage);
    }

    // Get today's amount
    public function getTodayAmount()
    {
        return Transaction::whereDate('created_at', Carbon::today())->sum('amount');
    }

    // Calculate filtered total amount (renamed from getTotalDebit for clarity)
    public function getTotalAmount()
    {
        $query = Transaction::query();

        if ($this->from_date) {
            $query->whereDate('created_at', '>=', $this->from_date);
        }

        if ($this->to_date) {
            $query->whereDate('created_at', '<=', $this->to_date);
        }

        // Add search filtering to total amount calculation
        if ($this->search) {
            $query->where(function($q) {
                $q->where('description', 'like', '%' . $this->search . '%')
                  ->orWhereHas('debitAccount', function($sub) {
                      $sub->where('account_name', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('creditAccount', function($sub) {
                      $sub->where('account_name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        return $query->sum('amount');
    }

    // Get monthly daily average (current month)
    public function getMonthlyDailyAverage()
    {
        $currentMonth = Carbon::now();
        $startOfMonth = $currentMonth->copy()->startOfMonth();
        $endOfMonth = $currentMonth->copy()->endOfMonth();

        // Get total amount for current month
        $monthlyTotal = Transaction::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        // Get days passed in current month (including today)
        $daysPassed = $currentMonth->day;

        // Calculate daily average
        return $daysPassed > 0 ? $monthlyTotal / $daysPassed : 0;
    }

    // Export to Excel
    public function exportExcel()
    {
        $filename = 'general_ledger_' . Carbon::now()->format('Y_m_d_H_i_s') . '.xlsx';

        return Excel::download(
            new GeneralLedgerExport($this->from_date, $this->to_date, $this->search),
            $filename
        );
    }

    public function render()
    {
        $transactions = $this->getTransactions();
        $todayAmount = $this->getTodayAmount();
        $totalAmount = $this->getTotalAmount();
        $monthlyDailyAverage = $this->getMonthlyDailyAverage();

        return view('livewire.account.general-ledger', [
            'transactions' => $transactions,
            'todayAmount' => $todayAmount,
            'totalAmount' => $totalAmount,
            'monthlyDailyAverage' => $monthlyDailyAverage,
        ]);
    }
}
