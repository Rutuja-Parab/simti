<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Candidates;
use ZipArchive;
use Illuminate\Support\Str;
use App\Models\Course;

class CertificateController extends Controller
{
    //

    public function generateEtoCertificates(Request $request)
    {
        // Find all ETO candidates with dgs_certificate_no
        $candidates = Candidates::with(['courseDetail.course'])
            ->whereHas('courseDetail.course', function($q) {
                $q->where('name', 'like', '%ETO%');
            })->get();

        // Check for missing dgs_certificate_no
        $missing = $candidates->filter(function($c) { return empty($c->dgs_certificate_no); });
        if ($missing->count() > 0) {
            $names = $missing->map(function($c) { return $c->name . ' (' . $c->roll_no . ')'; })->implode(', ');
            return back()->with('error', 'The following candidates are missing DGS Certificate No. and cannot be processed: ' . $names);
        }

        $candidates = $candidates->filter(function($c) { return !empty($c->dgs_certificate_no); });
        if ($candidates->isEmpty()) {
            return back()->with('error', 'No ETO candidates with DGS Certificate No. found.');
        }

        $publicDir = public_path('certificates');
        if (!file_exists($publicDir)) {
            mkdir($publicDir, 0777, true);
        }
        $generatedFiles = [];
        foreach ($candidates as $candidate) {
            $courseName = $candidate->courseDetail && $candidate->courseDetail->course ? $candidate->courseDetail->course->name : '';
            $filename = 'certificate_' . $candidate->roll_no . '.pdf';
            $savePath = $publicDir . '/' . $filename;
            // Prepare data for the template
            $data = [
                'roll_no' => $candidate->roll_no,
                'certificate_no' => $candidate->dgs_certificate_no,
                'name' => $candidate->name,
                'dob' => $candidate->dob ? $candidate->dob->format('d-m-Y') : '',
                'cdc_no' => $candidate->cdc_no,
                'passport_no' => $candidate->passport_no,
                'indos_no' => $candidate->indos_no,
                'start_date' => $candidate->courseDetail ? ($candidate->courseDetail->start_date ? date('d-M-Y', strtotime($candidate->courseDetail->start_date)) : '') : '',
                'end_date' => $candidate->courseDetail ? ($candidate->courseDetail->end_date ? date('d-M-Y', strtotime($candidate->courseDetail->end_date)) : '') : '',
                'issue_date' => now()->format('d-M-Y'),
                'place' => 'Kansal',
                'institute_name' => 'Seven Islands Maritime Training Institute',
                'photo_path' => $candidate->photo_path ? public_path('photos/' . basename($candidate->photo_path)) : null,
            ];
            // Convert photo to base64 for embedding
            if ($data['photo_path'] && file_exists($data['photo_path'])) {
                $data['photo_path'] = 'data:image/' . pathinfo($data['photo_path'], PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($data['photo_path']));
            } else {
                $data['photo_path'] = null;
            }
            // Render Blade view to HTML
            if (stripos($courseName, 'GPR') !== false) {
                $pdfHtml = view('certificates.gpr', $data)->render();
            } elseif (stripos($courseName, 'DNS') !== false) {
                $pdfHtml = view('certificates.dns', $data)->render();
            } elseif (stripos($courseName, 'GME') !== false) {
                $pdfHtml = view('certificates.gme', $data)->render();
            } else {
                $pdfHtml = view('certificates.eto', $data)->render();
            }
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($pdfHtml);
            $pdf->setPaper('A4', 'portrait');
            file_put_contents($savePath, $pdf->output());
            // Save path in DB (relative to public)
            $candidate->certificate_path = 'certificates/' . $filename;
            $candidate->save();
            $generatedFiles[] = $savePath;
        }
        // Create ZIP
        $zipName = 'eto_certificates_' . $candidate->roll_no . '.zip';
        $zipPath = $publicDir . '/' . $zipName;
        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
            foreach ($generatedFiles as $file) {
                $zip->addFile($file, basename($file));
            }
            $zip->close();
        }
        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    public function certificateWizard()
    {
        $courses = Course::all();
        return view('certificate-wizard', compact('courses'));
    }

