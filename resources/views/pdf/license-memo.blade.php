<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>License Memo</title>
    <style>
        @page {
            margin: 38px 48px 42px 48px;
        }

        body {
            font-family: Georgia, serif;
            font-size: 12px;
            color: #000;
            line-height: 1.45;
            margin: 0;
            padding: 0;
        }

        .page {
            width: 100%;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        .header-table td {
            vertical-align: top;
        }

        .logo-cell {
            width: 95px;
            text-align: left;
            padding-top: 2px;
        }

        .logo {
            width: 78px;
            height: auto;
            display: block;
        }

        .header-text {
            text-align: center;
            padding-right: 70px;
        }

        .company-name {
            font-family: Arial, sans-serif;
            font-size: 20px;
            font-weight: bold;
            margin: 0 0 4px 0;
            line-height: 1.1;
        }

        .company-address {
            font-family: Arial, sans-serif;
            font-size: 14px;
            font-weight: normal;
            margin: 0 0 6px 0;
            line-height: 1.2;
        }

        .tel-line {
            font-family: Arial, sans-serif;
            font-size: 10px;
            font-weight: normal;
            margin: 0 0 4px 0;
            line-height: 1.2;
        }

        .email-line,
        .mobile-line {
            font-family: Calibri, Arial, sans-serif;
            font-size: 11px;
            font-weight: normal;
            margin: 0 0 4px 0;
            line-height: 1.2;
        }

        .memo-title {
            text-align: center;
            font-family: Georgia, serif;
            font-size: 18px;
            font-weight: bold;
            margin-top: 26px;
            margin-bottom: 28px;
            letter-spacing: 0.5px;
        }

        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 22px;
        }

        .meta-table td {
            padding: 2px 0;
            vertical-align: top;
            font-size: 12px;
            font-family: Georgia, serif;
        }

        .meta-label {
            width: 92px;
        }

        .meta-colon {
            width: 12px;
        }

        .meta-value {
            width: auto;
        }

        .paragraph {
            text-align: justify;
            margin: 0 0 14px 0;
            font-family: Georgia, serif;
            font-size: 12px;
            line-height: 1.55;
        }

        .closing {
            margin-top: 16px;
            margin-bottom: 44px;
            font-family: Georgia, serif;
            font-size: 12px;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 42px;
        }

        .signature-table td {
            width: 50%;
            vertical-align: top;
            font-family: Georgia, serif;
            font-size: 12px;
        }

        .sig-label {
            padding-bottom: 42px;
        }

        .sig-name {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="page">
        <table class="header-table">
            <tr>
                <td class="logo-cell">
                    @if(!empty($logoPath) && file_exists($logoPath))
                        <img src="{{ $logoPath }}" class="logo" alt="Company Logo">
                    @endif
                </td>

                <td class="header-text">
                    <p class="company-name">Perseus Safety and Security Agency</p>
                    <p class="company-address">Landing, Catarman, Liloan, Cebu, Philippines, 6002</p>
                    <p class="tel-line">Tel. No. : (032)424-3098/(032)512-2210 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Fax No.: (032)236-0988</p>
                    <p class="email-line">Email: perseus1288@yahoo.com &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; perseusagency1288@gmail.com</p>
                    <p class="mobile-line">Mobile No. 09988604573/09255023052/09369146131</p>
                </td>
            </tr>
        </table>

        <div class="memo-title">MEMORANDUM</div>

        <table class="meta-table">
            <tr>
                <td class="meta-label">Attention</td>
                <td class="meta-colon">:</td>
                <td class="meta-value">SG {{ $guard->full_name }}</td>
            </tr>
            <tr>
                <td class="meta-label">Subject</td>
                <td class="meta-colon">:</td>
                <td class="meta-value">Security Guard License Compliance</td>
            </tr>
            <tr>
                <td class="meta-label">Date</td>
                <td class="meta-colon">:</td>
                <td class="meta-value">{{ $date }}</td>
            </tr>
        </table>

        <p class="paragraph">
            We would like to remind you that your security license is due to expire on
            {{ $expiration_date }}. As part of our commitment to maintaining compliance
            with regulatory requirements and ensuring the highest standards of safety and
            professionalism, we encourage you to begin the renewal process as soon as possible.
        </p>

        <p class="paragraph">
            Please ensure that you complete all necessary requirements for license renewal,
            including submission of required documents, completion of any mandated training, and
            payment of applicable fees. Kindly provide a copy of your renewed license to the Human
            Resources Department once it has been processed.
        </p>

        <p class="paragraph">
            If you need any assistance or documentation from the company to support your renewal
            application, please do not hesitate to contact us.
        </p>

        <p class="paragraph">
            Thank you for your continued dedication and service.
        </p>

        <p class="closing">For guidance and strict compliance.</p>

        <table class="signature-table">
            <tr>
                <td class="sig-label">Prepared by:</td>
                <td class="sig-label">Noted by:</td>
            </tr>
            <tr>
                <td>
                    <div class="sig-name">JESSA L. PILAR, RCRIM</div>
                    <div>HR Officer</div>
                </td>
                <td>
                    <div class="sig-name">ROGER L. DECORION, CSP</div>
                    <div>Operations Manager</div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>