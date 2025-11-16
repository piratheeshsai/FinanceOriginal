<!DOCTYPE html>
<html lang="ta">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rural Development Investment (Pvt) Ltd - Promissory Note</title>
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
            margin-bottom: 16px !important;
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

        .signature-section {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            width: 100%;
        }

        .signature-left,
        .signature-right {
            width: 45%;
            text-align: center;
        }

        .signature-label {
            font-weight: bold;
            margin-bottom: 8px;
            font-size: 12px;
        }

        .signature-line {
            border-bottom: 1.5px solid #000;
            margin: 16px auto 8px auto;
            height: 24px;
            width: 80%;
        }
    </style>
</head>

<body>
    <div class="document-container">
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
                    <div class="agreement-title">நிதி கடன் வழங்கும் உடன்படிக்கை</div>
                </div>
            </div>
        </div>

         <p class="line">
            இங்கு கீழே பல இடங்களில் "கடனாளி" என குறிப்பிடப்படும் <strong>{{ $loan->customer->full_name }}</strong> (தேசிய அடையாள அட்டை இல. <strong>{{ $loan->customer->nic }}</strong>) என்பவர்
            என்பவர் மற்றும் இங்கு பல இடங்களில் கடன் கொடுத்தவர் என குறிப்பிடப்படும் {{ $company->address ?? 'மட்டக்களப்பு' }}.
            (90020) இல் அமையப்பெற்ற அலுவலகம் மற்றும் பிரதான வர்த்தக நிலையத்தை நடத்தும் {{ $company->name ?? 'FINANCE 365' }}
            (பதிவு இல {{ $company->registration_no ?? '000000' }}) என்னும் நிறுவனமானது அதனது உரிமை, அதிகாரம், பெறுதல்கள் உடன் பின்வருமாறு
            ஏற்படுத்தப்படும் நிதிக்கடன் வழங்கும் உடன்படிக்கையின் விதிமுறைகள்:
        </p>


        <p class="line">
            <span class="clause-number">01.</span> மேற்குறிப்பிட்ட "கடனாளி" "கடன் பெற்ற தரப்பு" தமது சுய தொழிலை மேம்படுத்துவதற்கு <strong>Rs. {{ $amountInWords }}</strong> rupees only, (<strong>Rs.{{ $loan->loan_amount }}</strong>) இனை கடனாளிக்கு வழங்க இணங்குகிறார்.
        </p>



        <p class="line">
            <span class="clause-number">02. </span>இப்பணத்தினை கடனாளி நூற்றுக்கு <strong>{{ $loan->loanScheme->interest_rate }}</strong>% நாளாந்த /
            வார /இருவார / மாதாந்தம் /
            /வருடாந்தம் /ஒரு வருடம் ஆறு மாதங்களுக்கான வட்டி வீதத்தில் கடன் கொடுத்தவருக்கு
            செலுத்துவதற்கு இணங்கியுள்ளார். எவ்வாறெனினும் மேற்குறிப்பிட்ட வட்டி வீதம் "கடன் கொடுத்த
            தரப்பு" தமது வர்த்தக நடவடிக்கைகளை பொதுவாக நடத்திச் செல்வதற்கு முடியுமாக அல்லது
            மத்திய வங்கியினால் காலத்துக்கு காலம் மாற்றம் செய்யப்படும் வட்டி வீதத்தின் அடிப்படையில்
            இந்த வட்டி வீதம் மாறுபடும் என்பதனை "கடனாளி" ஏற்றுக் கொள்கிறார்.
        </p>

        <p class="line">
            <span class="clause-number">03.</span> மேற்குறிப்பிட்ட கடன்தொகை நாளாந்த / வார /இருவார /மாதாந்த /தவணையாக
            ரூபா<strong>{{ $loan->loanCollectionSchedules->first()->due ?? '....................' }}</strong>
            வீதம் நாளாந்த/ வாராந்தம்/ இருவாரம் /மாதாந்தமாக தவணைகளில் "கடனாளி" செலுத்தி முடித்தல் வேண்டும்.
        </p>

        <p class="line">
            <span class="clause-number">04.</span>
            குறித்த கடன் தொகையை குறித்த காலத்தில் செலுத்த "கடனாளி" தவறினால் தாமதிக்கும்
            ஒவ்வொரு தவணைக்கும் <strong>0.00%</strong> வீத தண்டனை வட்டி வீத அடிப்படையில் "கடன் கொடுத்த
            தரப்பு" இடம் செலுத்துவதற்கு "கடனாளி" உடன்படுகிறார்.
        </p>

        <p class="line">
            <span class="clause-number">05.</span>
            எனவே, இவ் நிதிக்கடன் கொடுக்கும் உடன்படிக்கையில் மற்றும் உறுதிப்பத்திரத்தில்
            குறிப்பிட்டுள்ள நியமங்கள் மற்றும் விதிமுறைகளுக்கு அமைவாக குறிப்பிட்ட கடன்தொகை
            அதற்குரிய வட்டியுடன் மீள பெற்றுக் கொள்வதற்கு கடன் கொடுத்த தரப்பிற்கு உரிமை
            உள்ளதென்பதை கடனாளி ஏற்றுக் கொள்கின்றார். அத்துடன் ரூபா (<strong>{{ $loan->loan_amount }}</strong>) இற்கான முழு கடன்
            தொகை மற்றும் அதற்கான வட்டி "கடன் கொடுத்த தரப்பு" அல்லது அந்நிறுவனத்தின் அதிகாரம்
            பெற்றவரினால் ஒரே தடவையினால் செலுத்துவதற்கு "கடனாளி" உடன்படுகிறார்.
        </p>

        <p class="line">
            <span class="clause-number">06.</span>
            மேலும், இக் கடன் கொடுக்கும் உடன்படிக்கையினை விளக்கும்போது இந்த கடன்
            உடன்படிக்கையின் காப்பீடாக "கடனாளி" இனால் தரபட்ட உறுதிப்பத்திரத்தினை இந்த கடன்
            உடன்படிக்கையின் ஒரு பகுதியாக ஏற்றுக் கொள்வதற்கு இரு தரப்பும் ஏற்றுக் கொண்டது.
            இதன்படி கடனை செலுத்தத் தவறின் கடன்கொடுத்தவரால் சுருக்கமான நடவடிக்கைகளின் படி
            இந்த உறுதிப்பத்திரத்தின்படி வழக்குத் தொடுத்து இப்பணத்தினைப் பெற்றுக் கொள்ள முடியும்
            என்பதனை ஏற்றுக்கொள்கிறார்.
        </p>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <p class="line">
            மேலும், இந்த பணம் கடனாகப் பெறும் உடன்படிக்கையினை விளக்கும் போது இக்கடன்
            உடன்படிக்கைக்கான காப்புறுதியாக கடனாளியினால் வழங்கப்பட்டிருக்கும் உறுதிப்பத்திரம் இந்த
            ஆவணத்தின் தமிழில் மற்றும் ஆங்கிலத்தில் எழுதப்பட்டிருக்கும் விதிமுறைகள் மற்றும்
            உறுதிமொழிகளை வாசித்து அறிந்துகொண்டதன் பின்னர் நன்றாகப் புரிந்துகொண்டு
            உடன்படிக்கையினை ஒழுங்கான முறையில் நிறைவேற்றுவதற்கு மேற்குறிப்பிட்ட இரு தரப்பினரும்
            தத்தமது உரிமை, நிர்வாகம், அறவிடல் அதிகாரம், வாரிசு என்பவற்றுடன் கட்டுப்பட்டு இந்த நிதி
            கடன் வழங்கும் திட்டத்திற்கு கையொப்பமிடுகிறோம்.
        </p>



        <div class="signature-section">
            <div class="signature-left">
                <div class="signature-label">கடன் பெறுபவர்</div>
                <div class="signature-line"></div>
                <div>(கையொப்பம்)</div>
            </div>
            <div class="signature-right">
                <div class="signature-label">கடன் கொடுக்கும் தரப்பு</div>
                <div class="signature-line"></div>
                <div>(கிளை முகாமையாளர்)</div>
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
                        <div><strong>தேசிய அடையாள எண்:</strong> <strong>{{ $guarantor->nic }}</strong></div>
                        <div><strong>தொலைபேசி எண்:</strong> <strong>{{ $guarantor->customer_phone }}</strong></div>
                        <div style="margin-top: 24px;">கையொப்பம்: ____________________</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</body>

</html>
