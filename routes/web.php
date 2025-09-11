<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseMarksController;
use App\Http\Controllers\MarksController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\PhotoValidationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\CourseDetailsController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\RoleMiddleware;
use App\Models\Subject;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'performLogin'])->name('login.perform');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Public Landing Page
Route::get('/', function () {
    return view('welcome');
});

Route::get('/courses/{id}/subjects', function ($id) {
    return Subject::where('course_id', $id)->get();
});

// Admin Only Routes
Route::middleware(['auth', RoleMiddleware::class . ':admin'])->group(function () {

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::post('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::get('/admin/dashboard', [App\Http\Controllers\Controller::class, 'adminDashboard'])->name('dashboard');



    // Master Routes
    Route::get('/master/subjects', [SubjectController::class, 'index'])->name('subjects.index');
    Route::post('/master/subjects', [SubjectController::class, 'store'])->name('subjects.store');
    Route::post('/master/subjects/{id}/update', [SubjectController::class, 'update'])->name('subjects.update');
    Route::post('/master/subjects/{id}/delete', [SubjectController::class, 'destroy'])->name('subjects.destroy');

    Route::get('/master/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::post('/master/courses', [CourseController::class, 'store'])->name('courses.store');
    Route::post('/master/courses/{id}/update', [CourseController::class, 'update'])->name('courses.update');
    Route::post('/master/courses/{id}/delete', [CourseController::class, 'destroy'])->name('courses.destroy');
    Route::resource('/master/course-details', App\Http\Controllers\CourseDetailsController::class)->names('course-details');
    Route::resource('/master/roles', App\Http\Controllers\RoleController::class)->names('roles');
    Route::resource('/master/permissions', App\Http\Controllers\PermissionController::class)->names('permissions');
});

// Shared Routes (faculty + Manager)
Route::middleware(['auth', RoleMiddleware::class . ':faculty,examcell,admin'])->group(function () {
    // Route::get('/admin/dashboard', [App\Http\Controllers\Controller::class, 'adminDashboard'])->name('dashboard');

    Route::get('/dashboard', function () {
        return view('index');
    })->name('index');

    Route::get('/add-candidate', [PhotoValidationController::class, 'addCandidate'])->name('candidate.add');
    Route::get('/candidate', [PhotoValidationController::class, 'candidate'])->name('candidate.view');
    Route::post('/candidates/import', [PhotoValidationController::class, 'import'])->name('candidates.import');
    Route::get('/candidates/export', [PhotoValidationController::class, 'export'])->name('candidates.export');
    Route::delete('/candidates/{candidate}', [PhotoValidationController::class, 'destroy'])->name('candidates.destroy');
    Route::post('/upload', [PhotoValidationController::class, 'upload'])->name('photo.upload');
    Route::post('/submit-all', [PhotoValidationController::class, 'finalSubmit'])->name('final.submit');
    // web.php
    Route::get('/candidate/{id}/marksheet', [PhotoValidationController::class, 'generateMarksheet'])->name('candidate.marksheet');


    Route::get('/add-marks/{candidate}', [MarksController::class, 'create'])->name('marks.create');
    Route::post('/add-marks/{candidate}', [MarksController::class, 'store'])->name('marks.store');
    Route::prefix('course-marks')->name('course.marks.')->group(function () {
        Route::get('/', [CourseMarksController::class, 'index'])->name('index');
        Route::get('/{course}/edit', [CourseMarksController::class, 'edit'])->name('edit');
        Route::post('/{course}', [CourseMarksController::class, 'store'])->name('store');
    });
    Route::get('/marks/approvals', [\App\Http\Controllers\MarksApprovalController::class, 'index'])->name('marks.approvals');
    Route::post('/marks/approvals/{course_id}/{batch}/approve', [\App\Http\Controllers\MarksApprovalController::class, 'approveGroup'])->name('marks.approvals.group.approve');
    Route::post('/marks/approvals/{mark}/approve', [\App\Http\Controllers\MarksApprovalController::class, 'approveMark'])->name('marks.approvals.mark.approve');
    Route::post('/marks/approvals/{mark}/reject', [\App\Http\Controllers\MarksApprovalController::class, 'rejectMark'])->name('marks.approvals.mark.reject');

    // Marksheet Generation Wizard (admin/examcell only)
    Route::get('/marksheet/wizard', [CourseMarksController::class, 'marksheetWizard'])->name('marksheet.wizard');
    Route::get('/marksheet/batches/{courseId}', [CourseMarksController::class, 'getBatchesForCourse'])->name('marksheet.getBatches');
    Route::get('/marksheet/candidates/{batchId}', [CourseMarksController::class, 'getCandidatesForBatch'])->name('marksheet.getCandidates');
    Route::post('/marksheet/generate-multiple', [CourseMarksController::class, 'generateMultipleMarksheets'])->name('marksheet.generateMultiple');

    // ETO Certificate batch generation and ZIP download
    Route::post('/certificates/eto/generate', [\App\Http\Controllers\CertificateController::class, 'generateEtoCertificates'])->name('certificates.eto.generate');

    // Certificate Wizard and batch generation (admin/examcell)
    Route::get('/certificate/wizard', [\App\Http\Controllers\CertificateController::class, 'certificateWizard'])->name('certificates.wizard');
    Route::post('/certificates/generate-multiple', [\App\Http\Controllers\CertificateController::class, 'generateMultipleCertificates'])->name('certificates.generateMultiple');
});

Route::post('/notifications/mark-all-read', function () {
    auth()->user()->unreadNotifications->markAsRead();
    return response()->json(['status' => 'ok']);
})->name('notifications.markAllRead');

Route::get('/verify/certificate/{id}', [App\Http\Controllers\CertificateController::class, 'verify'])->name('certificate.verify');

Route::post('/submit-candidate-form', [CandidateController::class, 'store'])->name('candidate.store');
Route::get('/course-details/{course_detail_id}/candidate-form', [CourseDetailsController::class, 'createCandidateForm'])->name('course-details.createCandidateForm');
Route::post('/candidate-upload', [CandidateController::class, 'upload'])->name('candidate.upload')->withoutMiddleware(['auth']);
Route::get('/candidate-link', [CandidateController::class, 'openFromToken'])
    ->name('candidate.link');
