<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate Verification</title>
</head>
<body>
    <h1>Certificate Verification</h1>
    <p><strong>Name:</strong> {{ $candidate->name }}</p>
    <p><strong>Roll No:</strong> {{ $candidate->roll_no }}</p>
    <p><strong>Certificate No:</strong> {{ $candidate->dgs_certificate_no }}</p>
    <p><strong>Course:</strong> {{ $candidate->courseDetail && $candidate->courseDetail->course ? $candidate->courseDetail->course->name : '' }}</p>
    <p><strong>Issue Date:</strong> {{ $candidate->created_at ? $candidate->created_at->format('d-m-Y') : '' }}</p>
    <p><strong>Status:</strong> Verified</p>
</body>
</html> 