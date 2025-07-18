<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>DNS Certificate</title>
    <style>
        @page { size: A4; margin: 0; }
        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            font-family: 'Arial', 'DejaVu Sans', sans-serif;
            background: #fff;
            overflow: hidden;
            box-sizing: border-box;
        }
        .certificate-border {
            border: 1.5px solid #222;
            margin: 10;
            padding: 10;
            box-sizing: border-box;
            overflow: hidden;
            background: #fff;
        }
         .container {
            box-sizing: border-box;
            width: 100%;
            overflow: hidden;
            font-size: 16px;
            margin-top:100px;
        }
        .header-table {
            width: 100%;
            table-layout: fixed;
            margin-bottom: 10px;
        }
        .header-table td {
            font-size: 14px;
            font-weight: bold;
            vertical-align: top;
            word-break: break-word;
            overflow-wrap: break-word;
        }
        .cert-no {
            text-align: right;
            font-size: 14px;
            font-weight: bold;
        }
        .candidate-details {
            margin-top: 10px;
            margin-bottom: 10px;
        }
        .candidate-details span {
            font-size: 14px;
        }
        .candidate-name {
            text-decoration: underline;
            font-weight: bold;
        }
        .photo-cell {
            width: 110px;
            text-align: left;
        }
        .photo {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border: 1.5px solid #333;
            margin-bottom: 2px;
            margin-left: 30px;
        }
        .trainee-label {
            text-align: left;
            font-size: 16px;
            margin-top: 2px;
        }
        .trainee-line {
            border-bottom: 1px solid #000;
            width: 50%;
            margin: 2px 0 2px 0;
            margin-top: 50px;
        }
        .highlight-yellow {
            font-weight: bold;
            padding: 0 2px;
        }
        .center-title {
            text-align: center;
            font-size: 15px;
            font-weight: bold;
            margin: 6px 0 2px 0;
        }
        .center-small {
            text-align: center;
            font-size: 14px;
            margin: 6px 0 2px 0;
        }
        .center-bold {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin: 0;
        }
        .center-bold-large {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin: 0;
        }
        .approval, .awareness, .stcw {
            font-size: 14px;
            margin-bottom: 10px;
            word-break: break-word;
            overflow-wrap: break-word;
        }
        .sigphoto-table {
            width: 100%;
            margin-top: 20px;
            font-size: 16px;
            table-layout: fixed;
        }
        .sigphoto-table td {
            vertical-align: top;
            padding: 0;
            word-break: break-word;
            overflow-wrap: break-word;
            overflow: hidden;
        }
        .sig-candidate {
            width: 40%;
            text-align: left;
        }
        .sig-block {
            text-align: right;
            margin-bottom: 30px;
            word-break: break-word;
            overflow-wrap: break-word;
        }
        .sig-label {
            font-size: 16px;
            margin-bottom: 20px;
        }
        .sig-line {
            border-bottom: 1px solid #000;
            width: 50%;
            margin: 2px 0 2px auto;
            margin-top:35px;
        }
        .sig-name {
            font-weight: bold;
            font-size: 16px;
        }
        .footer {
            margin-top: 8px;
            font-size: 14px;
            width: 100%;
            table-layout: fixed;
        }
        .footer td {
            padding: 0;
            word-break: break-word;
            overflow-wrap: break-word;
            overflow: hidden;
        }
        .footer .label {
            font-weight: bold;
        }
        .footer .highlight-yellow {
            font-weight: bold;
            padding: 0 2px;
        }
        .small { font-size: 14px; }
        .bold { font-weight: bold; }
        .right { text-align: right; }
        .left { text-align: left; }
        .center { text-align: center; }
        .underline { text-decoration: underline; }
    </style>
</head>
<body>
<div class="certificate-border">
    <div class="container">
        <table class="header-table">
            <tr>
                <td></td>
                <td class="cert-no">Certificate No.:<br><span class="bold">{{ $certificate_no }}</span></td>
            </tr>
        </table>
        <div class="candidate-details">
            <span>This is to certify that <span class="candidate-name">{{ $name }}</span></span>
        </div>
        <div class="candidate-details">
            <table class="candidate-details-table" style="width:100%; table-layout:fixed; margin-bottom:10px;">
                <tr>
                    <td class="left" style="width:33%; vertical-align:top;">
                        Date of Birth: <span class="bold">{{ $dob }}</span>
                    </td>
                    <td style="width:34%;"></td>
                    <td class="right" style="width:33%; vertical-align:top;">
                        Passport No. <span class="bold">{{ $passport_no }}</span>
                    </td>
                </tr>
                <tr>
                    <td class="left" style="width:33%; vertical-align:top;">
                        INDoS No.: <span class="bold">{{ $indos_no }}</span>
                    </td>
                    <td class="center" style="width:34%; vertical-align:top;">
                        MTI Roll No.: <span class="bold">{{ $roll_no }}</span>
                    </td>
                    <td class="right" style="width:33%; vertical-align:top;">
                        C.D.C. No. :
                        @if(empty($cdc_no))
                            <span class="highlight-yellow">-</span>
                        @else
                            <span class="highlight-yellow">{{ $cdc_no }}</span>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
        <div class="center-title">
            has successfully completed<br>
            One Year Pre-Sea Deck Cadet Course
            <br>
            <span class="center-bold">from {{ $start_date }} to {{ $end_date }}</span>
            <br>
            <span class="center-bold-large">for award of<br>DIPLOMA IN NAUTICAL SCIENCE</span>
            <br>
            <span class="center-bold">leading to B.Sc. (Applied Nautical Science) Degree</span>
        </div>
        <div class="center-small">
            This course is approved by the Directorate General of Shipping, Government of India<br>and by<br>The Indian Maritime University, Government of India
        </div>
        <div class="awareness">
            The holder of this certificate has also undergone awareness training on relevant provision of Merchant Shipping Act, 1958, Articles of Agreements, Recruitment and Placement Rules and safety and security training in accordance with regulations VI/1 and VI/6 of International Convention on Standards of Training, Certification and Watchkeeping (STCW) for seafarers 1978, as amended.
        </div>
        <div class="stcw">
            “The candidate has also successfully completed gender sensitization training during this course.”
        </div>
        <table class="sigphoto-table">
            <tr>
               <td class="photo-cell">
                    @if(isset($photo_path) && $photo_path)
                        <img  src="{{ $photo_path }}" class="photo" alt="Trainee Photo">
                    @else
                        <div class="photo"></div>
                    @endif
                    <div class="trainee-line"></div>
                    <div class="trainee-label">Signature of Trainee</div>
                </td>
                <td>
                    <div class="sig-block">
                        <div class="sig-label">Signature of Course In-Charge</div>
                        <div class="sig-line"></div>
                        <div class="sig-name">Capt. Narendra Kumar</div>
                        <div class="small">INDoS No. 00NL6230</div>
                    </div>
                    <div class="sig-block">
                        <div class="sig-label">Signature of Principal</div>
                        <div class="sig-line"></div>
                        <div class="sig-name">Mr. Rajeeva Prakash</div>
                        <div class="small">INDoS No. 01EL4681</div>
                    </div>
                </td>
            </tr>
        </table>
        <table class="footer">
            <tr>
                <td class="left">Date of Issue: <span class="highlight-yellow">{{ $issue_date }}</span></td>
                <td class="center">Place of issue: <span class="bold">{{ $place ?? 'Kansal' }}</span></td>
                <td class="right">Date of Expiry: <span class="bold">Unlimited</span></td>
            </tr>
        </table>
        
    </div>
</div>

</body>
</html> 