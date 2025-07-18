<!DOCTYPE html>
<html>
<head>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            width: 210mm;
            min-height: 297mm;
            box-sizing: border-box;
            font-family: 'Times New Roman', Times, serif;
            background: #fff;
        }
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            width: 80%;
            opacity: 0.5;
            transform: translate(-50%, -50%);
            z-index: 0;
        }
        .content {
            position: relative;
            z-index: 1;
            padding: 30px 30px 0 30px;
        }
        h2 {
            color: #d32f2f;
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 2px;
            font-size: 20px;
        }
        h4 {
            color: #1a237e;
            text-align: center;
            text-transform: uppercase;
            margin-top: 0;
            margin-bottom: 12px;
            font-size: 16px;
            text-decoration: underline;
        }
        .info-table {
            width: 100%;
            margin-bottom: 10px;
            font-size: 12px;
        }
        .info-table td {
            border: none;
            padding: 2px 0;
        }
        .marks-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0 auto 10px auto;
            font-size: 12px;
        }
        .marks-table th, .marks-table td {
            border: 1px solid #000;
            padding: 3px 5px;
            text-align: center;
        }
        .marks-table th {
            font-size: 13px;
            font-weight: bold;
        }
        .footer-row td {
            border: none;
            font-size: 12px;
            padding-top: 8px;
        }
        .result-row td {
            border: none;
            font-size: 13px;
            font-weight: bold;
            padding-top: 4px;
        }
        .signatures {
            width: 100%;
            margin-top: 30px;
        }
        .signatures td {
            border: none;
            text-align: center;
            font-size: 13px;
        }
        .sign-label {
            text-decoration: underline;
            font-weight: bold;
        }
        .sign-name {
            font-weight: bold;
            margin-top: 2px;
        }
        .bottom-info {
            width: 100%;
            margin-top: 18px;
            font-size: 12px;
        }
        .bottom-info td {
            border: none;
            padding: 0;
        }
    </style>
</head>
<body>
    <img src="{{ public_path('watermark.png') }}" class="watermark" />
    <div class="content">
        <h2>GRADUATE MARINE ENGINEER</h2>
        <h4>"SUBJECT WISE FINAL MARK LIST"</h4>
        <table class="info-table">
            <tr>
                <td style="text-align: left;"><strong>ENTRY:</strong> <u>{{ $entry_date ?? '1st March 2025' }}</u></td>
                <td style="text-align: right;"><strong>BATCH:</strong> <u>{{ $batch ?? (isset($candidate) ? \Illuminate\Support\Str::beforeLast($candidate->roll_no, '-') : '') }}</u></td>
            </tr>
            <tr>
                <td style="text-align: left;"><strong>NAME:</strong> <u>{{ $candidate->name ?? '' }}</u></td>
                <td style="text-align: right;"><strong>ROLL NO:</strong> <u>{{ $candidate->roll_no ?? '' }}</u></td>
            </tr>
            <tr>
                <td style="text-align: left;"><strong>INDOS NO:</strong> <u>{{ $candidate->indos_no ?? '' }}</u></td>
            </tr>
        </table>
        <table class="marks-table">
            <thead>
                <tr>
                    <th style="width: 10%;">Subject Code</th>
                    <th style="width: 38%;">Name of the Subject</th>
                    <th style="width: 10%;">Max Marks</th>
                    <th style="width: 10%;">Min Passing Marks</th>
                    <th style="width: 10%;">Term-1</th>
                    <th style="width: 10%;">Term-2</th>
                    <th style="width: 12%;">Total Marks Secured</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $grandTotal = 0;
                    $grandObtained = 0;
                    $grandTerm1 = 0;
                    $grandTerm2 = 0;
                @endphp
                @foreach ($subjects as $i => $subj)
                    @php
                        $subjectCode = $subj[0];
                        $subjectName = $subj[1];
                        $maxMarks = $subj[2];
                        $passingMarks = $subj[3];
                        $term1 = isset($marks[$i]->term1) ? $marks[$i]->term1 : 0;
                        $term2 = isset($marks[$i]->term2) ? $marks[$i]->term2 : 0;
                        $totalObtained = ($term1 ?? 0) + ($term2 ?? 0);
                        $grandTotal += $maxMarks;
                        $grandObtained += $totalObtained;
                        $grandTerm1 += $term1;
                        $grandTerm2 += $term2;
                    @endphp
                    <tr>
                        <td>{{ $subjectCode }}</td>
                        <td style="text-align: left;">{{ $subjectName }}</td>
                        <td>{{ $maxMarks }}</td>
                        <td>{{ $passingMarks }}</td>
                        <td>{{ $term1 }}</td>
                        <td>{{ $term2 }}</td>
                        <td>{{ $totalObtained }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="2" style="text-align: right;"><strong>TOTAL</strong></td>
                    <td><strong>{{ $grandTotal }}</strong></td>
                    <td></td>
                    <td><strong>{{ $grandTerm1 }}</strong></td>
                    <td><strong>{{ $grandTerm2 }}</strong></td>
                    <td><strong>{{ $grandObtained }}</strong></td>
                </tr>
                <tr class="result-row">
                    <td colspan="3" style="text-align: left;">PERCENTAGE = <b>{{ $percentage }}%</b></td>
                    <td colspan="4" style="text-align: right;">RESULT = <b>{{ $result }}</b></td>
                </tr>
            </tbody>
        </table>
        <table class="signatures">
            <tr>
                <td style="text-align: left;">
                    <span class="sign-label">(MANISH KESKAR)</span><br>
                    <span class="sign-name">Signature of Course In-Charge</span>
                </td>
                <td style="text-align: right;">
                    <span class="sign-label">(RAJEEVA PRAKASH)</span><br>
                    <span class="sign-name">Signature of Principal</span>
                </td>
            </tr>
        </table>
        <table class="bottom-info">
            <tr>
                @php
                    use Carbon\Carbon;
                    $todayFormatted = Carbon::now()->format('jS F Y');
                @endphp
                <td style="text-align: left;">
                    Date of Issue : <b>{{ $todayFormatted }}</b>
                </td>
                <td style="text-align: right;">
                    Place of Issue : <b>Kansal</b>
                </td>
            </tr>
        </table>
    </div>
</body>
</html> 