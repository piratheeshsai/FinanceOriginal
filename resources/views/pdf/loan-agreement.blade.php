<!DOCTYPE html>
<html lang="ta">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>கடன் ஒப்பந்தம்</title>
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
            font-size: 12px;
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

        .content-section {
            margin-bottom: 20px;
        }

        /* Official Document Split Table Styles */
        .loan-details-container {
            display: flex;
            gap: 15px;
            margin: 20px 0;
            justify-content: space-between;
        }

        .loan-details-left, .loan-details-right {
            flex: 1;
            background: #ffffff;
            border: 2px solid #201f1f;
            padding: 0;
        }

        .section-title {
            font-size: 12px;
            font-weight: bold;
            text-align: center;
            margin: 0;
            padding: 8px;
            background-color: #ffffff;
            color: #080808;
            border-bottom: 1px solid #4b4a4a;
        }

        .detail-row {
            display: flex;
            border-bottom: 1px solid #000000;
            min-height: 35px;
            align-items: center;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: bold;
            background-color: #ffffff;
            padding: 8px 12px;
            min-width: 120px;
            font-size: 12px;
            color: #000000;
            display: flex;
            align-items: center;
        }

        .detail-value {
            padding: 8px 12px;
            font-size: 12px;
            color: #000000;
            flex: 1;
            background-color: #ffffff;
        }

        .terms-list {
            margin: 15px 0;
            padding-left: 0;
        }

        .terms-list li {
            margin-bottom: 12px;
            list-style: none;
            text-align: justify;
            position: relative;
            padding-left: 20px;
        }

        .terms-list li:before {
            content: "•";
            font-weight: bold;
            position: absolute;
            left: 0;
        }

        .signature-section {
            margin-top: 40px;
            padding: 20px;
            border: 2px solid #ffffff;
            page-break-inside: avoid;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
        }

        .signature-table td {
            padding: 20px;
            text-align: center;
            vertical-align: bottom;
            width: 50%;
        }

        .signature-line {
            border-bottom: 1px solid #000;
            width: 200px;
            height: 50px;
            display: inline-block;
            margin-bottom: 5px;
        }

        .bold {
            font-weight: bold;
        }

        .center {
            text-align: center;
        }

        .justify {
            text-align: justify;
        }

        @media print {
            .loan-details-left, .loan-details-right {
                border: 2px solid #000000;
            }

            .signature-section {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>

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
            <div class="agreement-title">கடன் ஒப்பந்தம்</div>
        </div>
    </div>
</div>

    <div class="content-section">
        <p class="justify">
            தங்களால் கோரப்பட்ட கடன் தொகையானது பின்வரும் விதிமுறைகளுக்கு அமைவாக பெற்றுக் கொடுப்பதற்கு
            எமது நிறுவனம் இணக்கம் கண்டுள்ளது என்பதனை மகிழ்வுடன் அறியத் தருகிறோம். இங்கு குறிப்பிடப்பட்டுள்ள
            விதிமுறைகளின்படி கடன்தொகையைப் பெற்றுக் கொள்வதற்கு நீங்கள் இணங்கினால், கீழே கையொப்பமமிட்டு
            அதன் பிரதி ஒன்றினை எங்களுக்கு அனுப்பி வைக்குமாறு வேண்டுகிறோம்.
        </p>
    </div>

    <div class="loan-details-container">
        <div class="loan-details-left">
            <div class="section-title">வாடிக்கையாளர் விவரங்கள்</div>

            <div class="detail-row">
                <div class="detail-label">பெயர் :</div>
                <div class="detail-value">{{ $loan->customer->full_name ?? '.............................................' }}</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">தே.அ.அ.இல :</div>
                <div class="detail-value">{{ $loan->customer->nic ?? '...............................................' }}</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">அங்கத்துவ இல :</div>
                <div class="detail-value">{{ $loan->customer->customer_no ?? '...............................................' }}</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">தொலைபேசி எண் :</div>
                <div class="detail-value">{{ $loan->customer->customer_phone ?? '........................' }}</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">கடன் இல :</div>
                <div class="detail-value">{{ $loan->loan_number ?? '...............................................' }}</div>
            </div>
        </div>

        <div class="loan-details-right">
            <div class="section-title">கடன் விவரங்கள்</div>

            <div class="detail-row">
                <div class="detail-label">கடன் தொகை :</div>
                <div class="detail-value">Rs.{{ number_format($loan->loan_amount ?? 0, 2) }}</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">கடன் பெற்ற தேதி :</div>
                <div class="detail-value">{{ $loan->loan_date ? \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y') : '........................' }}</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">வட்டி விகிதம் :</div>
                <div class="detail-value">{{ $loan->loanScheme->interest_rate ?? '0' }}%</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">ஆவண கட்டணம் :</div>
                <div class="detail-value">Rs.{{ number_format($loan->document_charge ?? 0, 2) }}</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">கடன் தவணை :</div>
                <div class="detail-value">{{ $loan->loanScheme->loan_term ?? '........................' }}</div>
            </div>
        </div>
    </div>

    <div class="content-section">
        <p class="justify">
            <span class="bold">
                கடன் தொகைக்கு சமர்ப்பிக்கப்பட்ட பிணைகள் {{ $company->name ?? 'FINANCE 365' }}
நிதி நிறுவனத்தின் வாடிக்கையாளர்களுக்கு வாடிக்கையாளராக நீங்கள் எம் மீது வைத்துள்ள
நம்பிக்கைக்கு நாம் நன்றி கூறுகிறோம். சட்டதிட்டங்களின்படி விரைவான சேவையைப் பெற்றுக்
கொடுப்பது எமது பிரதான நோக்கமாகும். அதன்படி பின்வரும் விடயங்கள் தொடர்பில் தங்களது விசேட
கவனத்தை செலுத்த வேண்டியுள்ளது.
            </span>
        </p>
    </div>

    <ul class="terms-list">
        <li>கடன்தொகையை பெற்றுக் கொள்வதற்கு {{ $company->name ?? 'FINANCE 365' }} நிறுவனத்தில் செலுத்தப்படும் கட்டணங்கள் தவிர்ந்த வேறெந்த கட்டணமும் எங்கள் அலுவலர்களுக்கோ அல்லது பிற நபர்களுக்கோ வழங்கக்கூடாதுடன், அது தொடர்பான எந்த பொறுப்பையும் நிறுவனம் ஏற்றுக்கொள்ளாது.</li>

        <li>பெற்றுக்கொண்ட கடன் தொகை அல்லது அதன் ஒரு பகுதியை யாருக்கு ஒருவருக்கு கொடுத்திருந்தாலும் அது தொடர்பாக நிறுவனம் பொறுப்பேற்காததுடன், எங்கள் நிறுவனத்தில் பெற்றுக்கொண்ட கடனை செலுத்தும் முழுமையான பொறுப்பு உங்களுடையதாகும்.</li>

        <li>{{ $company->name ?? 'FINANCE 365' }} இல் அதிகாரம் பெற்ற அலுவலர்களுக்கு மட்டுமே தவணைத் தொகையை செலுத்துவதுடன், பிற வெளி நபர்களிடம் செலுத்தப்படும் தொகைக்கு நிறுவனம் பொறுப்பேற்காது. மேலும் சம்பந்தப்பட்ட அலுவலர்களின் நிறுவன அடையாள அட்டையை உறுதிப்படுத்திக் கொள்வதற்கு அவருடைய நிறுவன அடையாள அட்டையை பரிசோதிக்க உங்களுக்கு உரிமை உள்ளது.</li>

        <li>கடன் தொகையை பெற்றுக்கொண்டு நீங்கள் குறிப்பிட்ட விடயத்திற்கு மட்டுமே கடன் தொகையை பயன்படுத்துவது உங்களுடைய பொறுப்பாகும்.</li>

        <li>அச்சு இயந்திரத்தின் மூலம் பெறப்படும் பற்றுச்சீட்டினை உங்கள் வசம் வைத்திருக்கவும்.</li>

        <li>உங்களால் பெறப்பட்ட கடன்தொகை தொடர்பான உடன்படிக்கையினை மீறும் சந்தர்ப்பத்தில் அக்
         கடன் தொகையை உங்களிடமிருந்து ஒரே தடவையில் அறவிடுவதற்கு, பிணையாளர்களிடமிருந்து அறவிடுவதற்கு நிறுவனம் சட்டபூர்வ நடவடிக்கைகளை மேற்கொள்ளும் உரிமை {{ $company->name ?? 'FINANCE 365' }} இடம் உள்ளது..</li>

        <li>{{ $company->name ?? 'FINANCE 365' }} நிறுவனத்தின் அதிகாரம் பெற்ற மற்றும் அலுவலருக்கும் நீங்கள் பெற்றுக்கொண்ட கடன்தொகை தொடர்பாக பின்தொடர்தல் பெறுவதற்கு வணிக நிலையத்தை அல்லது வீட்டை பரிசோதனை செய்ய இடம்கொடுத்தல் வேண்டும்.</li>

        <li>நீங்கள் முறையாக கடன் தவணைக் கட்டணத்தை செலுத்தாதபோது வழக்கற்ற தொகைக்கான  வட்டியாக 0.00% ஆக வட்டி விகிதம் கணக்கிடப்பட்டு அசல்தொகை மீட்டெடுக்கப்படும்.</li>

        <li>{{ $company->name ?? 'FINANCE 365' }} யின் அனுமதியின்றி நிறுவனத்தின் பெயர் மற்றும் நிறுவனத்திற்கு சொந்தமான எந்தவொரு பொருளையும் வேறெந்த வெளி
            நடவடிக்கைகளுக்கும் உட்படுத்துவதோ உபயோகப்படுத்தவோ முடியாது. </li>


    </ul>

    <div class="signature-section">
    <div style="text-align: center; font-weight: bold; font-size: 14px; margin-bottom: 30px; border-bottom: 2px solid #ffffff; padding-bottom: 10px;">
        கையொப்பம் மற்றும் ஒப்புதல்
    </div>

    <div style="display: flex; justify-content: space-between; gap: 50px; margin-top: 40px;">
        <!-- Left Side - Officer -->
        <div style="flex: 1; text-align: center;">
            <div style="border-bottom: 2px solid #000; width: 200px; height: 60px; margin: 0 auto 15px;"></div>
            <div style="font-weight: bold; margin-bottom: 20px;">அதிகாரம் பெற்ற அலுவலரின் கையொப்பம்</div>


        </div>

        <!-- Right Side - Customer -->
        <div style="flex: 1; text-align: center;">
            <div style="border-bottom: 2px solid #000; width: 200px; height: 60px; margin: 0 auto 15px;"></div>
            <div style="font-weight: bold; margin-bottom: 20px;">வாடிக்கையாளரின் கையொப்பம்</div>


        </div>
    </div>
</div>

</body>
</html>
