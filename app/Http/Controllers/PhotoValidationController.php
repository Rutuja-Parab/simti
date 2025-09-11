<?php

namespace App\Http\Controllers;

use App\Models\Candidates;
use App\Models\Course;
use App\Services\CertificateService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Models\CourseDetail;
use App\Models\User;
use App\Notifications\CandidateAddedNotification;
use Illuminate\Support\Facades\Notification;

class PhotoValidationController extends Controller
{

    public function index()
    {
        return view('index');
    }
    public function candidate()
    {
        $user = auth()->user();
        if ($user->role === 'faculty') {
            $subjectIds = $user->subjects()->pluck('subjects.id')->toArray();
            $courseDetailIds = \App\Models\CourseDetail::whereHas('course.subjects', function ($query) use ($subjectIds) {
                $query->whereIn('subjects.id', $subjectIds);
            })->pluck('id');
            $candidate = Candidates::with('courseDetail.course')
                ->whereIn('course_detail_id', $courseDetailIds)
                ->get();
        } else {
            $candidate = Candidates::with('courseDetail.course')->get();
        }
        return view('candidate', ['candidate' => $candidate]);
    }
    public function addCandidate()
    {
        $user = auth()->user();

        $courseDetails = \App\Models\CourseDetail::with('course')->orderBy('id', 'desc')->get();
        return view('upload', compact('courseDetails'));
    }



    public function upload(Request $request)
    {
        $request->validate([
            'photo' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            // 'type' => 'required|in:photo,signature,aadhaar,passport',
            'type' => 'required|in:photo,signature,passport',
        ]);

        $type = $request->input('type');
        $file = $request->file('photo');
        $ext = $file->getClientOriginalExtension();

        $photoDir = public_path('photos');
        if (!file_exists($photoDir)) {
            mkdir($photoDir, 0755, true);
        }

        $prefix = match ($type) {
            'signature' => 'sign_',
            // 'aadhaar' => 'aadhaar_',
            'passport' => 'passport_',
            default => 'photo_',
        };

        $filename = $prefix . uniqid() . '.' . $ext;
        $destination = $photoDir . '/' . $filename;

        file_put_contents($destination, file_get_contents($file));

        $validationResult = $this->runPythonValidation($destination, $type);

        $publicPath = 'photos/' . $filename;
        $existing = session()->get('files', []);
        $existing[$type] = $publicPath;
        session(['files' => $existing]);

        // ✅ Return only the partial view instead of redirect
        return view('result', ['result' => $validationResult]);
    }

    private function runPythonValidation($absoluteImagePath, $type = 'photo')
    {
        ini_set('memory_limit', '512M');
        set_time_limit(300);
        $scriptMap = [
            'photo' => 'validate_passport_photo.py',
            'signature' => 'validate_signature.py',
            // 'aadhaar' => 'validate_aadhaar_card.py',
            'passport' => 'validate_passport_doc.py',
        ];

        if (!isset($scriptMap[$type])) {
            return ['error' => 'Invalid validation type provided.'];
        }

        $scriptPath = base_path('python/' . $scriptMap[$type]);

        if (!file_exists($absoluteImagePath)) {
            return ['error' => 'Image file not found.'];
        }

        $python = 'python';
        $command = "$python \"$scriptPath\" \"$absoluteImagePath\" 2>&1";

        $output = shell_exec($command);

        if (!$output) {
            return ['error' => 'No response from Python script.'];
        }

        $decoded = json_decode($output, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'error' => 'Invalid JSON returned from Python script.',
                'raw' => $output
            ];
        }

