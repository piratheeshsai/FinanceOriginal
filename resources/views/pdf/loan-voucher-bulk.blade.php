<!DOCTYPE html>
<html lang="ta">
<head>
    <meta charset="UTF-8">
    <title>Loan Slip</title>
    <style>
        @font-face {
            font-family: 'NotoSansTamil';
            src: url('{{ public_path('fonts/NotoSansTamil-Regular.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'NotoSansTamil';
            src: url('{{ public_path('fonts/NotoSansTamil-Bold.ttf') }}') format('truetype');
            font-weight: bold;
            font-style: normal;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'NotoSansTamil', Arial, sans-serif;
            font-size: 13px;
            color: #000;
            margin: 0;
            padding: 0;
            background-color: #fff;
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }

        .loan-container {
            border: 1px solid #000;
            padding: 10px 30px;
            margin-bottom: 50px;
            background-color: #fff;
            position: relative;
            height: calc(100vh / 3 - 30px);
            min-height: 230px;
        }

        .top-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .logo {
            height: 50px;
        }

        .company-info {
            text-align: center;
        }

        .company-name {
            font-weight: bold;
            font-size: 16px;
            white-space: nowrap;
        }

        .reg-number {
            font-size: 10px;
            color: #666;
            margin-top: 2px;
            font-style: italic;
            text-align: center;
        }

        .date {
            font-weight: bold;
            font-size: 12px;
            margin-top: 15px;
        }

        .amount-section {
            margin: 20px 0 10px;
            font-size: 14px;
        }

        .amount-section span {
            font-weight: bold;
            color: rgb(7, 7, 7);
            margin: 0 5px;
        }

        .label {
            font-weight: bold;
            color: #000;
        }

        .bottom-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-top: 20px;
        }

        .details {
            line-height: 1.5;
        }

        .signature {
            text-align: right;
            margin-top: 25px;
            font-size: 12px;
        }

        /* Page break after every 3 loans */
        .loan-container:nth-child(3n) {
            page-break-after: always;
        }

        @media print {
            .loan-container {
                height: auto;
                min-height: 250px;
                break-inside: avoid;
            }

            .loan-container:nth-child(3n) {
                page-break-after: always;
            }
        }
    </style>
</head>

<body>
    @foreach($loans as $index => $loan)
    <div class="loan-container {{ $loop->last ? 'last-loan' : '' }}">
        <!-- Top row with logo and date -->
        <div class="top-row">
            <div style="display: flex; align-items: center; gap: 10px;">
                <img src="{{ $logoSrc }}" class="logo" alt="Logo">
                <div class="company-info">
                    <div class="company-name">
                        {{ $company->name ?? 'FINANCE 365' }}
                    </div>
                    <div class="reg-number">
                        Reg No: {{ $company->registration_no ?? 'PV 00215877' }}
                    </div>
                </div>
            </div>
            <div class="date">
                திகதி: {{ $loan->loan_date }}
            </div>
        </div>

        <!-- Amount text -->
        <div class="amount-section">
            கடன் தொகையாக கொடுக்கப்படும் ரூபா <span> {{ $loan->loan_amount }} </span> என்னால் /எங்களால் பெறப்பட்டது.
        </div>

        <!-- Bottom row: left = details, right = signature -->
        <div class="bottom-row">
            <div class="details">
                <div><span class="label">பெயர் :</span> {{ $loan->customer->full_name }}</div>
                <div><span class="label">தே.அ.அ.இல : </span>{{ $loan->customer->nic }}</div>
            </div>

            <div class="signature">
                ------------------------------<br>
                கையொப்பம்/Signature
            </div>
        </div>
    </div>
    @endforeach
</body>
</html>
