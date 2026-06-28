<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\ShuounController;

Route::get('/', fn() => redirect()->route('login'));

// Auth
Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',   [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register',[AuthController::class, 'register'])->name('register.post');
Route::post('/logout',  [AuthController::class, 'logout'])->name('logout');

// QR Verify (عام)
Route::get('/verify/{hash}', [StudentController::class, 'verifyDocument'])->name('verify');

// API - auto-fill المرشد (عام)
Route::get('/api/advisor-by-email', function(\Illuminate\Http\Request $request) {
    $email      = $request->query('email');
    $assignment = \App\Models\AdvisorAssignment::with('doctor')
        ->where('student_email', $email)->first();
    if ($assignment) {
        return response()->json([
            'found'       => true,
            'doctor_name' => $assignment->doctor->name,
        ]);
    }
    return response()->json(['found' => false]);
});

// Student Routes
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard',        [StudentController::class, 'dashboard'])->name('dashboard');
    Route::get('/register-form',    [StudentController::class, 'showRegistrationForm'])->name('register-form');
    Route::post('/register-form',   [StudentController::class, 'submitRegistrationForm'])->name('register-form.submit');
    Route::get('/track/{id}',       [StudentController::class, 'trackForm'])->name('track');
    Route::get('/step2/{form_id}',  [StudentController::class, 'showSubjectForm'])->name('step2');
    Route::post('/step2/{form_id}', [StudentController::class, 'submitSubjectForm'])->name('step2.submit');
});

// Doctor Routes
Route::middleware(['auth', 'role:doctor'])->prefix('doctor')->name('doctor.')->group(function () {
    Route::get('/dashboard',       [DoctorController::class, 'dashboard'])->name('dashboard');
    Route::get('/setup',           [DoctorController::class, 'showSetup'])->name('setup');
    Route::post('/setup',          [DoctorController::class, 'saveSetup'])->name('setup.save');
    Route::get('/form/{id}',       [DoctorController::class, 'showForm'])->name('form');
    Route::post('/form/{id}/sign', [DoctorController::class, 'signForm'])->name('form.sign');
});

// Shuoun Routes
Route::middleware(['auth', 'role:shuoun'])->prefix('shuoun')->name('shuoun.')->group(function () {
    // Dashboard
    Route::get('/dashboard',           [ShuounController::class, 'dashboard'])->name('dashboard');

    // Forms
    Route::get('/form/{id}',           [ShuounController::class, 'showForm'])->name('form');
    Route::post('/form/{id}/approve',  [ShuounController::class, 'approveForm'])->name('form.approve');
    Route::post('/form/{id}/reject',   [ShuounController::class, 'rejectForm'])->name('form.reject');

    // Templates
    Route::get('/templates',           [ShuounController::class, 'templates'])->name('templates');
    Route::get('/templates/create',    [ShuounController::class, 'createTemplate'])->name('templates.create');
    Route::post('/templates',          [ShuounController::class, 'storeTemplate'])->name('templates.store');
    Route::get('/templates/{id}/edit', [ShuounController::class, 'editTemplate'])->name('templates.edit');
    Route::put('/templates/{id}',      [ShuounController::class, 'updateTemplate'])->name('templates.update');

    // Receipts
    Route::get('/receipts',            [ShuounController::class, 'receipts'])->name('receipts');
    Route::post('/receipts',           [ShuounController::class, 'addReceipts'])->name('receipts.add');

    // Doctors
    Route::get('/doctors',                   [ShuounController::class, 'doctors'])->name('doctors');
    Route::get('/doctors/create',            [ShuounController::class, 'createDoctor'])->name('doctors.create');
    Route::post('/doctors',                  [ShuounController::class, 'storeDoctor'])->name('doctors.store');
    Route::delete('/doctors/{id}',           [ShuounController::class, 'deleteDoctor'])->name('doctors.delete');

    // Assignments
    Route::get('/doctors/{id}/assignments',  [ShuounController::class, 'assignments'])->name('doctors.assignments');
    Route::post('/doctors/{id}/assignments', [ShuounController::class, 'saveAssignments'])->name('doctors.assignments.save');
    Route::delete('/assignments/{id}',       [ShuounController::class, 'deleteAssignment'])->name('assignments.delete');

    // Reports
    Route::get('/reports',              [ShuounController::class, 'reports'])->name('reports');
    Route::get('/reports/pdf/{code}',   [ShuounController::class, 'reportSubjectPdf'])->name('reports.subject.pdf');
    Route::get('/reports/pdf-all',      [ShuounController::class, 'reportAllPdf'])->name('reports.all.pdf');


});
