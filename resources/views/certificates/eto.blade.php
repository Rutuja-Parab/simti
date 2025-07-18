<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>TRAINING CERTIFICATE</title>
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
        }
        .header {
            text-align: center;
            color: #0070c0;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
            letter-spacing: 1px;
            text-transform: uppercase;
            word-break: break-word;
            overflow-wrap: break-word;
        }
        .row-table {
            width: 100%;
            font-size: 16px;
            margin-bottom: 15px;
            table-layout: fixed;
        }
        .row-table td {
            padding: 0;
            word-break: break-word;
            overflow-wrap: break-word;
            overflow: hidden;
        }
        .label {
            font-weight: bold;
        }
        .section {
            margin-bottom: 20px;
            font-size: 16px;
            word-break: break-word;
            overflow-wrap: break-word;
        }
        .candidate-name {
            font-weight: bold;
            text-decoration: underline;
        }
        .course-title {
            background:rgb(255, 255, 255);
            border: 1.5px solid rgb(0, 0, 0);
            color: #FF0000;
            font-weight: bold;
            text-align: center;
            font-size: 24px;
            margin: 2px auto 2px auto;
            padding: 1px 0;
            letter-spacing: 1px;
            word-break: break-word;
            overflow-wrap: break-word;
            width: 80%;
            margin-bottom: 10px;
        }
        .approval, .note, .authority {
            font-size: 16px;
            margin-bottom: 20px;
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
            margin-top: 20px;
            font-size: 16px;
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
        .small { font-size: 16px; }
        .bold { font-weight: bold; }
        .right { text-align: right; }
        .left { text-align: left; }
        .center { text-align: center; }
    </style>
</head>
<body>
<div class="certificate-border">
    <div class="container" style="margin-top:100px;">
        <div class="header">TRAINING CERTIFICATE</div>
        <table class="row-table">
            <tr>
                <td class="left"><span class="label">Student ID:</span> <span class="bold">{{ $roll_no }}</span></td>
                <td class="right"><span class="label">Certificate No:</span> <span class="bold">{{ $certificate_no }}</span></td>
            </tr>
        </table>
        <div class="section">
            This is to certify that <span class="candidate-name">{{ $name }}</span>
        </div>
        <table class="row-table">
            <tr>
                <td class="left">Date of Birth: <span class="bold">{{ $dob }}</span></td>
                <td class="right">Holder of C.D.C. No: <span class="bold">{{ $cdc_no }}</span></td>
            </tr>
        </table>
        <table class="row-table">
            <tr>
                <td class="left">Passport No: <span class="bold">{{ $passport_no }}</span></td>
                <td class="right">INDOS No: <span class="bold">{{ $indos_no }}</span></td>
            </tr>
        </table>
        <div class="section">
            has successfully completed a training course in
        </div>
        <div class="course-title">ELECTRO-TECHNICAL OFFICERS</div>
        <div class="section">
            held from <span class="bold">{{ $start_date }}</span> to <span class="bold">{{ $end_date }}</span> at {{ $institute_name ?? 'Seven Islands Maritime Training Institute' }}.
        </div>
        <div class="approval">
            This course is approved by Director General of Shipping, INDIA and meets the requirements laid down in Section A-III/6 and table A-III/6 as per STCW Convention 1978 as amended.
        </div>
        <div class="note">
            "The candidate has also successfully completed gender sensitization training during this course."
        </div>
        <div class="authority">
            This certificate is issued under authority of Director General of Shipping, ministry of Surface Transport, Government of INDIA.
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
                        <div class="sig-label">Signature of Course In- Charge</div>
                        <div class="sig-line"></div>
                        <div class="sig-name">Mr. Prodyut Basu</div>
                        <div class="small">INDoS No. 99EL3235</div>
                    </div>
                    <div class="sig-block">
                        <div class="sig-label">Signatures of Principal</div>
                        <div class="sig-line"></div>
                        <div class="sig-name">Mr. Rajeeva Prakash</div>
                        <div class="small">INDoS No. 01EL4681</div>
                    </div>
                </td>
            </tr>
        </table>
        <table class="footer">
            <tr>
                <td class="left">Date of Issue: <span class="bold">{{ $issue_date }}</span></td>
                <td class="right">Place of issue: <span class="bold">{{ $place ?? 'Kansal' }}</span></td>
            </tr>
        </table>
    </div>
</div>
</body>
</html> 