    public function generateMultipleCertificates(Request $request)
    {
        $candidateIds = $request->input('candidate_ids', []);
        if (empty($candidateIds)) {
            return back()->with('error', 'No candidates selected.');
        }
        $candidates = Candidates::with(['courseDetail.course'])->whereIn('id', $candidateIds)->get();
        // Check for missing dgs_certificate_no
        $missing = $candidates->filter(function($c) { return empty($c->dgs_certificate_no); });
        if ($missing->count() > 0) {
            $names = $missing->map(function($c) { return $c->name . ' (' . $c->roll_no . ')'; })->implode(', ');
            return back()->with('error', 'The following candidates are missing DGS Certificate No. and cannot be processed: ' . $names);
        }
        $publicDir = public_path('certificates');
        if (!file_exists($publicDir)) {
            mkdir($publicDir, 0777, true);
        }
        $generatedFiles = [];
        foreach ($candidates as $candidate) {
            $courseName = $candidate->courseDetail && $candidate->courseDetail->course ? $candidate->courseDetail->course->name : '';
            $filename = 'certificate_' . $candidate->roll_no . '.pdf';
            $savePath = $publicDir . '/' . $filename;
            // Prepare data for the template (ETO for now, can expand for other courses)
            $data = [
                'roll_no' => $candidate->roll_no,
                'certificate_no' => $candidate->dgs_certificate_no,
                'name' => $candidate->name,
                'dob' => $candidate->dob ? $candidate->dob->format('d-m-Y') : '',
                'cdc_no' => $candidate->cdc_no,
                'passport_no' => $candidate->passport_no,
                'indos_no' => $candidate->indos_no,
                'start_date' => $candidate->courseDetail ? ($candidate->courseDetail->start_date ? date('d-M-Y', strtotime($candidate->courseDetail->start_date)) : '') : '',
                'end_date' => $candidate->courseDetail ? ($candidate->courseDetail->end_date ? date('d-M-Y', strtotime($candidate->courseDetail->end_date)) : '') : '',
                'issue_date' => now()->format('d-M-Y'),
                'place' => 'Kansal',
                'institute_name' => 'Seven Islands Maritime Training Institute',
                'photo_path' => $candidate->photo_path ? public_path('photos/' . basename($candidate->photo_path)) : null,
            ];
            // Convert photo to base64 for embedding
            if ($data['photo_path'] && file_exists($data['photo_path'])) {
                $data['photo_path'] = 'data:image/' . pathinfo($data['photo_path'], PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($data['photo_path']));
            } else {
                $data['photo_path'] = null;
            }
            // Render Blade view to HTML (ETO for now)
            if (stripos($courseName, 'GPR') !== false) {
                $pdfHtml = view('certificates.gpr', $data)->render();
            } elseif (stripos($courseName, 'DNS') !== false) {
                $pdfHtml = view('certificates.dns', $data)->render();
            } elseif (stripos($courseName, 'GME') !== false) {
                $pdfHtml = view('certificates.gme', $data)->render();
            } else {
                $pdfHtml = view('certificates.eto', $data)->render();
            }
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($pdfHtml);
            $pdf->setPaper('A4', 'portrait');
            file_put_contents($savePath, $pdf->output());
            // Save path in DB (relative to public)
            $candidate->certificate_path = 'certificates/' . $filename;
            $candidate->save();
            $generatedFiles[] = $savePath;
        }
        // Create ZIP
        $zipName = 'certificates_' . Str::random(8) . '.zip';
        $zipPath = $publicDir . '/' . $zipName;
        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
            foreach ($generatedFiles as $file) {
                $zip->addFile($file, basename($file));
            }
            $zip->close();
        }
        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
}
