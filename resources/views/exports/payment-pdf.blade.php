<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payments</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Payment Details</h2>
    <table>
        <thead>
            <tr>
                <th>Due Amount</th>
                <th>Due Interest</th>
                <th>Due Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($payments as $payment)
                <tr>
                    <td>{{ number_format($payment['due'], 2) }}</td>
                    <td>{{ number_format($payment['interest'], 2) }}</td>
                    <td>{{ number_format($payment['total'], 2) }}</td>
                </tr>
            @endforeach

            <tr style="background-color: #544a4a; font-weight: bold; color: #f7f1f1;">
                <td> {{ number_format(collect($payments)->sum('due'), 2) }}</td>
                <td> {{ number_format(collect($payments)->sum('interest'), 2) }}</td>
                <td><strong>Total:</strong> {{ number_format(collect($payments)->sum('total'), 2) }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
