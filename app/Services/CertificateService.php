<?php

namespace App\Services;

use PhpOffice\PhpWord\TemplateProcessor;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class CertificateService
{
    public function generate(array $data, string $outputFile)
    {
        // Load the background PNG as base64
        $backgroundPath = public_path('background.png'); // make sure file is here
        if (!file_exists($backgroundPath)) {
            throw new \Exception("Background image not found at: $backgroundPath");
        }

        $base64Background = base64_encode(file_get_contents($backgroundPath));

        // Render Blade template with data and background image
        $html = view('certificates.template', [
            'name' => $data['name'],
            'dob' => $data['dob'],
            'passport_no' => $data['passport_no'],
            'indos_no' => $data['indos_no'],
            'roll_no' => $data['roll_no'],
            'cdc_no' => $data['cdc_no'],
            'certificate_no' => $data['certificate_no'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'issue_date' => $data['issue_date'],
            'place' => $data['place'] ?? 'Mumbai',
            'background_image' => $base64Background,
        ])->render();

        // Generate PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
        $pdf->setPaper('A4', 'portrait');

        $finalPath = storage_path("app/public/certificates/$outputFile");
        $pdf->save($finalPath);

        return $finalPath;
    }



}
