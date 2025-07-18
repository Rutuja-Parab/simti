<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 14px;
            margin: 0;
            padding: 0;
            /* DomPDF background */
            background-image: url('{{ public_path('templates/background.pdf') }}');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }

        h2,
        h4 {
            text-align: center;
            margin: 0;
        }

        .info p {
            margin: 4px 0;
        }

        .footer {
            margin-top: 40px;
        }

        .footer p {
            margin: 2px 0;
        }

        .signature {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>

<body>
    <img src="{{ public_path('watermark.png') }}"
         style="position: fixed; top: 45%; left: 45%; width: 100%; opacity: 0.5; transform: translate(-50%, -50%); z-index: 0;" />
    <div style="position: relative; z-index: 1;">
    <h2 style="color: red;"><u>ELECTRO-TECHNICAL OFFICER COURSE</u></h2>
    <h4><u>“SUBJECT WISE FINAL MARK SHEET”</u></h4>

    <table style="width: 100%; margin-top: 20px; border: none; border-collapse: collapse;">
        <tr>
            <td style="text-align: left; border: none;"><strong>ENTRY:</strong> <u>1<sup>st</sup> March 2025</u></td>
            <td style="text-align: right; border: none;"><strong>BATCH:</strong>
                <u>{{ \Illuminate\Support\Str::beforeLast($candidate->roll_no, '-') }}</u></td>
        </tr>
        <tr>
            <td style="text-align: left; border: none;"><strong>NAME:</strong> <u>{{ $candidate->name }}</u></td>
            <td style="border: none;"></td>
        </tr>
        <tr>
            <td style="text-align: left; border: none;"><strong>INDOS NO:</strong> <u>{{ $candidate->indos_no }}</u></td>
            <td style="text-align: right; border: none;"><strong>ROLL NO:</strong> <u>{{ $candidate->roll_no }}</u></td>
        </tr>
    </table>


    <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
        <thead>
            <tr>
                <th style="width: 5%;">Sr No.</th>
                <th style="width: 45%; font-size: 16px;">Name of the Subject</th>
                <th style="width: 16.66%;">Maximum Marks</th>
                <th style="width: 16.66%;">Passing Marks</th>
                <th style="width: 16.66%;">Marks Obtained</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($subjects as $i => $subj)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td style="font-size: 15px;">{{ $subj[1] }}</td>
                    <td>{{ $subj[2] }}</td>
                    <td>{{ $subj[3] }}</td>
                    <td>{{ $marks[$i]->marks_obtained ?? 'N/A' }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="2" style="text-align: right;"><strong>TOTAL MARKS</strong></td>
                <td>{{ $total }}</td>
                <td>{{ array_sum(array_column($subjects, 2)) }}</td>
                <td>{{ $obtained }}</td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: right;"><strong>OVERALL PERCENTAGE</strong></td>
                <td colspan="3"><b>{{ $percentage }}%</b></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: right;"><strong>RESULT</strong></td>
                <td colspan="3"><b>{{ $result }}</b></td>
            </tr>
        </tbody>
    </table>


    <table style="width: 100%; margin-top: 60px; border: none;">
        <tr>
            <td style="width: 50%; text-align: left;border: none;">
                <p><u><strong>Signature of Course In-Charge</strong></u><br>
                    <strong>Mr. Prodyot Basu</strong>
                </p>
            </td>
            <td style="width: 50%; text-align: center;border: none;">
                <p><u><strong>Signature of Principal</strong></u><br>
                    <strong>Mr. Rajeeva Prakash</strong>
                </p>
            </td>
        </tr>
    </table>

    <table style="width: 100%; margin-top: 30px; border: none;">
        <tr>
            @php
                use Carbon\Carbon;
                $todayFormatted = Carbon::now()->format('jS F Y'); // e.g. 10th July 2025
            @endphp

            <td style="text-align: left; border: none;">
                <p><strong>Date of Issue:</strong> {{ $todayFormatted }}</p>
            </td>
            <td style="text-align: center;border: none;">
                <p><strong>Place of Issue: Kansal</strong></p>
            </td>
        </tr>
    </table>
    </div>
</body>

</html>
