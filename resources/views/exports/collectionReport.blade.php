<!DOCTYPE html>
<html>
<head>
    <title>Collection Report - {{ $company->name ?? config('app.name') }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { display: flex; justify-content: space-between; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 15px; }
        .company-logo { max-width: 150px; height: auto; }
        .company-info { text-align: right; font-size: 0.9em; }
        .report-meta { margin-bottom: 25px; padding: 15px; background: #f5f5f5; border-radius: 4px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; font-size: 0.9em; }
        th { background-color: #2c3e50; color: white; padding: 12px; text-align: left; }
        td { padding: 10px; border: 1px solid #ddd; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .badge {
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 0.85em;
            background-color: #17a2b8;
            color: white;
        }
        .footer { margin-top: 30px; padding-top: 15px; border-top: 1px solid #ccc; text-align: center; }
        .text-right { text-align: right; }
    </style>
    <script>
        window.onload = function() {
            window.print();
            setTimeout(() => { window.close(); }, 1000);
        };
    </script>
</head>
<body>
    <div class="header">
        @if($company)
            <div class="company-info">
                <h2>{{ $company->name }}</h2>
                <p>{{ $company->address }}</p>
                <p>Tel: {{ $company->phone }}</p>
                @if($company->email)<p>Email: {{ $company->email }}</p>@endif
            </div>
        @else
            <div class="company-info">
                <h2>{{ config('app.name') }}</h2>
                <p>Company information not configured</p>
            </div>
        @endif
    </div>

    <div class="report-meta">
        <h3>Collection Report</h3>
        <div class="filters">
            <p><strong>Report Period:</strong> {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
            <p><strong>Total Collected:</strong> {{ number_format($collections->sum('collected_amount'), 2) }}</p>
            <p><strong>Generated At:</strong> {{ now()->format('d M Y H:i') }}</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Customer</th>
                <th>Loan ID</th>
                <th class="text-right">Amount</th>
                <th>Method</th>
            </tr>
        </thead>
        <tbody>
            @foreach($collections as $collection)
            <tr>
                <td>{{ \Carbon\Carbon::parse($collection->collection_date)->format('d M Y') }}</td>
                <td>{{ $collection->loan->customer->full_name }}</td>
                <td>{{ $collection->loan->loan_number }}</td>
                <td class="text-right">{{ number_format($collection->collected_amount, 2) }}</td>
                <td>
                    <span class="badge">
                        {{ ucfirst($collection->collection_method) }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-right"><strong>Total Collected:</strong></td>
                <td class="text-right"><strong>{{ number_format($collections->sum('collected_amount'), 2) }}</strong></td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        {{ $company->name ?? config('app.name') }} | {{ $company->website ?? config('app.url') }}<br>
        Printed at: {{ now()->format('d M Y H:i') }}
    </div>
</body>
</html>
