<?php


namespace App\Livewire\Loan;

use App\Models\LoanProgress as ModelsLoanProgress;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class LoanProgress extends Component
{
    use WithPagination;

    public $perPage = 20;
    public $search = '';

    public function render()
    {
        $user = Auth::user();


            $query = ModelsLoanProgress::query()
            ->with(['loan', 'loan.customer', 'loan.center.branch', 'loan.loanCollections'])
            ->orderBy('created_at', 'desc');

        // Apply Branch Restriction
        if (!$user->hasPermissionTo('View All Branch Loans')) {
            $userBranchId = $user->branch_id;

            $query->whereHas('loan.center.branch', function ($q) use ($userBranchId) {
                $q->where('id', $userBranchId);
            });
        }

        // Apply Search Filter
        if (!empty($this->search)) {
            $query->whereHas('loan.customer', function ($q) {
                $q->where('full_name', 'like', '%' . $this->search . '%')
                  ->orWhere('nic', 'like', '%' . $this->search . '%');
            });
        }

        $loanProgress = $query->paginate($this->perPage);

        return view('livewire.loan.loan-progress', [
            'loanProgress' => $loanProgress,
            'start' => ($loanProgress->currentPage() - 1) * $loanProgress->perPage()
        ]);
    }

    public function exportCsv()
    {
        $user = Auth::user();

        $query = ModelsLoanProgress::query()
            ->with(['loan', 'loan.customer', 'loan.center.branch', 'loan.loanCollections'])
            ->orderBy('created_at', 'desc');

        if (!$user->hasPermissionTo('View All Branch Loans')) {
            $userBranchId = $user->branch_id;
            $query->whereHas('loan.center.branch', function ($q) use ($userBranchId) {
                $q->where('id', $userBranchId);
            });
        }

        if (!empty($this->search)) {
            $query->whereHas('loan.customer', function ($q) {
                $q->where('full_name', 'like', '%' . $this->search . '%')
                  ->orWhere('nic', 'like', '%' . $this->search . '%');
            });
        }

        $loanProgress = $query->get();

        $csvData = [];
        $csvData[] = [
            '#', 'Loan No', 'Customer', 'Principal', 'Total', 'Principal Collected', 'Interest Collected', 'Paid', 'Balance', 'Status', 'Branch'
        ];

        foreach ($loanProgress as $index => $item) {
            $loan = $item->loan;
            $customer = $loan?->customer;
            $branch = $loan?->center?->branch;

            $csvData[] = [
                $index + 1,
                $loan?->loan_number ?? '',
                $customer ? ($customer->full_name . ' (' . $customer->nic . ')') : '',
                $loan ? number_format($loan->loan_amount, 2) : '',
                $item->total_amount ? number_format($item->total_amount, 2) : '',
                $loan ? number_format($loan->loanCollections->sum('principal_amount'), 2) : '',
                $loan ? number_format($loan->loanCollections->sum('interest_amount'), 2) : '',
                $item->total_paid_amount ? number_format($item->total_paid_amount, 2) : '',
                $item->balance ? number_format($item->balance, 2) : '',
                $item->status ?? '',
                $branch?->name ?? '',
            ];
        }

        $filename = 'loan_progress_' . now()->format('Ymd_His') . '.csv';
        $handle = fopen('php://temp', 'r+');
        foreach ($csvData as $row) {
            fputcsv($handle, $row);
        }
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response()->streamDownload(function () use ($csv) {
            echo $csv;
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