        return $decoded;
    }

    public function finalSubmit(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'dob' => 'required|date',
                'indos_no' => 'required|string|max:100',
                'passport_no' => 'required|string|max:100',
                'cdc_no' => 'nullable|string|max:100',
                'dgs_certificate_no' => 'nullable|string|max:100',
                'course_detail_id' => 'required|exists:course_details,id',
            ]);

            $files = session('files', []);
            if (!isset($files['photo'], $files['signature'], $files['passport'])) {
                return back()->with('error', 'Please upload and validate all required documents.');
            }

            $courseDetail = \App\Models\CourseDetail::with('course')->findOrFail($request->course_detail_id);
            // Get roll part from request
            $rollPart = '01';
            if ($request->has('roll_no')) {
                $rollParts = explode('-', $request->roll_no);
                $rollPart = isset($rollParts[2]) ? $rollParts[2] : $request->roll_no;
            }
            $batchNo = str_pad($courseDetail->batch_no, 2, '0', STR_PAD_LEFT);
            $courseName = $courseDetail->course->name;
            if (preg_match('/\((.*?)\)/', $courseName, $matches)) {
                $courseCode = $matches[1];
            } else {
                $courseCode = strtok($courseName, ' ');
            }
            $roll_no = $courseCode . '-' . $batchNo . '-' . $rollPart;

            \App\Models\Candidates::create([
                'roll_no' => $roll_no,
                'name' => $request->name,
                'dob' => $request->dob,
                'indos_no' => $request->indos_no,
                'passport_no' => ['required','regex:/^[A-Za-z0-9]{8}$/'],
                'cdc_no' => $request->cdc_no,
                'dgs_certificate_no' => $request->dgs_certificate_no,
                'course_detail_id' => $courseDetail->id,
                'photo_path' => $files['photo'],
                'signature_path' => $files['signature'],
                'passport_path' => $files['passport'],
            ]);

            $admins = User::where('role', 'admin')->get();
            Notification::send($admins, new CandidateAddedNotification($candidate));


            Session::forget('files');
            return back()->with('success', 'All documents successfully submitted!');
        } catch (\Exception $e) {
            \Log::error('Final Submit Error: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong. Please try again later.');
        }
    }

    public function export()
    {
        $user = auth()->user();

        $candidates = $user->role === 'faculty'
            ? Candidates::with('courseDetail.course')
            ->whereIn('course_detail_id', CourseDetail::whereHas('course', function ($q) use ($user) {
                $q->whereIn('id', $user->subjects()->pluck('subjects.id'));
            })->pluck('id'))
            ->get()
            : Candidates::with('courseDetail.course')->get();

        $headers = ['Roll No', 'Name', 'DOB', 'Course', 'Batch No', 'INDOS', 'Passport', 'CDC', 'DGS Certificate No'];

        $callback = function () use ($candidates, $headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            foreach ($candidates as $c) {
                fputcsv($file, [
                    $c->roll_no,
                    $c->name,
                    optional($c->dob)->format('Y-m-d'),
                    $c->courseDetail ? $c->courseDetail->course->name : '-',
                    $c->courseDetail ? $c->courseDetail->batch_no : '-',
                    $c->indos_no,
                    $c->passport_no,
                    $c->cdc_no,
                    $c->dgs_certificate_no,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=candidates.csv",
            "Cache-Control" => "no-store, no-cache",
        ]);
    }

    public function import(Request $request, CertificateService $certService)
    {
        $file = $request->file('csv_file');
        $rows = array_map('str_getcsv', file($file));
        $header = array_map('trim', array_shift($rows));

        $errors = [];

        foreach ($rows as $index => $row) {
            try {
                $data = array_combine($header, $row);

                // Skip empty rows
                if (empty($data['Roll No'])) {
                    continue;
                }

                $candidate = Candidates::where('roll_no', $data['Roll No'])->first();

                if (!$candidate) {
                    \Log::info("Skipped row {$index}: Candidate not found for roll no: " . $data['Roll No']);
                    continue;
                }

                if (!empty($data['DGS Certificate No'])) {
                    DB::beginTransaction();

                    // Temporarily assign new DGS Certificate No (not saved yet)
                    $candidate->dgs_certificate_no = $data['DGS Certificate No'];

                    // Attempt to generate PDF
                    // $certService->generate([
                    //     'roll_no' => $candidate->roll_no,
                    //     'name' => $candidate->name,
                    //     'dob' => optional($candidate->dob)->format('d-m-Y'),
                    //     'indos_no' => $candidate->indos_no,
                    //     'passport_no' => $candidate->passport_no,
                    //     'cdc_no' => $candidate->cdc_no,
                    //     'certificate_no' => $candidate->dgs_certificate_no,
                    //     'start_date' => now()->subDays(5)->format('d M Y'),
                    //     'end_date' => now()->format('d M Y'),
                    //     'issue_date' => now()->format('jS M Y'),
                    // ], "certificate_{$candidate->roll_no}.pdf");

                    // Only commit to DB if PDF generation was successful
                    $candidate->save();
                    DB::commit();
                }
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error("Error processing row {$index}: " . $e->getMessage());
                $errors[] = "Row {$index} (" . ($data['Roll No'] ?? 'N/A') . "): " . $e->getMessage();
                continue;
            }
        }

        if (!empty($errors)) {
            return redirect()->back()->with('error', 'Some records failed to import. Check logs for details.');
        }

        return redirect()->back()->with('success', 'DGS numbers updated and certificates generated!');
    }

    public function destroy(Candidates $candidate)
    {
        try {
            // Delete candidate-related marks if needed
            $candidate->marks()->delete();

            // Optionally delete uploaded documents
            if ($candidate->photo_path && file_exists(public_path('photos/' . basename($candidate->photo_path)))) {
                unlink(public_path('photos/' . basename($candidate->photo_path)));
            }
            if ($candidate->signature_path && file_exists(public_path('photos/' . basename($candidate->signature_path)))) {
                unlink(public_path('photos/' . basename($candidate->signature_path)));
            }
            if ($candidate->passport_path && file_exists(public_path('photos/' . basename($candidate->passport_path)))) {
                unlink(public_path('photos/' . basename($candidate->passport_path)));
            }

            $candidate->delete();

            return redirect()->back()->with('success', 'Candidate deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete candidate: ' . $e->getMessage());
        }
    }

    public function generateMarksheet($id)
    {
        $candidate = Candidates::with('courseDetail.course', 'marks')->findOrFail($id);

        $subjects = $candidate->courseDetail ? $candidate->courseDetail->course->subjects->map(function ($subject) {
            return [$subject->name, $subject->max_marks, $subject->passing_marks];
        })->toArray() : [];

        $marks = $candidate->marks;

        if ($marks->isEmpty()) {
            return redirect()->back()->with('error', 'No marks found for this candidate.');
        }

        $obtained = array_sum(array_column($marks->toArray(), 'marks_obtained'));
        $total = array_sum(array_column($subjects, 1));
        $percentage = round(($obtained / $total) * 100, 2);
        $result = $percentage >= 70 ? 'Pass (First Class with Distinction)' : 'Pass';

        $courseSlug = strtolower(
            trim(preg_replace('/[^A-Za-z0-9]+/', '_', $candidate->courseDetail ? $candidate->courseDetail->course->name : '-'), '_')
        );

        $view = "pdf.marksheets.{$courseSlug}";

        if (!view()->exists($view)) {
            return redirect()->back()->with('error', "Marksheet template not found for course: " . ($candidate->courseDetail ? $candidate->courseDetail->course->name : '-'));
        }
        // dd($candidate);
        $pdf = Pdf::loadView($view, compact('candidate', 'subjects', 'marks', 'obtained', 'total', 'percentage', 'result'));
        return $pdf->download("Marksheet-{$candidate->roll_no}.pdf");
    }

    public function editCandidate($id)
    {
        $candidate = \App\Models\Candidates::findOrFail($id);
        $courseDetails = \App\Models\CourseDetail::with('course')->orderBy('id', 'desc')->get();
        return view('edit_candidate', compact('candidate', 'courseDetails'));
    }

    public function updateCandidate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'dob' => 'required|date',
            'indos_no' => 'required|string|max:100',
            'passport_no' => 'required|string|max:100',
            'cdc_no' => 'required|string|max:100',
            'dgs_certificate_no' => 'nullable|string|max:100',
            'course_detail_id' => 'required|exists:course_details,id',
        ]);
        $candidate = \App\Models\Candidates::findOrFail($id);
        $courseDetail = \App\Models\CourseDetail::with('course')->findOrFail($request->course_detail_id);
        $existingCount = \App\Models\Candidates::where('course_detail_id', $courseDetail->id)->count();
        $nextRoll = str_pad($existingCount, 2, '0', STR_PAD_LEFT); // keep roll unique, but don't increment if editing
        $batchNo = str_pad($courseDetail->batch_no, 2, '0', STR_PAD_LEFT);
        $courseName = $courseDetail->course->name;
        if (preg_match('/\((.*?)\)/', $courseName, $matches)) {
            $courseCode = $matches[1];
        } else {
            $courseCode = strtok($courseName, ' ');
        }
        $roll_no = $courseCode . '-' . $batchNo . '-' . $nextRoll;
        $candidate->update([
            'roll_no' => $roll_no,
            'name' => $request->name,
            'dob' => $request->dob,
            'indos_no' => $request->indos_no,
            'passport_no' => $request->passport_no,
            'cdc_no' => $request->cdc_no,
            'dgs_certificate_no' => $request->dgs_certificate_no,
            'course_detail_id' => $courseDetail->id,
        ]);
        return redirect()->route('candidate')->with('success', 'Candidate updated successfully!');
    }
}
