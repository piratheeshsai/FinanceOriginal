<!DOCTYPE html>
<html lang="ta">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tamil Legal Document</title>
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
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'NotoSansTamil', Arial, sans-serif;
            font-size: 8px;
            line-height: 1.6;
            color: #000;
            background: #fff;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
        }

        .company-info {
            text-align: center;
        }

        .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #1a472a;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .reg-number {
            font-size: 10px;
            color: #666;
            margin-bottom: 10px;
            font-style: italic;
            text-align: center;
        }

        .agreement-title {
            font-size: 16px;
            font-weight: bold;
            margin: 15px 0;
        }

        .document {
            width: 100%;
            max-width: none;
            padding: 0;
            margin: 0;
        }

        .line {
            text-align: left;
            margin: 0;
            padding: 0;
            font-size: 12px;
        }

        .center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .blank {
            display: inline-block;
            border-bottom: 1px solid #000;
            min-width: 100px;
            height: 14px;
            margin: 0 2px;
        }

        .blank-short {
            min-width: 60px;
        }

        .blank-medium {
            min-width: 120px;
        }

        .blank-long {
            min-width: 200px;
        }

        .blank-extra-long {
            min-width: 300px;
        }

        .witness-section {
            margin-top: 20px;
        }

        .witness-grid {
            width: 100%;
            margin-top: 10px;
        }

        .witness-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .witness-item {
            width: 48%;
        }

        .witness-field {
            margin-bottom: 8px;
            font-size: 12pt;
        }

        .field-label {
            display: inline-block;
            width: 90px;
        }

        .field-blank {
            display: inline-block;
            border-bottom: 1px solid #000;
            min-width: 150px;
            height: 14px;
        }

        p {
            margin: 8px 0;
            padding: 0;
        }

        .spacing {
            margin-bottom: 12px;
        }

        .amount-box {
            display: inline-block;
            border: 2px solid #141414;
            padding: 4px 16px;
            border-radius: 6px;
            background: #f8f9fa;
            font-weight: bold;
            font-size: 14px;
            color: #070707;
            vertical-align: middle;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>

     @foreach($loans as $index => $loan)
    <div class="header">
        <div style="display: flex; align-items: center; gap: 15px;">
            <div style="flex-shrink: 0;">
                <img src="{{ $logoSrc }}" alt="Logo" style="height: 60px;">
            </div>
            <div style="flex-grow: 1;">
                <div class="company-info">
                    <div class="company-name">{{ $company->name ?? 'FINANCE 365' }}</div>
                    <div class="reg-number">Reg No: {{ $company->registration_no ?? 'PV 00215877' }}</div>
                </div>
                <div class="agreement-title">உறுதிப் பத்திரம்</div>
            </div>
        </div>
    </div>
    <div class="document">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
            <div>
                <span class="amount-box">ரூபா : {{ $loan->loan_amount }}</span>
            </div>
            <div>
                <span class="line bold" style="text-align: right;">திகதி : {{ $loan->loan_date }}</span>
            </div>
        </div>

        <br>
        <p class="line">ரூபா <strong>{{ $loan->loan_amount }}</strong> இங்கு கீழே கையெழுத்திடும்
            <strong>{{ $loan->customer->permanent_address }}</strong> இல் வதியும்
            <strong>{{ $loan->customer->full_name }}</strong> (தேசிய அடையாள அட்டை
            இல.<strong>{{ $loan->customer->nic }}</strong>) ஆகிய நான் {{ $company->address ?? 'மட்டக்களப்பு' }}. இல்
            தமது பிரதான அலுவலகம் / அல்லது வர்த்தக நிலையத்தினை நடத்திவரும் {{ $company->name ?? 'FINANCE 365' }}, (பதிவு இல. {{ $company->registration_no ?? '000000' }}) மூலம் இலங்கையின் செல்லுபடியாகும் ரூபா
           <span class="amount-highlight"><strong>{{ ucfirst($transformer->toWords($loan->loan_amount)) }}</strong></span> (ரூபா.<strong>{{ $loan->loan_amount }}</strong>) இனை மிகுதியின்றி கடனாகக் கோரி பெற்றுக்கொள்ள
            நேர்ந்துள்ளது.
        </p>
        <br>
        <br>
        <br>
        <p class="line">
            பணத்தொகையினை நிதி நிறுவனம் அல்லது அந் நிறுவனத்தின் கட்டளையின் படி இந்நிறுவனத்தின் பிரதிநிதி ஒருவர் கோரும்
            பட்சத்தில் செலுத்துவதற்கும்
            அவ்வாறு செலுத்தி விடுவிப்பு பெறும் வரை முழுத்தொகைக்குமான நாளாந்த/ வராந்த/ மாதாந்த / அரை வருட / வருடாந்த / ஒரு வருடம் ஆறு
            மாதங்களுக்கு <strong> ({{ $loan->loanScheme->interest_rate }} %) </strong>
            வீதம் வட்டியைச் செலுத்தும் வகையில் உறுதி அளித்து கையொப்பமிடுகிறேன்.
        </p>
        <br>
        <br>
        <br>
        <p class="line">மேலும், மேற்குறிப்பிட்ட தொகையான ரூபா <span class="amount-highlight"><strong>{{ ucfirst($transformer->toWords($loan->loan_amount)) }} </strong></span>
            (ரூபா.<strong>{{ $loan->loan_amount }}</strong>) இனை மிகுதியின்றி கோரி
            பெற்றுக்கொண்ட தருணத்தில் அத்தொகையினை எந்தவொரு தவணைப்பணமோ வட்டியோ அல்லது கட்டணமோ முற்பணமாக என்னால் {{ $company->name ?? 'FINANCE 365' }} இற்கு செலுத்தப்படவில்லை என்பதை சான்றுப்படுத்துகிறேன்.</p>

        <br>
        <p class="line">தமிழ் மற்றும் ஆங்கில மொழியில் காணப்படும் மேற்குறிப்பிட்ட விடயங்களை வாசித்து அறிந்து கொண்டு
            கையெழுத்திட்டோம் என உறுதியளித்து கையொப்பமிட்டு பொறுப்பளிக்கின்றேன்.</p>

        <br>

        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-top: 16px;">
            <p class="line" style="margin: 0;">
                சாட்சி <br>
                இங்கு சில இடங்களில் பிணையாளர்கள் எனக் கூறப்படும்.
            </p>
            <div style="display: flex; align-items: center;">
                <span
                    style="display: inline-block; border: 2px solid #0c0c0c; padding: 8px 24px; border-radius: 6px; background: #f8f9fa; font-weight: bold; font-size: 15px; color: #131313;">
                    முத்திரை
                </span>
            </div>
        </div>

        <br>
        <br>
        <div style="margin-top: 30px;">
            <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                @foreach ($loan->guarantors as $index => $guarantor)
                    <div
                        style="flex: 0 0 49%; box-sizing: border-box; margin-bottom: 16px; border: 1px solid #ccc; padding: 12px; border-radius: 6px; min-width: 220px;">
                        <div style="font-weight: bold; margin-bottom: 8px;">
                            சாட்சி{{ $index + 1 }}
                        </div>
                        <div><strong>பெயர்:</strong> <strong>{{ $guarantor->full_name }}</strong></div>
                        <div><strong>முகவரி:</strong> <strong>{{ $guarantor->permanent_address }}</strong></div>
                        <div><strong>தேசிய அடையாள எண்:</strong> <strong>{{ $guarantor->nic }}</strong></div>
                        <div><strong>தொலைபேசி எண்:</strong> <strong>{{ $guarantor->customer_phone }}</strong></div>
                        <div style="margin-top: 24px;">கையொப்பம்: ____________________</div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

     @if(!$loop->last)
        <div class="page-break"></div>
    @endif
    @endforeach
</body>

</html>
