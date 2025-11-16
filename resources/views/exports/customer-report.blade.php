<!DOCTYPE html>
<html>
<head>
    <title>Customer Report - {{ config('app.name') }}</title>
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
        <h3>Customer Report</h3>
        <div class="filters">
            <p><strong>Generated At:</strong> {{ now()->format('Y-m-d H:i') }}</p>
            @if($search)
                <p><strong>Search Filter:</strong> "{{ $search }}"</p>
            @endif
            @if(!empty($centre))
            <p><strong>Centre:</strong> {{ $centre }}</p>
        @endif
            <p><strong>Total Records:</strong> {{ $customers->count() }}</p>
        </div>
    </div>

    <!-- Report Table -->
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>NIC</th>
                <th>Phone</th>
                <th>Loan Status</th>
                <th>Registered Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($customers as $customer)
                <tr>
                    <td>{{ $customer->full_name }}</td>
                    <td>{{ $customer->nic }}</td>
                    <td>{{ $customer->customer_phone }}</td>
                    <td>
                        @if($customer->loans->isNotEmpty())
                            @php $status = optional($customer->loans->first()->approval)->status @endphp
                            <span class="status" style="background-color: {{ $status === 'approved' ? '#d4edda' : '#fff3cd' }};">
                                {{ $status ?? 'Pending' }}
                            </span>
                        @else
                            <span class="status" style="background-color: #f8d7da;">No Loans</span>
                        @endif
                    </td>
                    <td>{{ $customer->created_at->format('Y-m-d') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Report Footer -->
    <div class="footer">
        {{ config('app.name') }} | {{ config('app.url') }}<br>
        Page <span class="pageNumber"></span> of <span class="totalPages"></span>
    </div>
</body>
</html>
