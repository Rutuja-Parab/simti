<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Candidates;
use Illuminate\Http\Request;
use App\Models\CourseDetail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;

class CandidateController extends Controller
{
    public function create($course_detail_id)
    {
        $courseDetail = CourseDetail::findOrFail($course_detail_id);
        return view('candidate_form', compact('courseDetail'));
    }

    public function store(Request $request)
    {
        try {
            // Validate incoming request
            $request->validate([
                'name' => 'required|string|max:255',
                'dob' => 'required|date',
                'indos_no' => 'required|string|max:100|unique:candidates,indos_no',
                'passport_no' => 'required|string|max:100|unique:candidates,passport_no',
                'cdc_no' => 'nullable|string|max:100',
                'dgs_certificate_no' => 'nullable|string|max:100',
                'course_detail_id' => 'required|exists:course_details,id',
            ]);

            // Get uploaded files stored in session
            $files = session('files', []);
            if (!isset($files['photo'], $files['signature'], $files['passport'])) {
                return back()->with('error', 'Please upload and validate all required documents.');
            }

            $courseDetail = \App\Models\CourseDetail::with('course')->findOrFail($request->course_detail_id);

            // Generate roll number
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

            // Create candidate
            \App\Models\Candidates::create([
                'roll_no' => $roll_no,
                'name' => $request->name,
                'dob' => $request->dob,
                'indos_no' => $request->indos_no,
                'passport_no' => $request->passport_no,
                'cdc_no' => $request->cdc_no,
                'dgs_certificate_no' => $request->dgs_certificate_no,
                'course_detail_id' => $courseDetail->id,
                'photo_path' => $files['photo'],
                'signature_path' => $files['signature'],
                'passport_path' => $files['passport'],
            ]);

            // Clear session files
            Session::forget('files');

            return back()->with('success', 'All documents successfully submitted!');
        } catch (\Exception $e) {
            \Log::error('Candidate Store Error: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong.' . $e->getMessage());
        }
    }


    public function upload(Request $request)
    {
        $request->validate([
            'photo' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'type' => 'required|in:photo,signature,passport',
        ]);

        $type = $request->input('type');
        $file = $request->file('photo');
        $ext = $file->getClientOriginalExtension();

        $photoDir = public_path('uploads/candidate');
        if (!file_exists($photoDir)) {
            mkdir($photoDir, 0755, true);
        }

        $prefix = match ($type) {
            'signature' => 'sign_',
            'passport' => 'passport_',
            default => 'photo_',
        };

        $filename = $prefix . uniqid() . '.' . $ext;
        $destination = $photoDir . '/' . $filename;

        $file->move($photoDir, $filename);

        $validationResult = $this->runPythonValidation($destination, $type);

        // Save uploaded file path in session
        $publicPath = 'uploads/candidate/' . $filename;
        $existing = session()->get('files', []);
        $existing[$type] = $publicPath;
        session(['files' => $existing]);

        // Return JSON
        return response()->json($validationResult);
    }


    private function runPythonValidation($absoluteImagePath, $type = 'photo')
    {
        ini_set('memory_limit', '512M');
        set_time_limit(300);

        $scriptMap = [
            'photo' => 'validate_passport_photo.py',
            'signature' => 'validate_signature.py',
            'passport' => 'validate_passport_doc.py',
        ];

        if (!isset($scriptMap[$type])) {
            return ['error' => 'Invalid validation type provided.'];
        }

        $scriptPath = base_path('python/' . $scriptMap[$type]);

        if (!file_exists($absoluteImagePath)) {
            return ['error' => 'File not found.'];
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
                'error' => 'Invalid JSON from Python.',
                'raw' => $output
            ];
        }

        return $decoded;
    }

    public function openFromToken(Request $request)
{
    $token = $request->query('token');

    try {
        $data = json_decode(Crypt::decryptString($token), true);
    } catch (\Exception $e) {
        abort(404);
    }

    // optional expiry check (24h)
    if (now()->timestamp - $data['ts'] > 86400) {
        abort(403, 'Link expired.');
    }

    // Just redirect to your normal candidate-form route
    return redirect()->route('course-details.createCandidateForm', [
        'course_detail_id' => $data['id']
    ]);
}
}