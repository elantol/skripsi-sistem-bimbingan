<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DefenseController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Document & BAP Download Routes (Secure Private Storage & Authenticated PDF)
Route::middleware(['auth'])->group(function () {
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
    Route::get('/documents/versions/{version}/download', [DocumentController::class, 'downloadVersion'])->name('documents.versions.download');
    Route::get('/defenses/{defense}/bap', [DefenseController::class, 'downloadBap'])->name('defenses.bap.download');
});

// Student Routes
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [StudentController::class, 'profile'])->name('profile');
    Route::post('/profile', [StudentController::class, 'updateProfile'])->name('profile.update');
    Route::get('/supervision', [StudentController::class, 'supervision'])->name('supervision');
    Route::post('/supervision', [StudentController::class, 'updateSupervisionTitle'])->name('supervision.update');

    Route::get('/guidance', [StudentController::class, 'guidanceList'])->name('guidance.index');
    Route::get('/guidance/create', [StudentController::class, 'createGuidance'])->name('guidance.create');
    Route::post('/guidance', [StudentController::class, 'storeGuidance'])->name('guidance.store');
    Route::get('/guidance/{id}', [StudentController::class, 'showGuidance'])->name('guidance.show');
    Route::post('/guidance/{id}/revision', [StudentController::class, 'storeRevision'])->name('guidance.revision');

    Route::get('/documents', [StudentController::class, 'documents'])->name('documents');
    Route::get('/schedules', [StudentController::class, 'schedules'])->name('schedules');
    Route::get('/history', [StudentController::class, 'history'])->name('history');
    Route::get('/progress', [StudentController::class, 'progress'])->name('progress');
    Route::get('/classification', [StudentController::class, 'classification'])->name('classification');
    Route::get('/notifications', [StudentController::class, 'notifications'])->name('notifications');

    // Defense Routes (Sidang Skripsi)
    Route::get('/defenses', [DefenseController::class, 'studentIndex'])->name('defenses.index');
    Route::post('/defenses', [DefenseController::class, 'studentStore'])->name('defenses.store');
});

// Lecturer Routes
Route::middleware(['auth', 'role:lecturer'])->prefix('lecturer')->name('lecturer.')->group(function () {
    Route::get('/dashboard', [LecturerController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [LecturerController::class, 'profile'])->name('profile');
    Route::post('/profile', [LecturerController::class, 'updateProfile'])->name('profile.update');
    Route::get('/students', [LecturerController::class, 'students'])->name('students');

    Route::get('/requests', [LecturerController::class, 'requests'])->name('requests.index');
    Route::get('/requests/{id}', [LecturerController::class, 'showRequest'])->name('requests.show');
    Route::post('/requests/{id}/review', [LecturerController::class, 'storeReview'])->name('requests.review');
    Route::post('/requests/{id}/schedule', [LecturerController::class, 'storeSchedule'])->name('requests.schedule');
    Route::put('/schedules/{id}', [LecturerController::class, 'updateSchedule'])->name('schedules.update');
    Route::post('/schedules/{id}/cancel', [LecturerController::class, 'cancelSchedule'])->name('schedules.cancel');
    Route::post('/requests/{id}/complete', [LecturerController::class, 'completeGuidance'])->name('requests.complete');

    Route::get('/history', [LecturerController::class, 'history'])->name('history');
    Route::get('/progress', [LecturerController::class, 'progress'])->name('progress');
    Route::get('/classification', [LecturerController::class, 'classification'])->name('classification');
    Route::get('/notifications', [LecturerController::class, 'notifications'])->name('notifications');

    // Defense Examination Routes
    Route::get('/defenses', [DefenseController::class, 'lecturerIndex'])->name('defenses.index');
    Route::get('/defenses/{defense}/score', [DefenseController::class, 'lecturerScoreForm'])->name('defenses.score');
    Route::post('/defenses/{defense}/score', [DefenseController::class, 'lecturerStoreScore'])->name('defenses.score.store');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::get('/students', [AdminController::class, 'students'])->name('students.index');
    Route::post('/students', [AdminController::class, 'storeStudent'])->name('students.store');
    Route::put('/students/{id}', [AdminController::class, 'updateStudent'])->name('students.update');
    Route::delete('/students/{id}', [AdminController::class, 'deleteStudent'])->name('students.delete');

    Route::get('/lecturers', [AdminController::class, 'lecturers'])->name('lecturers.index');
    Route::post('/lecturers', [AdminController::class, 'storeLecturer'])->name('lecturers.store');
    Route::put('/lecturers/{id}', [AdminController::class, 'updateLecturer'])->name('lecturers.update');
    Route::delete('/lecturers/{id}', [AdminController::class, 'deleteLecturer'])->name('lecturers.delete');

    Route::get('/periods', [AdminController::class, 'periods'])->name('periods.index');
    Route::post('/periods', [AdminController::class, 'storePeriod'])->name('periods.store');
    Route::put('/periods/{id}', [AdminController::class, 'updatePeriod'])->name('periods.update');

    Route::get('/assignments', [AdminController::class, 'assignments'])->name('assignments.index');
    Route::post('/assignments', [AdminController::class, 'storeAssignment'])->name('assignments.store');
    Route::delete('/assignments/{id}', [AdminController::class, 'deleteAssignment'])->name('assignments.delete');

    Route::get('/supervisions', [AdminController::class, 'supervisions'])->name('supervisions.index');
    Route::post('/supervisions', [AdminController::class, 'storeSupervision'])->name('supervisions.store');
    Route::put('/supervisions/{id}', [AdminController::class, 'updateSupervision'])->name('supervisions.update');
    Route::get('/guidance', [AdminController::class, 'guidance'])->name('guidance.index');

    // Admin Defense Verification & Plotting Routes
    Route::get('/defenses', [DefenseController::class, 'adminIndex'])->name('defenses.index');
    Route::post('/defenses/{defense}/schedule', [DefenseController::class, 'adminSchedule'])->name('defenses.schedule');

    Route::get('/datasets', [AdminController::class, 'datasets'])->name('datasets.index');
    Route::post('/datasets/import', [AdminController::class, 'importDataset'])->name('datasets.import');

    Route::get('/classification', [AdminController::class, 'classification'])->name('classification.index');
    Route::post('/classification/run', [AdminController::class, 'runClassification'])->name('classification.run');

    Route::get('/reports', [AdminController::class, 'reports'])->name('reports.index');
});
