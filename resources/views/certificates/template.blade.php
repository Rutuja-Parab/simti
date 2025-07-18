<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>DNS Certificate</title>
    <style>
        @page { margin: 0cm; size: A4; }
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            width: 100%;
            font-family: 'DejaVu Sans', sans-serif;
            background-image: url('data:image/png;base64,{{ $background_image }}');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            overflow: hidden;
        }
        .content {
            padding: 80px 50px;
            box-sizing: border-box;
            font-size: 14px;
            line-height: 1.4;
            height: 100%;
        }
        .title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 20px;
        }
        .section { margin-bottom: 14px; }
        .signature-block {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            font-size: 12px;
        }
        .signature {
            text-align: center;
            width: 32%;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
        }
        .bold { font-weight: bold; }
        .underline { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="content">
        <div class="title">Certificate No.: <span class="bold">{{ $certificate_no }}</span></div>

        <div class="section">
            This is to certify that <span class="bold underline">{{ $name }}</span><br>
            Date of Birth: <span class="bold">{{ $dob }}</span><br>
            Passport No.: <span class="bold">{{ $passport_no }}</span><br>
            INDoS No.: <span class="bold">{{ $indos_no }}</span> &nbsp;&nbsp;
            MTI Roll No.: <span class="bold">{{ $roll_no }}</span><br>
            C.D.C. No.: <span class="bold">{{ $cdc_no }}</span>
        </div>

        <div class="section">
            has successfully completed<br>
            <span class="bold">{{ $course_name }}</span><br>
            from <span class="bold">{{ $start_date }}</span> to <span class="bold">{{ $end_date }}</span><br>
            for award of<br>
            <span class="bold">{{ $certificate_title }}</span><br>
            leading to <span class="bold">B.Sc. (Applied Nautical Science)</span> Degree
        </div>

        <div class="section">
            This course is approved by the <span class="bold">Directorate General of Shipping, Government of India</span><br>
            and by <span class="bold">The Indian Maritime University, Government of India</span>
        </div>

        <div class="section">
            The holder of this certificate has also undergone awareness training on relevant provisions of the <span class="bold">Merchant Shipping Act, 1958</span>, Articles of Agreements, Recruitment and Placement Rules and safety and security training in accordance with regulations <span class="bold">VI/1</span> and <span class="bold">VI/6</span> of the <span class="bold">International Convention on STCW</span> for seafarers 1978, as amended.
        </div>

        <div class="signature-block">
            <div class="signature">
                Signature of Course In-charge<br>
                <b>Capt. Narendra Kumar</b><br>
                INDoS No. 00NL6230
            </div>
            <div class="signature">
                Signature of Principal<br>
                <b>Mr. Rajeeva Prakash</b><br>
                INDoS No. 01EL4681
            </div>
            <div class="signature">
                Signature of Candidate
            </div>
        </div>

        <div class="footer">
            Date of Issue: <span class="bold">{{ $issue_date }}</span><br>
            Place of Issue: <span class="bold">{{ $place }}</span><br>
            Date of Expiry: <span class="bold">Unlimited</span>
        </div>
    </div>
</body>
</html>
