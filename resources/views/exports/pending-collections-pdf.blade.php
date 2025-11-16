<!DOCTYPE html>
<html>
<head>
    <title>Pending Collections Report - {{ config('app.name') }}</title>
    <style>
        /* Company Header Styles */
        .header {
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }

        .company-info {
            text-align: center;
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
            text-align: center;
            display: inline-block;
        }

        .text-danger { color: #dc3545; }

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
            {{ now()->format('Y-m-d H:i') }}
        </div>
    </div>

    <!-- Report Metadata -->
    <div class="report-meta">
        <div class="filters">
            <p><strong>Date Range:</strong>
                {{ $filters['dateFrom'] ? date('d M Y', strtotime($filters['dateFrom'])) : 'N/A' }} -
                {{ $filters['dateTo'] ? date('d M Y', strtotime($filters['dateTo'])) : 'N/A' }}
            </p>
            @if($filters['centerId'])
            <p><strong>Center:</strong> {{ \App\Models\Center::find($filters['centerId'])->name }}</p>
            @endif
            <p><strong>Total Pending:</strong> {{ number_format($totalPending, 2) }}</p>
            <p><strong>Overdue Accounts:</strong> {{ $overdueCount }}</p>
            <p><strong>Average Delay:</strong> {{ round($averageDelay) }} days</p>
        </div>
    </div>

    <!-- Report Table -->
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Customer</th>
                <th>Center</th>
                <th>Pending Due</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($collections as $collection)
            <tr>
                <td>{{ $collection->date->format('d M Y') }}</td>
                <td>{{ $collection->loan->customer->full_name ?? 'N/A' }}</td>
                <td>{{ $collection->loan->center->name ?? 'N/A' }}</td>
                <td class="text-danger">{{ number_format($collection->pending_due, 2) }}</td>
                <td>
                    @php
                        $daysOverdue = $collection->date->diffInDays(now());
                        $statusColor = match(true) {
                            $daysOverdue > 30 => 'background-color: #f8d7da; color: #721c24;',
                            $daysOverdue > 15 => 'background-color: #fff3cd; color: #856404;',
                            default => 'background-color: #d1ecf1; color: #0c5460;'
                        };
                    @endphp
                    <span class="status" style="{{ $statusColor }}">
                        {{ $daysOverdue }} Days Overdue
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" style="text-align: right;">Total:</th>
                <th colspan="2" class="text-danger">
                    {{ number_format($collections->sum('pending_due'), 2) }}
                </th>
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
