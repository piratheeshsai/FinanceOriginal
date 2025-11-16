<div class="card-body px-3 pt-3 pb-2">
    <div class="col-12">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0 text-white">Loan Progress Overview</h5>
        </div>
        <div class="card-body px-3 pt-3 pb-2">
    <div class="table-responsive"> <!-- Add this wrapper -->
        <table class="table table-hover align-middle table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Loan No</th> <!-- Remove fixed widths -->
                    <th>Customer</th>
                    <th>Loan Type</th>
                    <th>Principal</th>
                    <th>Total</th>
                    <th>Paid</th>
                    <th>Balance</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-nowrap"> <!-- Add text-nowrap for number fields -->
                        <span class="badge bg-info text-white">
                            {{ $loanProgress->loan->loan_number }}
                        </span>
                    </td>
                    <td>
                        <strong>{{ $loanProgress->loan->customer->full_name }}</strong>
                        <div class="text-muted small">{{ $loanProgress->loan->customer->nic }}</div>
                    </td>
                    <td class="text-wrap">{{ $loanProgress->loan->loan_type }}</td>
                    <td class="text-success fw-bold">
                        {{ number_format($loanProgress->loan->loan_amount, 2) }}
                    </td>
                    <td class="text-primary fw-bold">
                        {{ number_format($loanProgress->total_amount, 2) }}
                    </td>
                    <td class="text-success fw-bold">
                        {{ number_format($loanProgress->total_paid_amount, 2) }}
                    </td>
                    <td class="text-danger fw-bold">
                        {{ number_format($loanProgress->balance, 2) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
        </div>
    </div>
</div>
