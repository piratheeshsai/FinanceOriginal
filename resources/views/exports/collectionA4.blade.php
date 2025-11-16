<!DOCTYPE html>
<html>
<head>
    <title>Payment Receipt - {{ config('app.name') }}</title>
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

        /* Footer */
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ccc;
            text-align: center;
            font-size: 0.8em;
            color: #666;
        }

        /* Invoice Specific Styles */
        .receipt-title {
            font-size: 1.4em;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
            color: #2c3e50;
        }

        .meta-columns {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .meta-column {
            width: 48%;
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
    <!-- Company Header -->
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

    <!-- Receipt Title -->
    <div class="receipt-title">PAYMENT RECEIPT</div>

    <!-- Metadata Columns -->
    <div class="meta-columns">
        <div class="meta-column">
            <p><strong>Invoice ID:</strong> {{ $invoice->invoice_number }}</p>
            <p><strong>Customer Name:</strong> {{ $invoice->collection->loan->customer->full_name }}</p>
            <p><strong>Loan Number:</strong> {{ $invoice->collection->loan->loan_number }}</p>
        </div>
        <div class="meta-column">
            <p><strong>Date:</strong> {{ now()->format('m/d/Y') }}</p>
            <p><strong>Time:</strong> {{ \Carbon\Carbon::parse($invoice->collection->created_at)->format('h:i A') }}</p>
            <p><strong>Collected By:</strong> {{ $invoice->collection->staff->name }}</p>
        </div>
    </div>

    <!-- Payment Details Table -->
    <table>
        <thead>
            <tr>
                <th>Collection Date</th>
                <th>Payment Method</th>
                <th>Amount Received</th>
                <th>Outstanding Balance</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ \Carbon\Carbon::parse($invoice->collection->collection_date)->format('m/d/Y') }}</td>
                <td>{{ $invoice->collection->collection_method }}</td>
                <td>{{ number_format($invoice->collection->collected_amount, 2) }}</td>
                <td>{{ number_format($invoice->collection->loan->loanProgress->balance, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        {{ $company->name ?? config('app.name') }}<br>
        Thank you for your payment!
    </div>
</body>
</html>
