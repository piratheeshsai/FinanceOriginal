<!DOCTYPE html>
<html>
<head>
    <title>Collections Report - {{ $company->name ?? config('app.name') }}</title>
    <style>
        @page {
            size: A4;
            margin: 0.5in;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 0.9em;
            width: 100%;
            margin: 0 auto;
            padding: 10px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
            width: 100%;
        }

        .company-info {
            text-align: center;
        }

        .report-meta {
            margin-bottom: 25px;
            padding: 10px;
            background: #f5f5f5;
            border-radius: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            page-break-inside: avoid;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #2c3e50;
            color: white;
            text-align: left;
        }

        td {
            text-align: left;
        }

        .amount-cell {
            text-align: right;
            font-family: 'Courier New', monospace;
        }

        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #ccc;
            text-align: center;
            font-size: 0.8em;
            color: #666;
            position: fixed;
            bottom: 10px;
            width: 100%;
        }

        .total-row {
            background-color: #e9ecef;
            font-weight: bold;
        }
    </style>
    <script>
        window.onload = function() {
            window.print();
            setTimeout(() => {
                window.close();
            }, 1000);
        };
    </script>
</head>
<body>

    <div class="header">
        <div class="company-info">
            <h2>{{ $company->name ?? config('app.name') }}</h2>
            <p>{{ $company->address ?? '123 Main Street, Kaluwanchikudy' }}</p>
            <p>Tel: {{ $company->phone ?? '+94 112 345 678' }}</p>
            @if($company->email ?? false)
                <p>Email: {{ $company->email }}</p>
            @endif
        </div>
    </div>

    <div class="report-meta">
        <h3>Collections Report</h3>
        <p><strong>Report Period:</strong>
            {{ $filters['dateFrom'] ? \Carbon\Carbon::parse($filters['dateFrom'])->format('d M Y') : 'N/A' }} -
            {{ $filters['dateTo'] ? \Carbon\Carbon::parse($filters['dateTo'])->format('d M Y') : 'N/A' }}
        </p>
        <p><strong>Generated At:</strong> {{ now()->format('d M Y H:i') }}</p>
        @if($filters['collectorId'])
            <p><strong>Collector:</strong> {{ App\Models\User::find($filters['collectorId'])->name ?? 'N/A' }}</p>
        @endif
        @if($filters['branchId'])
            <p><strong>Branch:</strong> {{ App\Models\Branch::find($filters['branchId'])->name ?? 'N/A' }}</p>
        @endif
    </div>

    @if(count($collections) > 0)
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Collector</th>
                    <th>Branch</th>
                    <th>Principal</th>
                    <th>Interest</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($collections as $collection)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($collection->collection_date)->format('d M Y') }}</td>
                        <td>{{ optional($collection->loan->customer)->full_name ?? 'N/A' }}</td>
                        <td>{{ optional($collection->collector)->name ?? 'N/A' }}</td>
                        <td>{{ optional($collection->loan->center->branch)->name ?? 'N/A' }}</td>
                        <td class="amount-cell">{{ number_format($collection->principal_amount, 2) }}</td>
                        <td class="amount-cell">{{ number_format($collection->interest_amount, 2) }}</td>
                        <td class="amount-cell">{{ number_format($collection->collected_amount, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="4" style="text-align: right;">Grand Total</td>
                    <td class="amount-cell">{{ number_format($totalPrincipal, 2) }}</td>
                    <td class="amount-cell">{{ number_format($totalInterest, 2) }}</td>
                    <td class="amount-cell">{{ number_format($totalCollections, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    @else
        <p style="text-align: center; color: #666; padding: 20px;">No collection records found for the selected criteria</p>
    @endif

    <div class="footer">
        {{ $company->name ?? config('app.name') }} | {{ $company->website ?? config('app.url') }}<br>
        Printed at: {{ now()->format('d M Y H:i') }}
    </div>

</body>
</html>
