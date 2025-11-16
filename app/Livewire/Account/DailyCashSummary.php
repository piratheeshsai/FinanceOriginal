<?php namespace App\Livewire\Account;

use App\Models\Account;
use App\Models\Branch;
use App\Models\Transaction;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class DailyCashSummary extends Component
{
    use WithPagination;

    public $date;
    public $branchId;
    public $showDetails = true;
    public $perPage = 5;
    public $cashInPage = 1;
    public $cashOutPage = 1;

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount()
    {
        $this->date = Carbon::now()->format('Y-m-d');
    }

    public function render()
    {

        $cashAccounts = Account::where('type', 'cash')
            ->when($this->branchId, fn($q) => $q->where('branch_id', $this->branchId))
            ->get();


        foreach ($cashAccounts as $account) {
            $previousDay = Carbon::parse($this->date)->subDay()->endOfDay();

            $debits = Transaction::where('debit_account_id', $account->id)
                ->where('created_at', '<=', $previousDay)
                ->sum('amount');

            $credits = Transaction::where('credit_account_id', $account->id)
                ->where('created_at', '<=', $previousDay)
                ->sum('amount');

            $account->opening_balance = $debits - $credits;
        }


        $startOfDay = Carbon::parse($this->date)->startOfDay();
        $endOfDay = Carbon::parse($this->date)->endOfDay();

        $transactions = Transaction::whereBetween('created_at', [$startOfDay, $endOfDay])
            ->where(function($query) use ($cashAccounts) {
                $query->whereIn('debit_account_id', $cashAccounts->pluck('id'))
                    ->orWhereIn('credit_account_id', $cashAccounts->pluck('id'));
            })
            ->with(['debitAccount', 'creditAccount', 'createdBy'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate opening balance
        $openingBalance = $cashAccounts->sum('opening_balance');

        // Categorize transactions
        $cashIn = $transactions->filter(function($t) use ($cashAccounts) {
            return $cashAccounts->contains('id', $t->debit_account_id) &&
                   !$cashAccounts->contains('id', $t->credit_account_id);
        });

        $cashOut = $transactions->filter(function($t) use ($cashAccounts) {
            return $cashAccounts->contains('id', $t->credit_account_id) &&
                   !$cashAccounts->contains('id', $t->debit_account_id);
        });

        // Paginate cash in and cash out transactions
        $paginatedCashIn = $cashIn->forPage($this->cashInPage, $this->perPage);
        $paginatedCashOut = $cashOut->forPage($this->cashOutPage, $this->perPage);

        // Calculate totals
        $totalCashIn = $cashIn->sum('amount');
        $totalCashOut = $cashOut->sum('amount');
        $closingBalance = $openingBalance + $totalCashIn - $totalCashOut;

        return view('livewire.account.daily-cash-summary', [
            'openingBalance' => $openingBalance,
            'cashIn' => $paginatedCashIn,
            'cashOut' => $paginatedCashOut,
            'totalCashIn' => $totalCashIn,
            'totalCashOut' => $totalCashOut,
            'closingBalance' => $closingBalance,
            'cashAccounts' => $cashAccounts,
            'cashInTotal' => $cashIn->count(),
            'cashOutTotal' => $cashOut->count()
        ]);
    }

    // Pagination methods for cash in and cash out
    public function nextCashInPage()
    {
        $this->cashInPage++;
    }

    public function previousCashInPage()
    {
        if ($this->cashInPage > 1) {
            $this->cashInPage--;
        }
    }

    public function nextCashOutPage()
    {
        $this->cashOutPage++;
    }

    public function previousCashOutPage()
    {
        if ($this->cashOutPage > 1) {
            $this->cashOutPage--;
        }
    }

    // Previous day, next day, and today methods remain the same
    public function previousDay()
    {
        $this->date = Carbon::parse($this->date)->subDay()->format('Y-m-d');
    }

    public function nextDay()
    {
        $this->date = Carbon::parse($this->date)->addDay()->format('Y-m-d');
    }

    public function today()
    {
        $this->date = Carbon::now()->format('Y-m-d');
    }
}
