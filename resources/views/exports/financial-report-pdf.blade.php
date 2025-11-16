<!DOCTYPE html>
<html>
<head>
    <title>Financial Report - {{ config('app.name') }}</title>
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

        /* Summary Boxes */
        .summary-box {
            background: #f5f5f5;
            border-radius: 4px;
            padding: 15px;
            margin: 15px 0;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin: 8px 0;
        }

        /* Totals Styling */
        .total-row {
            background-color: #e9ecef;
            font-weight: bold;
        }

        /* Section Titles */
        .section-title {
            font-size: 1.1em;
            margin: 25px 0 15px 0;
            padding-bottom: 8px;
            border-bottom: 2px solid #2c3e50;
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

        /* Utility Classes */
        .text-right {
            text-align: right;
        }

        .page-break {
            page-break-after: always;
        }

        .status {
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 0.85em;
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
        <h3>Comprehensive Financial Report</h3>
        <div class="filters">
            <p><strong>Report Period:</strong>
                {{ date('M d, Y', strtotime($startDate)) }} to {{ date('M d, Y', strtotime($endDate)) }}
            </p>
            <p><strong>Generated At:</strong> {{ date('F d, Y H:i:s') }}</p>
        </div>
    </div>

    <!-- Company-wide Summary -->
    <div class="section-title">Company-wide Financial Summary</div>

    <div style="display: flex; gap: 20px; margin-bottom: 30px;">
        <div class="summary-box" style="flex: 1;">
            <h4>Balance Sheet Summary</h4>
            <div class="summary-row">
                <span>Total Assets:</span>
                <span>{{ number_format($reportData['totals']['total_assets'], 2) }}</span>
            </div>
            <div class="summary-row">
                <span>Total Liabilities:</span>
                <span>{{ number_format($reportData['totals']['total_liabilities'], 2) }}</span>
            </div>
            <div class="summary-row">
                <span>Total Equity:</span>
                <span>{{ number_format($reportData['totals']['total_equity'], 2) }}</span>
            </div>
            <div class="summary-row total-row">
                <span>Total Liabilities & Equity:</span>
                <span>{{ number_format($reportData['totals']['total_liabilities_and_equity'], 2) }}</span>
            </div>
        </div>

        <div class="summary-box" style="flex: 1;">
            <h4>Income Statement</h4>
            <div class="summary-row">
                <span>Total Revenue:</span>
                <span>{{ number_format($reportData['totals']['total_revenue'], 2) }}</span>
            </div>
            <div class="summary-row">
                <span>Total Expenses:</span>
                <span>{{ number_format($reportData['totals']['total_expenses'], 2) }}</span>
            </div>
            <div class="summary-row total-row">
                <span>Net Income:</span>
                <span>{{ number_format($reportData['totals']['net_income'], 2) }}</span>
            </div>
        </div>
    </div>

    <!-- Branch Summary -->
    <div class="section-title">Branch Performance Overview</div>
    <table>
        <thead>
            <tr>
                <th>Branch</th>
                <th>Assets</th>
                <th>Liabilities</th>
                <th>Equity</th>
                <th>Revenue</th>
                <th>Expenses</th>
                <th>Net Income</th>
            </tr>
        </thead>
        <tbody>
            @foreach($branches as $branch)
                @php $branchData = $reportData[$branch->id]; @endphp
                <tr>
                    <td>{{ $branch->name }}</td>
                    <td class="text-right">{{ number_format($branchData['summary']['total_assets'], 2) }}</td>
                    <td class="text-right">{{ number_format($branchData['summary']['total_liabilities'], 2) }}</td>
                    <td class="text-right">{{ number_format($branchData['summary']['total_equity'], 2) }}</td>
                    <td class="text-right">{{ number_format($branchData['summary']['total_revenue'], 2) }}</td>
                    <td class="text-right">{{ number_format($branchData['summary']['total_expenses'], 2) }}</td>
                    <td class="text-right">{{ number_format($branchData['summary']['net_income'], 2) }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td>Total</td>
                <td class="text-right">{{ number_format($reportData['totals']['total_assets'], 2) }}</td>
                <td class="text-right">{{ number_format($reportData['totals']['total_liabilities'], 2) }}</td>
                <td class="text-right">{{ number_format($reportData['totals']['total_equity'], 2) }}</td>
                <td class="text-right">{{ number_format($reportData['totals']['total_revenue'], 2) }}</td>
                <td class="text-right">{{ number_format($reportData['totals']['total_expenses'], 2) }}</td>
                <td class="text-right">{{ number_format($reportData['totals']['net_income'], 2) }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Individual Branch Details -->
    @foreach($branches as $branch)
        <div class="page-break"></div>

        <!-- Branch Header -->
        <div class="header">
            <img src="{{ public_path('storage/logo.png') }}" class="company-logo" alt="Company Logo">
            <div class="company-info">
                <h2>{{ $branch->name }}</h2>
                <p>{{ $branch->address }}<br>
                   Tel: {{ $branch->phone }}</p>
            </div>
        </div>

        <div class="section-title">{{ $branch->name }} Detailed Breakdown</div>

        <!-- Asset Accounts -->
        <div class="section-title">Assets</div>
        <table>
            <thead>
                <tr>
                    <th>Account</th>
                    <th>Opening</th>
                    <th>Debits</th>
                    <th>Credits</th>
                    <th>Net Change</th>
                    <th>Closing</th>
                </tr>
            </thead>
            <tbody>
                @foreach($branchData['assets'] as $account)
                <tr>
                    <td>{{ $account['account_number'] }} - {{ $account['account_name'] }}</td>
                    <td class="text-right">{{ number_format($account['opening_balance'], 2) }}</td>
                    <td class="text-right">{{ number_format($account['debits'], 2) }}</td>
                    <td class="text-right">{{ number_format($account['credits'], 2) }}</td>
                    <td class="text-right">{{ number_format($account['net_change'], 2) }}</td>
                    <td class="text-right">{{ number_format($account['ending_balance'], 2) }}</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td>Total Assets</td>
                    <td class="text-right">{{ number_format(array_sum(array_column($branchData['assets'], 'opening_balance')), 2) }}</td>
                    <td class="text-right">{{ number_format(array_sum(array_column($branchData['assets'], 'debits')), 2) }}</td>
                    <td class="text-right">{{ number_format(array_sum(array_column($branchData['assets'], 'credits')), 2) }}</td>
                    <td class="text-right">{{ number_format(array_sum(array_column($branchData['assets'], 'net_change')), 2) }}</td>
                    <td class="text-right">{{ number_format($branchData['summary']['total_assets'], 2) }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Liability Accounts -->
        <div class="section-title">Liabilities</div>
        <table>
            <thead>
                <tr>
                    <th>Account</th>
                    <th>Opening</th>
                    <th>Debits</th>
                    <th>Credits</th>
                    <th>Net Change</th>
                    <th>Closing</th>
                </tr>
            </thead>
            <tbody>
                @foreach($branchData['liabilities'] as $account)
                <tr>
                    <td>{{ $account['account_number'] }} - {{ $account['account_name'] }}</td>
                    <td class="text-right">{{ number_format($account['opening_balance'], 2) }}</td>
                    <td class="text-right">{{ number_format($account['debits'], 2) }}</td>
                    <td class="text-right">{{ number_format($account['credits'], 2) }}</td>
                    <td class="text-right">{{ number_format($account['net_change'], 2) }}</td>
                    <td class="text-right">{{ number_format($account['ending_balance'], 2) }}</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td>Total Liabilities</td>
                    <td class="text-right">{{ number_format(array_sum(array_column($branchData['liabilities'], 'opening_balance')), 2) }}</td>
                    <td class="text-right">{{ number_format(array_sum(array_column($branchData['liabilities'], 'debits')), 2) }}</td>
                    <td class="text-right">{{ number_format(array_sum(array_column($branchData['liabilities'], 'credits')), 2) }}</td>
                    <td class="text-right">{{ number_format(array_sum(array_column($branchData['liabilities'], 'net_change')), 2) }}</td>
                    <td class="text-right">{{ number_format($branchData['summary']['total_liabilities'], 2) }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Equity Accounts -->
        <div class="section-title">Equity</div>
        <table>
            <thead>
                <tr>
                    <th>Account</th>
                    <th>Opening</th>
                    <th>Debits</th>
                    <th>Credits</th>
                    <th>Net Change</th>
                    <th>Closing</th>
                </tr>
            </thead>
            <tbody>
                @foreach($branchData['equity'] as $account)
                <tr>
                    <td>{{ $account['account_number'] }} - {{ $account['account_name'] }}</td>
                    <td class="text-right">{{ number_format($account['opening_balance'], 2) }}</td>
                    <td class="text-right">{{ number_format($account['debits'], 2) }}</td>
                    <td class="text-right">{{ number_format($account['credits'], 2) }}</td>
                    <td class="text-right">{{ number_format($account['net_change'], 2) }}</td>
                    <td class="text-right">{{ number_format($account['ending_balance'], 2) }}</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td>Total Equity</td>
                    <td class="text-right">{{ number_format(array_sum(array_column($branchData['equity'], 'opening_balance')), 2) }}</td>
                    <td class="text-right">{{ number_format(array_sum(array_column($branchData['equity'], 'debits')), 2) }}</td>
                    <td class="text-right">{{ number_format(array_sum(array_column($branchData['equity'], 'credits')), 2) }}</td>
                    <td class="text-right">{{ number_format(array_sum(array_column($branchData['equity'], 'net_change')), 2) }}</td>
                    <td class="text-right">{{ number_format($branchData['summary']['total_equity'], 2) }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Revenue Accounts -->
        <div class="section-title">Revenue</div>
        <table>
            <thead>
                <tr>
                    <th>Account</th>
                    <th>Opening</th>
                    <th>Debits</th>
                    <th>Credits</th>
                    <th>Net Change</th>
                    <th>Closing</th>
                </tr>
            </thead>
            <tbody>
                @foreach($branchData['revenue'] as $account)
                <tr>
                    <td>{{ $account['account_number'] }} - {{ $account['account_name'] }}</td>
                    <td class="text-right">{{ number_format($account['opening_balance'], 2) }}</td>
                    <td class="text-right">{{ number_format($account['debits'], 2) }}</td>
                    <td class="text-right">{{ number_format($account['credits'], 2) }}</td>
                    <td class="text-right">{{ number_format($account['net_change'], 2) }}</td>
                    <td class="text-right">{{ number_format($account['ending_balance'], 2) }}</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td>Total Revenue</td>
                    <td class="text-right">{{ number_format(array_sum(array_column($branchData['revenue'], 'opening_balance')), 2) }}</td>
                    <td class="text-right">{{ number_format(array_sum(array_column($branchData['revenue'], 'debits')), 2) }}</td>
                    <td class="text-right">{{ number_format(array_sum(array_column($branchData['revenue'], 'credits')), 2) }}</td>
                    <td class="text-right">{{ number_format(array_sum(array_column($branchData['revenue'], 'net_change')), 2) }}</td>
                    <td class="text-right">{{ number_format($branchData['summary']['total_revenue'], 2) }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Expense Accounts -->
        <div class="section-title">Expenses</div>
        <table>
            <thead>
                <tr>
                    <th>Account</th>
                    <th>Opening</th>
                    <th>Debits</th>
                    <th>Credits</th>
                    <th>Net Change</th>
                    <th>Closing</th>
                </tr>
            </thead>
            <tbody>
                @foreach($branchData['expenses'] as $account)
                <tr>
                    <td>{{ $account['account_number'] }} - {{ $account['account_name'] }}</td>
                    <td class="text-right">{{ number_format($account['opening_balance'], 2) }}</td>
                    <td class="text-right">{{ number_format($account['debits'], 2) }}</td>
                    <td class="text-right">{{ number_format($account['credits'], 2) }}</td>
                    <td class="text-right">{{ number_format($account['net_change'], 2) }}</td>
                    <td class="text-right">{{ number_format($account['ending_balance'], 2) }}</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td>Total Expenses</td>
                    <td class="text-right">{{ number_format(array_sum(array_column($branchData['expenses'], 'opening_balance')), 2) }}</td>
                    <td class="text-right">{{ number_format(array_sum(array_column($branchData['expenses'], 'debits')), 2) }}</td>
                    <td class="text-right">{{ number_format(array_sum(array_column($branchData['expenses'], 'credits')), 2) }}</td>
                    <td class="text-right">{{ number_format(array_sum(array_column($branchData['expenses'], 'net_change')), 2) }}</td>
                    <td class="text-right">{{ number_format($branchData['summary']['total_expenses'], 2) }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Branch Financial Summary -->
        <div class="summary-box" style="margin-top: 25px;">
            <h4>{{ $branch->name }} Financial Health</h4>
            <div class="summary-row">
                <span>Assets vs Liabilities:</span>
                <span style="color: {{ $branchData['summary']['total_assets'] > $branchData['summary']['total_liabilities'] ? '#28a745' : '#dc3545' }};">
                    {{ number_format($branchData['summary']['total_assets'] - $branchData['summary']['total_liabilities'], 2) }}
                </span>
            </div>
            <div class="summary-row">
                <span>Profit Margin:</span>
                <span>
                    @if($branchData['summary']['total_revenue'] > 0)
                        {{ number_format(($branchData['summary']['net_income'] / $branchData['summary']['total_revenue']) * 100, 2) }}%
                    @else
                        N/A
                    @endif
                </span>
            </div>
            <div class="summary-row total-row">
                <span>Financial Status:</span>
                <span class="status" style="background-color: {{ $branchData['summary']['net_income'] > 0 ? '#d4edda' : '#f8d7da' }};">
                    {{ $branchData['summary']['net_income'] > 0 ? 'Profitable' : 'Deficit' }}
                </span>
            </div>
        </div>
    @endforeach

    <!-- Report Footer -->
    <div class="footer">
        {{ config('app.name') }} | {{ config('app.url') }}<br>
        Page <span class="pageNumber"></span> of <span class="totalPages"></span>
    </div>
</body>
</html>
