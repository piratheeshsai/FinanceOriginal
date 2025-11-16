<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JMS Invoice</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 2px;
        }

        .invoice {
            width: 58mm; /* Changed from 80mm to 58mm */
            max-width: 100%;
            margin: 0 auto;
            padding: 2px;
        }

        .header {
            text-align: center;
            margin-bottom: 3px;
        }

        .header h1 {
            font-size: 14px;
            margin: 2px 0;
        }

        .header p {
            margin: 1px 0;
            font-size: 10px;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }

        .content {
            margin: 3px 0;
        }

        .details {
            margin: 3px 0;
            font-size: 10px;
        }

        .details p {
            margin: 2px 0;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap; /* Allow wrapping if needed */
        }

        .details span:first-child {
            font-weight: bold;
            width: 40%; /* Control label width */
        }

        .details span:last-child {
            width: 60%; /* Control value width */
            text-align: right;
            word-break: break-word; /* Break long text if needed */
        }

        .footer {
            text-align: center;
            margin-top: 5px;
            font-size: 10px;
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
    <div class="invoice">
        <!-- Header Section -->
        <div class="header">
            @if($company)
                <h1>{{ $company->name }}</h1>
                <p>{{ $company->address }}</p>
                <p>Tel: {{ $company->phone }}</p>
                @if($company->email)<p>Email: {{ $company->email }}</p>@endif
            @else
                <h1>{{ config('app.name') }}</h1>
                <p>Company info not set</p>
            @endif

            @if($invoice->collection->loan->center)
                {{-- <p>{{ $invoice->collection->loan->center->branch->name }}</p> --}}
                <p>Center:{{ $invoice->collection->loan->center->name }}</p>
                {{-- <p>Tel: {{ $invoice->collection->loan->center->branch->phone }}</p> --}}
            @endif
        </div>

        <div class="line"></div>

        <!-- Invoice Details -->
        <div class="content">
            <div class="details">
                <p>
                    <span>Invoice ID:</span>
                    <span>{{ $invoice->invoice_number }}</span>
                </p>
                <p>
                    <span>Date/Time:</span>
                    <span>{{ \Carbon\Carbon::parse($invoice->collection->created_at)->format('d-m-Y h:i A') }}</span>
                </p>
                <p>
                    <span>Customer:</span>
                    <span>{{ $invoice->collection->loan->customer->full_name }}</span>
                </p>
                <p>
                    <span>Loan No:</span>
                    <span>{{ $invoice->collection->loan->loan_number }}</span>
                </p>
            </div>

            <div class="line"></div>

            <div class="details">
                <p>
                    <span>Collected By:</span>
                    <span>{{ $invoice->collection->staff->name }}</span>
                </p>
                <p>
                    <span>Method:</span>
                    <span>{{ $invoice->collection->collection_method }}</span>
                </p>
                <p>
                    <span>Amount:</span>
                    <span>Rs. {{ number_format($invoice->collection->collected_amount, 2) }}</span>
                </p>
                <p>
                    <span>Outstanding:</span>
                    <span>Rs. {{ number_format($invoice->collection->loan->loanProgress->balance, 2) }}</span>
                </p>
            </div>
        </div>

        <div class="line"></div>

        <!-- Footer Section -->
        <div class="footer">
            <p>Thank you for your payment!</p>
            <p>{{ $company->name ?? config('app.name') }}</p>
        </div>
    </div>
</body>
</html>
