<!-- resources/views/exports/trial-balance-pdf.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Trial Balance</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .company-name {
            font-size: 18px;
            font-weight: bold;
        }
        .report-title {
            font-size: 16px;
            font-weight: bold;
            margin: 10px 0;
        }
        .report-period {
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
        }
        .total-row {
            font-weight: bold;
            background-color: #f2f2f2;
        }
        .difference-row {
            font-weight: bold;
            background-color: #ffdddd;
        }
    </style>
</head>
<body>


    <div class="header">
        <div class="company-info">
            <h2>{{ $company->name ?? config('app.name') }}</h2>
            <p>{{ $company->address ?? '123 Main Street, Kaluwanchikudy' }}</p>
            <p>Tel: {{ $company->phone ?? '+94 112 345 678' }}</p>
            @if($company->email ?? false)<p>Email: {{ $company->email }}</p>@endif
        </div>
        <div class="report-period">
            Period: {{ date('d/m/Y', strtotime($startDate)) }} - {{ date('d/m/Y', strtotime($endDate)) }}
        </div>
        <div>Branch: {{ $branchName }}</div>
    </div>
  

    <table>
        <thead>
            <tr>
                <th>Account Number</th>
                <th>Account Name</th>
                <th>Category</th>
                <th>Type</th>
                <th class="text-right">Debit</th>
                <th class="text-right">Credit</th>
            </tr>
        </thead>
        <tbody>
            @foreach($trialBalanceData as $item)
                <tr>
                    <td>{{ $item['account_number'] }}</td>
                    <td>{{ $item['account_name'] }}</td>
                    <td style="text-transform: capitalize;">{{ $item['category'] }}</td>
                    <td style="text-transform: capitalize;">{{ str_replace('_', ' ', $item['type']) }}</td>
                    <td class="text-right">{{ number_format($item['debit'], 2) }}</td>
                    <td class="text-right">{{ number_format($item['credit'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="4" class="text-right">Totals</td>
                <td class="text-right">{{ number_format($totalDebit, 2) }}</td>
                <td class="text-right">{{ number_format($totalCredit, 2) }}</td>
            </tr>
            @if($totalDebit != $totalCredit)
                <tr class="difference-row">
                    <td colspan="4" class="text-right">Difference</td>
                    <td colspan="2" style="text-align: center;">{{ number_format(abs($totalDebit - $totalCredit), 2) }}</td>
                </tr>
            @endif
        </tfoot>
    </table>

    <div class="footer">
        <p>Generated on {{ date('d/m/Y H:i') }}</p>
    </div>
</body>
</html>
