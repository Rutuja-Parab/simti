<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>GME Certificate</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }

        html,
        body {
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

        .cert-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 10px;
            margin-top: 2px;
        }

        .section {
            margin-bottom: 10px;
            font-size: 14px;
            word-break: break-word;
            overflow-wrap: break-word;
        }

        .candidate-name {
            font-weight: bold;
        }

        .label {
            font-weight: bold;
        }

        .photo-sig-table {
            width: 100%;
            margin-top: 20px;
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

        .trainee-label {
            text-align: left;
            font-size: 14px;
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
            font-size: 14px;
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
            font-size: 14px;
        }

        .footer {
            margin-top: 20x;
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

        .small {
            font-size: 14px;
        }

        .bold {
            font-weight: bold;
        }

        .right {
            text-align: right;
        }

        .left {
            text-align: left;
        }

        .center {
            text-align: center;
        }

        .underline {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="certificate-border">
        <div class="container">
            <table class="header-table">
                <tr>
                    <td class="left">SIMTI Roll No.: <span class="bold">{{ $roll_no }}</span></td>
                    <td class="right">Certificate No.:<span class="bold">{{ $certificate_no }}</span></td>
                </tr>
            </table>
            <div class="cert-title">One Year Pre – Sea Training for Graduate Marine Engineers course (GME)</div>
            <div class="section">
                This is to certify that <span class="candidate-name">{{ $name }}</span> &nbsp;&nbsp; INDoS No.
                <span class="bold">{{ $indos_no }}</span>
            </div>
            <div class="section">
                Date of Birth: <span class="bold">{{ $dob }}</span> &nbsp;&nbsp; C.D.C. No.: <span
                    class="bold">{{ $cdc_no }}</span>
            </div>
            <div class="section">
                GME/TAK Book Serial No. GME/04/2021 GME-04-01. In compliance with the Online circular No. 10069, dated
                28-JUN-2023 from the Directorate General of Shipping, Govt of India, and Training Circular No. 23 of
                2014, & as per IMU/Maritime PART “A” norms, the candidate has completed his pre-sea training at Seven
                Islands Maritime Training Institute, successfully during 01-Aug-2025 to 31-Aug-2025 as member of the
                following “One Year GME” training batch. He has been imparted training at Seven Islands Maritime
                Training Institute, under existing rules, in Part A, Part A-VI and 2.5 months of on-board training in
                Government/Non-Government Shipping Companies, as per the rules of Semester I, II, and III of the “One
                Year GME” Training Programme.
            </div>
            <div class="section">
                The course has been approved by the Directorate General of Shipping, India and covers the requirements
                of the STCW 1978 Convention, as amended. The training also includes the relevant provisions of the
                Merchant Shipping Act, Recruitment & Placement Rules, which also fulfill the requirements as laid down
                in Table A-III/6 of the STCW 1978 as amended.
            </div>
            <div class="section">
                The candidate has undergone Mandatory Courses as per STCW 1978 as amended, including Proficiency in
                Survival Techniques, Fire Prevention and Fire Fighting, Elementary First Aid, Personal Safety and Social
                Responsibilities, Security Training for Seafarers with Designated Duties, and Awareness of Articles of
                Agreement and Safety Training, as per the rules of the Merchant Shipping Act, 1958 (as amended) and
                DGS/IMU/Maritime University guidelines.
            </div>
            <div class="section">
                “The candidate has also successfully completed gender sensitization training during this course.”
            </div>
            <table class="photo-sig-table">
                <tr>
                    <td class="photo-cell">
                        @if (isset($photo_path) && $photo_path)
                            <img src="{{ $photo_path }}" class="photo" alt="Trainee Photo">
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
                            <div class="sig-name">Mr. Manish Keskar</div>
                            <div class="small">INDoS No. 99EL1928</div>
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
                    <td class="left">Date of Issue: <span class="bold">{{ $issue_date }}</span></td>
                    <td class="right">Place of issue: <span class="bold">{{ $place ?? 'Kansal' }}</span></td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>
