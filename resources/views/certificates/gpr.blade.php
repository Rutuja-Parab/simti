<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>GPR Certificate</title>
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
            margin-bottom: 20px;
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
        .cert-no-underline {
            display: inline-block;
            border-bottom: 1px solid #000;
            width: 260px;
            vertical-align: middle;
            margin-left: 8px;
        }
        .trainee-line {
            border-bottom: 1px solid #000;
            width: 70%;
            margin: 2px 0 2px 0;
            margin-top: 50px;
        }
        .course-title {
            text-align: center;
            color: #0070c0;
            font-size: 15px;
            font-weight: bold;
            margin-bottom: 20px;
            margin-top: 2px;
            letter-spacing: 1px;
        }
        .section {
            margin-bottom: 4px;
            font-size: 14px;
            word-break: break-word;
            overflow-wrap: break-word;
            line-height: 1.8;
        }
        .candidate-name {
            text-decoration: underline;
            font-weight: bold;
        }
        .details-bold {
            font-weight: bold;
        }
        .date-range {
            font-weight: bold;
            color: #0070c0;
        }
        .photo-sig-table {
            width: 100%;
            margin-top: 18px;
            font-size: 14px;
            table-layout: fixed;
        }
        .photo-sig-table td {
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
        .sig-label {
            font-size: 16px;
            margin-bottom: 20px;
        }
        .sig-line {
            border-bottom: 1px solid #000;
            width: 70%;
            margin: 2px 0 2px auto;
            margin-top:50px;
        }
        .sig-name {
            font-weight: bold;
            font-size: 16px;
        }
        .footer-table {
            width: 100%;
            margin-top: 10px;
            font-size: 14px;
            table-layout: fixed;
        }
        .footer-table td {
            padding: 0;
            word-break: break-word;
            overflow-wrap: break-word;
            overflow: hidden;
        }
        .footer .label {
            font-weight: bold;
        }
        .footer-note {
            font-size: 14px;
            margin-top: 10px;
            color: #222;
        }
        .footer-note-italic {
            font-size: 14px;
            font-style: italic;
            color: #222;
        }
        .center { text-align: center; margin-top: 50px; }
        .right { text-align: right; }
        .bold { font-weight: bold; }
        .underline { text-decoration: underline; }
    </style>
</head>
<body>
<div class="certificate-border">
    <div class="container">
        <table class="header-table">
            <tr>
                <td></td>
                <td class="cert-no">Certificate No.: <span class="bold">{{ $certificate_no }}</span><</td>
            </tr>
        </table>
        <div class="course-title">PRE SEA TRAINING COURSE<br>FOR GENERAL PURPOSE (GP) RATINGS</div>
        <div class="section">
            This is to certify that <span class="candidate-name">{{ $name }}</span> &nbsp;&nbsp; Roll No.<span class="details-bold">{{ $roll_no }}</span>
        </div>
        <div class="section">
            D.O.B. <span class="details-bold">{{ $dob }}</span>, &nbsp; INDoS No. <span class="details-bold">{{ $indos_no }}</span>, &nbsp; Passport No. <span class="details-bold">{{ $passport_no }}</span>
            has successfully <br> completed a Pre Sea Training Course for Rating forming part of the navigational and Engineering Watch from <span class="date-range">{{ $start_date }} to {{ $end_date }}</span>. This course is an integral part of the overall planned and structured training programme for the prospective Rating of a Sea going Ship of 500 gross tonnage or more and is designed to assist him in achieving the minimum standards of competence as specified in Regulation II/4, II/5 and III/4, III/5 of STCW convention as amended in 2010. This training programme was conducted in English language and is approved by the Directorate General of Shipping, Ministry of Shipping, Government of India.
        </div>
        <div class="section">
            “The candidate has also successfully completed gender sensitization training during this course”.
        </div>
        <table class="photo-sig-table">
            <tr>
                <td class="photo-cell">
                    @if(isset($photo_path) && $photo_path)
                        <img src="{{ $photo_path }}" class="photo" alt="Trainee Photo">
                    @else
                        <div class="photo"></div>
                    @endif
                    <div class="trainee-line"></div>
                    <div class="sig-label">Rating's Signature</div>
                </td>
                
                <td class="right">
                    <div class="sig-label">Signature of Principal</div>
                    <div class="sig-line"></div>
                    <div class="sig-name">Mr. Rajeeva Prakash</div>
                    <div class="footer-note">INDoS No. 01EL4681</div>
                </td>
            </tr>
             <tr>
                <td colspan="3" class="right">
                    <div class="sig-label bold underline" style="margin-bottom:2px;">{{ $issue_date }}</div>
                    <div class="sig-label">Date of Issue</div>
                </td>
            </tr>
        </table>
        <div class="footer-note-italic">*Indian National Database of Seafarers</div>
        <div class="footer-note">All enquiries concerning the certificate should be addressed to the issuing authority above.</div>
    </div>
</div>
</body>
</html> 