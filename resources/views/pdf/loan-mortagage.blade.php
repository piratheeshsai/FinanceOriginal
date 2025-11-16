<!DOCTYPE html>
<html lang="ta">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>பிணைப்பத்திரம்</title>
    <style>
        @font-face {
            font-family: 'NotoSansTamil';
            src: url('fonts/NotoSansTamil-Regular.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'NotoSansTamil';
            src: url('fonts/NotoSansTamil-Bold.ttf') format('truetype');
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
            padding: 30px;
            max-width: 800px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
        }

        .header-content {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
        }

        .logo-container {
            flex-shrink: 0;
        }

        .logo-container img {
            height: 60px;
            width: auto;
        }

        .title-container {
            flex-grow: 1;
            text-align: center;
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
            letter-spacing: 1px;
        }

        .reg-number {
            font-size: 10px;
            color: #666;
            margin-bottom: 10px;
            font-style: italic;
            text-align: center;
        }

        .document-title {
            font-size: 22px;
            font-weight: bold;
            margin: 10px 0;
            color: #333;
        }

        .subtitle {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 25px;
            text-align: center;
        }

        .content-section {
            margin-bottom: 25px;
            text-align: justify;
            line-height: 1.8;
        }

        .paragraph {
            margin-bottom: 20px;
            text-align: justify;
            text-indent: 20px;
        }

        /* Enhanced styling for dynamic data */
        .guarantor-info {
            font-size: 12px;
            font-weight: bold;
            color: #0a0a0a;
            margin: 10px 0;
            padding: 8px;
            background-color: #f9f9f9;
            border-left: 4px solid #070707;
            line-height: 1.8;
        }

        .loan-data {
            font-weight: bold;
            color: #080808;
            font-size: 12px;
        }

        .amount-highlight {
            font-weight: bold;
            color: #0f0f0f;
            font-size: 12px;
            background-color: #fff3cd;
            padding: 2px 4px;
            border-radius: 3px;
        }

        .signature-section {
            margin-top: 50px;
            page-break-inside: avoid;
        }

        .signature-table td {
            padding: 8px 10px;
            /* More top/bottom spacing */
            line-height: 1.6;
            /* Looser spacing between lines */
            font-size: 12px;
        }

        .signature-line {
            display: inline-block;
            border-bottom: 1px solid #000;
            width: 150px;
            height: 1px;
            margin-top: 4px;
            /* Add space above the line */
            vertical-align: middle;
        }


        .guarantor-number {
            font-weight: bold;
            color: #0c0c0c;
            font-size: 15px;
            margin-bottom: 8px;
            display: block;
        }

        .dotted-line {
            display: inline-block;
            border-bottom: 1px dotted #000;
            width: 200px;
            margin: 0 2px;
        }

        .short-line {
            display: inline-block;
            border-bottom: 1px dotted #000;
            width: 100px;
            margin: 0 2px;
        }

        .medium-line {
            display: inline-block;
            border-bottom: 1px dotted #000;
            width: 150px;
            margin: 0 2px;
        }

        .bold {
            font-weight: bold;
        }

        .agreement-title {
            font-size: 18px;
            font-weight: bold;
            margin: 15px 0;
            color: #070707;
        }

        @media print {
            body {
                padding: 20px;
                max-width: none;
            }

            .guarantor-info {
                background-color: transparent !important;
                -webkit-print-color-adjust: exact;
            }

            .amount-highlight {
                background-color: transparent !important;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="header-content">
            <div class="logo-container">
                <img src="{{ $logoSrc }}" alt="Logo">
            </div>
            <div class="title-container">
                <div class="company-info">
                    <div class="company-name">{{ $company->name ?? 'FINANCE 365' }}</div>
                    <div class="reg-number">Reg No: {{ $company->registration_no ?? 'PV 00215877' }}</div>
                </div>
                <div class="agreement-title">பிணைப்பத்திரம்</div>
            </div>
        </div>
    </div>

    <div class="content-section">
        <p class="paragraph">
            இங்கு பல இடங்களில் பிணையாளர் எனக் குறிப்பிடப்படும்
        </p>

        <div class="guarantor-info">
            @foreach ($loan->guarantors as $guarantor)
                <strong>{{ $guarantor->permanent_address }}</strong> வதியும்,
                <strong>{{ $guarantor->full_name }}</strong> (தே.அ.அ.இல. <strong>{{ $guarantor->nic }}</strong>),<br>
            @endforeach
        </div>

        <p class="paragraph">
            போன்றவர்கள் இங்கு கீழே பல இடங்களில் பிணை உரித்தான தரப்பு எனக் குறிப்பிடப்படும்
            {{ $company->address ?? 'மட்டக்களப்பு.' }} இல் தமது பிரதான வர்த்தக நிறுவனத்தை நடத்திவரும்
            {{ $company->name ?? 'FINANCE 365' }}, (பதிவு இல. {{ $company->registration_no ?? '000000' }}) இல்
            <span class="loan-data">{{ $loan->loan_date }}</span> ஆம் திகதி தமிழ்மொழியில் தயாரிக்கப்பட்டு வாசித்து
            விளக்கிக் கூறியதின்படி புரிந்து கொண்ட இந்த பிணைப் பத்திரத்தின் தன்மை பின்வருமாறு :
        </p>
    </div>

    <div class="content-section">
        <p class="paragraph">
            மேல்குறிப்பிடப்பட்ட உரித்துடைய தரப்பான {{ $company->name ?? 'FINANCE 365' }}
            உடன் இங்கு பல இடங்களில் கடனாளி எனக் கூறப்படும் <span
            class="loan-data">{{ $loan->customer->permanent_address }}</span> இல் வதியும்
            <span class="loan-data">{{ $loan->customer->full_name }}</span> (தே.அ.அ. இல. <span
            class="loan-data">{{ $loan->customer->nic }}</span>) என்பவர் <span
            class="loan-data">{{ $loan->loan_date }}</span> ஆம்
            திகதி ஏற்படுத்திக் கொண்ட பணம் கடனாக வழங்கும் உடன்படிக்கை மற்றும் உறுதிப் பத்திரத்தின் பேரில்
            அதன்படி இலங்கையின் செல்லுபடியான பணத்தில் <span class="amount-highlight">ரூபா. {{ $amountInWords }} rupees
            only (Rs.{{ $loan->loan_amount }})</span>
            இற்கான கடன்தொகை மேற்குறிப்பிடப்பட்ட பணம் கடனாக வழங்கும் உடன்படிக்கையில்
            குறிப்பிடப்பட்டுள்ள விதிமுறைகள் மற்றும் உறுதிப்பத்திரத்திற்கமைய மேற்குறிப்பிடப்பட்ட கடனாளியினால்
            தரப்பினர் ஏற்றுக்கொண்டனர்.
        </p>
    </div>

    <div class="content-section">
        <p class="paragraph">
            அதன்படி, மேற்குறிப்பிடப்பட்ட <span class="amount-highlight">Rs.{{ $loan->loan_amount }}</span> இற்கான
            கடன்தொகை வழங்குவதற்கான
            உடன்படிககையில் குறிப்பிடப்பட்டுளள விதிமுறைகள் மற்றும் உறுதிப்பத்திரம் மேற்குறிப்பிடப்பட்ட கடனாளியான
            <span class="loan-data">{{ $loan->customer->full_name }}</span> என்பவரால் மேற்குறிப்பிடப்பட்ட {{ $company->name ?? 'FINANCE 365' }} ற்கு குறிப்பிடப்பட்ட முழு கடன் தொகை மற்றும்
            அதற்கான வட்டி உடன் அல்லது நிலுவைக் கடன் தொகை (தண்டப் பணம் வட்டி உட்பட)
            செலுத்துவதற்குத் தவறினால் அல்லது தட்டிக் கழித்தால் குறித்த முழு கடன் தொகையோடு அதற்கான
            வட்டி நிலுவைக் கடன் தொகை (தண்டப்பண வட்டி உட்பட) மேற்குறிப்பிடப்பட்ட பணம் கடனாக
            வழங்கும் உடன்படிக்கையின் பிரகாரம் செலுத்துவதற்கு உடன்பட்டு இணங்குவதுடன் இந்த பிணைப்
            பத்திரம் பிணையாளர்களான எம்மால் பிணை உரிமை பெற்ற தரப்பிற்கு ஒன்றாகவோ அல்லது
            தனித்தனியாகவோ செலுத்துவதற்கு இணங்குகிறோம்.
        </p>
    </div>

    <div class="content-section">
        <p class="paragraph">
            பிணை உரிமை பெற்ற தரப்பினர் மேற்குறிப்பிடப்பட்ட கடன்தொகை மற்றும் அதற்கான வட்டி உட்பட
            அனைத்துக் கட்டணங்களையும் கடனாளி மற்றும் பிணையாளர்களிடம் ஒன்றாகவோ அல்லது
            தனித்தனியாகவோ கோருவதற்கு மேற்கொள்ளப்படும் சட்டபூர்வ நடவடிக்கைகளில் மேற்குறித்த கடனாளி
            மற்றும் பிணையாளருக்கு எதிராக ஒன்றாகவோ அல்லது தனித்தனியாகவோ வழக்குத் தொடர பிணை
            உரிமை பெற்ற தரப்பினருக்கு உரிமை உண்டு என்பதனை பிணையாளர்கள் ஏற்றுக் கொண்டனர்.
        </p>
    </div>

    <div class="content-section">
        <p class="paragraph">
            மேலும், மேற்குறிப்பிட்ட பணம் கடனாக வழங்கும் உடன்படிக்கையினை விளக்கும்போது இந்த
            பிணைபபத்திரமானது குறிப்பிட்ட பணம் கடனாக வழங்கும் உடன்படிக்கையின் ஒரு பகுதியாகவே ஏற்றுக்
            கொள்வதற்கு பிணையாளர்கள் இங்கு பிணைப்பத்திரத்தினை எழுதி கையெழுத்திடுவதன் மூலம் இணங்கி
            ஏற்றுக் கொண்டுள்ளனர்.
        </p>
    </div>

    <div class="content-section">
        <p class="paragraph">
            மேலும், கடனாளி மேற்குறித்த கடனாக வழங்கும் உடன்படிக்கையின் பிரகாரம் செலுத்த
            வேண்டிய தொகை மற்றும் அதற்கான வட்டியை செலுத்த தவறினால் அத்தொகையினை முதலில்
            கடனாளியிடம் கோராமல் மேற்குறிப்பிடப்பட்ட பிணையாளர்களிட் முதலில் கோருவதற்கு உரிமை
            உண்டென்றும் மேற்குறிப்பிடப்பட்ட கடனாளியுடன் ஒன்றாகவோ அல்லது வெவ்வேறாகவோ அல்லது
            பிணையாளர்களுக்கு எதிராக மட்டும் அத்தொகையினைக் கோரி வழக்குத் தொடரும் உரிமை பிணை
            உரிமை பெற்ற தரப்பினருக்கு உள்ளதென்பதனை பிணையாளர்கள் ஏற்றுக்கொண்டனர்.
        </p>
    </div>

    <div class="content-section">
        <p class="paragraph">
            மேலும், பிணையாளர்கள், பிணையாளருக்கு உள்ள சலுகையான அறவிடவேண்டிய தொகையினை
            முதலில் கடனாளியிடமே கோர வேண்டும் எனக்கேட்கும் உரிமை மற்றும் கடனாளிக்கு எதிராகவே முதில்
            வழக்குத் தொடரல் வேண்டும் எனக் கேட்டுக்கொள்ளும் உரிமை, அறவிடப்படவேண்டிய தொகையினை
            பிணையாளர்களுக்கிடையில் சமமான முறையில் பகிர்ந்து அறவிடல் வேண்டும் எனக் கேட்டுக்
            கேட்டுக்கொள்ளும் உரிமை மற்றும் பிணையாளருக்கு எதிராக தொடரப்படும் வழக்கில் கடன் கொடுத்தவருக்கு
            செலுத்தப்பட வேண்டிய தொகையை பிணையாளர்களிடையில் சமமாக பகிர்ந்து அறவிடல் வேண்டுமென
            கேட்டுக்கொள்ளும் உரிமை போன்ற பிணையாளருக்கு சட்டப்படியோ அல்லது நியாயத்தின்படியோ
            உள்ள உரிமைகள் மற்றும் சலுகைகள் இதன்மூலம் வெளிப்படையாக கைவிடப்படுகின்றன.
        </p>
    </div>

    <div class="signature-section">
        <table class="signature-table" style="width: 100%;">
            @foreach ($loan->guarantors->chunk(2) as $pair)
                <tr>
                    @foreach ($pair as $guarantor)
                        <td style="width: 50%; text-align: left; vertical-align: bottom;">
                            <div style="font-size: 12px;">
                                <span class="guarantor-number" style="font-size: 12px;">
                                    {{ str_pad($loop->parent->index * 2 + $loop->index + 1, 2, '0', STR_PAD_LEFT) }} ஆம் பிணையாளரின்
                                </span>
                                <div style="margin-bottom: 8px;">
                                    <strong style="font-size: 12px;">பெயர் :</strong>
                                    <span class="loan-data" style="font-size: 12px; font-weight: bold;">{{ $guarantor->full_name }}</span>
                                </div>
                                <div>
                                    <strong style="font-size: 12px;">கையொப்பம் :</strong>
                                    <span class="signature-line"></span>
                                </div>
                            </div>
                        </td>
                    @endforeach
                    @if ($pair->count() === 1)
                        <td style="width: 50%;"></td>
                    @endif
                </tr>
            @endforeach
        </table>
    </div>

</body>

</html>
