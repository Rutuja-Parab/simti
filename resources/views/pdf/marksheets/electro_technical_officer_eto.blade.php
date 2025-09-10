<!DOCTYPE html>
<html>

<head>
    <style>
        @page {
            margin: 0cm 0cm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            background-image: url('{{ public_path('bg.jpg') }}');
            background-repeat: no-repeat;
            background-position: center center;
            background-size: cover;
            width: 100%;
            font-weight: bold;
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
    <div style="width: 80%; margin: 0 auto; position: relative; z-index: 1; padding: 0;margin-top: 25%;">
        <h2 style="color: red;"><u>ELECTRO-TECHNICAL OFFICER COURSE</u></h2>
        <h4><u>“SUBJECT WISE FINAL MARK SHEET”</u></h4>

        <table style="width: 100%; margin-top: 20px; border: none; border-collapse: collapse;">
            <tr>
                <td style="text-align: left; border: none;"><strong>ENTRY:</strong> <u>1<sup>st</sup> March 2025</u></td>
                <td style="text-align: right; border: none;"><strong>BATCH:</strong>
                    <u>{{ \Illuminate\Support\Str::beforeLast($candidate->roll_no, '-') }}</u>
                </td>
            </tr>
            <tr>
                <td style="text-align: left; border: none;"><strong>NAME:</strong> <u>{{ $candidate->name }}</u></td>
                <td style="border: none;"></td>
            </tr>
            <tr>
                <td style="text-align: left; border: none;"><strong>INDOS NO:</strong> <u>{{ $candidate->indos_no }}</u>
                </td>
                <td style="text-align: right; border: none;"><strong>ROLL NO:</strong> <u>{{ $candidate->roll_no }}</u>
                </td>
            </tr>
        </table>


        <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
            <thead>
                <tr>
                    <th style="width: 5%;">Sr No.</th>
                    <th style="width: 45%; font-size: 12px;text-align: left;">Name of the Subject</th>
                    <th style="width: 16.66%;">Maximum Marks</th>
                    <th style="width: 16.66%;">Passing Marks</th>
                    <th style="width: 16.66%;">Marks Obtained</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($subjects as $i => $subj)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td style="font-size: 12px;text-align: left;">{{ $subj[1] }}</td>
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

        <table style="width: 100%; margin-top: 30px; border: none;">
            <tr>
                <td style="width: 50%; text-align: left;border: none;">
                    <p><u><strong>Signature of Course In-Charge</strong></u><br>
                        <strong>Mr. Prodyot Basu</strong>
                    </p>
                </td>
                <td style="width: 50%; text-align: right;border: none;">
                    <p><u><strong>Signature of Principal</strong></u><br>
                        <strong>Mr. Rajeeva Prakash</strong>
                    </p>
                </td>
            </tr>
            <tr>
                @php
                    use Carbon\Carbon;
                    $todayFormatted = Carbon::now()->format('jS F Y'); // e.g. 10th July 2025
                @endphp

                <td style="text-align: left; border: none;">
                    <p><strong>Date of Issue:</strong> {{ $todayFormatted }}</p>
                </td>
                <td style="text-align: right;border: none;">
                    <p><strong>Place of Issue: Kansal</strong></p>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
