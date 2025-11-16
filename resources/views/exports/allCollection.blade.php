<!DOCTYPE html>
<html>
<head>
    <title>Collections Report</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .total-row {
            font-weight: bold;
        }
        .total-cell {
            text-align: right;
            padding-right: 20px;
        }
    </style>
</head>
<body>
    <h2>Collections Report</h2>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Customer Name</th>
                <th>Loan Number #</th>
                <th>Staff Name</th>
                <th>Method</th>
                <th>Collected Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($collections as $collection)
                <tr>
                    <td>{{ $collection->collection_date }}</td>
                    <td>{{ optional($collection->loan->customer)->full_name }}</td>
                    <td>{{ optional($collection->loan)->loan_number }}</td>
                    <td>{{ optional($collection->staff)->name }}</td>
                    <td>{{ $collection->collection_method }}</td>
                    <td>{{ number_format($collection->collected_amount, 2) }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="5" class="total-cell">Total Collected Amount</td>
                <td class="total-cell">{{ number_format($collections->sum(function($collection) { return (float) $collection->collected_amount; }), 2) }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
