<!DOCTYPE html>
<html>

<head>
    <title>Voucher - {{ $voucher->voucher_number }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap');

        * {
            font-family: 'Poppins', sans-serif;
            box-sizing: border-box;
        }

        body {
            background: #f5f6fa;
            margin: 0;
            padding: 20px;
        }

        .voucher-container {
            max-width: 800px;
            margin: 20px auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
        }

        /* New Company Details Section */

        .company-info {
            text-align: center;
            padding: 1.5rem;
            border-bottom: 2px solid #f1f1f1;
        }

        .company-name {
            font-size: 1.8rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
        }

        .company-address {
            color: #718096;
            font-size: 0.9rem;
        }

        .header {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        /* Rest of existing styles remain the same */
        .voucher-title {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            letter-spacing: -0.5px;
        }

        .voucher-number {
            font-size: 1.25rem;
            font-weight: 400;
            opacity: 0.9;
        }

        .details {
            padding: 2rem;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 1rem 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            color: #6b7280;
            font-weight: 500;
        }

        .detail-value {
            color: #1f2937;
            font-weight: 600;
        }

        .amount {
            color: #10b981;
            font-size: 1.5rem;
            font-weight: 700;
        }

        .signatures {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
            padding: 2rem;
            border-top: 2px dashed #e5e7eb;
            margin-top: 2rem;
        }

        .signature-box {
            text-align: center;
        }

        .signature-line {
            width: 200px;
            height: 1px;
            background: #d1d5db;
            margin: 1rem auto;
            position: relative;
        }

        .signature-line:after {
            content: "";
            position: absolute;
            top: -5px;
            left: 0;
            right: 0;
            margin: auto;
            width: 100px;
            height: 2px;
            background: #d1d5db;
        }

        .print-section {
            text-align: center;
            padding: 1rem;
            background: #f8fafc;
            border-radius: 0 0 12px 12px;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-primary {
            background: #6366f1;
            color: white;
        }

        .btn-primary:hover {
            background: #4f46e5;
        }

        .btn-secondary {
            background: #e5e7eb;
            color: #1f2937;
        }

        .btn-secondary:hover {
            background: #d1d5db;
        }

        @media print {
    .header {
        background: #fff !important;
        color: #000 !important;
        border-bottom: 2px solid #000;
    }

    .voucher-title,
    .voucher-number {
        color: #000 !important;
        text-shadow: none !important;
    }

    .amount {
        color: #000 !important;
    }

    .detail-label {
        color: #444 !important;
    }

    .detail-value {
        color: #000 !important;
    }

    .signature-line {
        background: #000 !important;
        border-bottom: 1px solid #000 !important;
    }

    .signature-line:after {
        background: #000 !important;
    }

    body {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
        color-adjust: exact;
    }
}

        .customer-details {
            display: flex;
            flex-direction: column;
        }

        .customer-name {
            font-weight: normal;
        }

        .customer-nic {
            font-size: 0.8em;
            color: #1b1a1a;
            margin-top: 2px;
        }
    </style>
</head>

<body>
    <div class="voucher-container">
        <!-- Company Details Section -->
        <div class="company-info">
            <div class="company-name">RURAL DEVELOPMENT INVERSTMENT (PVT)</div>
            <div class="company-address">Batticaloa</div>
            <!-- Add this if you have a logo -->
            <!-- <img src="/path-to-logo.png" alt="Company Logo" style="height: 60px; margin-top: 1rem;"> -->
        </div>

        <div class="header">
            <div class="voucher-title">{{ strtoupper(str_replace('_', ' ', $voucher->type)) }} VOUCHER</div>
            <div class="voucher-number">Voucher #{{ $voucher->voucher_number }}</div>
        </div>

        <!-- Rest of the content remains the same -->
        <div class="details">
            <div class="detail-row">
                <span class="detail-label">Date</span>
                <span class="detail-value">{{ $voucher->date->format('d M Y') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Amount</span>
                <span class="detail-value amount">{{ number_format($voucher->amount, 2) }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">
                    @if ($voucher->type === 'Loan Disbursement')
                        Customer
                    @else
                        Account
                    @endif
                </span>
                <span class="detail-value">
                    @if ($voucher->type === 'Loan Disbursement')
                        <div class="customer-details">
                            <div class="detail-value">{{ $voucher->customer->full_name ?? 'N/A' }}</div>
                            @if ($voucher->customer->nic ?? false)
                                <div class="customer-nic">{{ $voucher->customer->nic }}</div>
                            @endif
                        </div>
                    @else
                        {{ $voucher->account->account_name ?? 'N/A' }}
                    @endif
                </span>
            </div>



            @if ($voucher->type === 'Loan Disbursement')
                <div class="detail-row">
                    <span class="detail-label">Center</span>
                    <span class="detail-value">
                        {{ $voucher->loan->center->name ?? 'N/A' }}
                    </span>
                </div>
            @endif



            @unless ($voucher->type === 'Loan Disbursement')
                <div class="detail-row">
                    <span class="detail-label">Payee</span>
                    <span class="detail-value">{{ $voucher->payee_details }}</span>
                </div>
            @endunless

            <div class="detail-row">
                <span class="detail-label">Description</span>
                <span class="detail-value">{{ $voucher->description }}</span>
            </div>
           
        </div>

        <div class="signatures">
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="detail-label">Prepared By</div>
                <div class="detail-value">{{ $voucher->creator->name }}</div>
            </div>
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="detail-label">Approved By</div>
                <div class="detail-value">{{ $voucher->approver->name }}</div>
            </div>
        </div>

        <div class="print-section">
            <button onclick="window.print()" class="btn btn-primary">Print Voucher</button>
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Close</a>
        </div>
    </div>
</body>

</html>
