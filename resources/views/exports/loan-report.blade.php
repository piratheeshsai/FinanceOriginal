<!DOCTYPE html>
<html>
<head>
    <title>Loan Report - {{ config('app.name') }}</title>
    <style>
        /* Company Header Styles */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }

        .company-logo {
            max-width: 150px;
            height: auto;
        }

        .company-info {
            text-align: right;
            font-size: 0.9em;
        }

        /* Report Metadata */
        .report-meta {
            margin-bottom: 25px;
            padding: 15px;
            background: #f5f5f5;
            border-radius: 4px;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 0.9em;
        }

        th {
            background-color: #2c3e50;
            color: white;
            padding: 12px;
            text-align: left;
        }

        td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        /* Status Badges */
        .status {
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 0.85em;
        }

        /* Footer */
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ccc;
            text-align: center;
            font-size: 0.8em;
            color: #666;
        }
    </style>
</head>
<body>
    <!-- Company Header -->
    <div class="header">
        <div class="company-info">
            <h2>{{ $company->name ?? config('app.name') }}</h2>
            <p>{{ $company->address ?? '123 Main Street, Kaluwanchikudy' }}</p>
            <p>Tel: {{ $company->phone ?? '+94 112 345 678' }}</p>
            @if($company->email ?? false)<p>Email: {{ $company->email }}</p>@endif
        </div>
    </div>

    <!-- Report Metadata -->
    <div class="report-meta">
        <h3>Loan Report</h3>
        <div class="filters">
            <p><strong>Generated At:</strong> {{ now()->format('Y-m-d H:i') }}</p>
            @if($search)
                <p><strong>Search Filter:</strong> "{{ $search }}"</p>
            @endif
            @if(!empty($center))
            <p><strong>Centre:</strong> {{ $center }}</p>
            @endif
            <p><strong>Total Records:</strong> {{ $loans->count() }}</p>
        </div>
    </div>

    <!-- Report Table -->
    <table>
        <thead>
            <tr>
                <th>Loan ID</th>
                <th>Customer</th>
                <th>Guarantors</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Applied On</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
            @foreach($loans as $loan)
            <tr>
                <td>{{ $loan->loan_number }}</td>
                <td>{{ $loan->customer->full_name }}</td>
                <td style="max-width: 200px; font-size: 0.8em; line-height: 1.2;">
                    @foreach($loan->guarantors as $guarantor)
                        <div class="text-truncate" title="{{ $guarantor->full_name }}">
                            {{ $guarantor->full_name }}
                        </div>
                    @endforeach
                </td>
                <td>{{ number_format($loan->loan_amount, 2) }}</td>
                <td>
                    <span class="status" style="background-color:
                        @if(strtolower($loan->approval->status) === 'approved') #d4edda
                        @elseif(strtolower($loan->approval->status) === 'pending') #fff3cd
                        @elseif(strtolower($loan->approval->status) === 'rejected') #f8d7da
                        @elseif(strtolower($loan->approval->status) === 'active') #cff4fc
                        @else #e2e3e5 @endif;
                        color: @if(in_array(strtolower($loan->approval->status), ['approved', 'active'])) #0f5132
                              @elseif(strtolower($loan->approval->status) === 'pending') #856404
                              @elseif(strtolower($loan->approval->status) === 'rejected') #842029
                              @else #41464b @endif">
                        {{ ucfirst($loan->approval->status) }}
                    </span>
                </td>
                <td>{{ $loan->created_at->format('Y-m-d') }}</td>
                <td class="text-end fw-bold">
                    @if($loan->loanProgress)
                        {{ number_format($loan->loanProgress->balance, 2) }}
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" style="text-align: right;">Total:</th>
                <th>{{ number_format($loans->sum('loan_amount'), 2) }}</th>
                <th></th>
                <th></th>
                <th>{{ number_format($loans->sum(function($loan) {
                    return $loan->loanProgress ? $loan->loanProgress->balance : 0;
                }), 2) }}</th>
            </tr>
        </tfoot>
    </table>

    <!-- Report Footer -->
    <div class="footer">
        {{ config('app.name') }} | {{ config('app.url') }}<br>
        Page <span class="pageNumber"></span> of <span class="totalPages"></span>
    </div>


</body>
</html>
