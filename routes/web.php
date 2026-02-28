<?php

use App\Http\Controllers\Admin\{AuditLogController, UniversityController, DepartmentController, UserController, StudentController, FeeController, InvoiceController, PaymentController, ScholarshipController, StudentScholarshipController};
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::middleware(['auth', 'role:super_admin,university_admin,department_admin,staff_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('universities', UniversityController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('users', UserController::class)->except(['destroy']);
    Route::resource('students', StudentController::class)->except(['destroy']);
    Route::resource('fees', FeeController::class);
    Route::resource('invoices', InvoiceController::class)->except(['destroy']);
    Route::resource('payments', PaymentController::class)->except(['destroy']);
    Route::resource('scholarships', ScholarshipController::class);
    Route::resource('student-scholarships', StudentScholarshipController::class)->except(['destroy']);
    Route::resource('audit-logs', AuditLogController::class)->only(['index', 'show']);
});

Route::middleware(['auth', 'role:super_admin,university_admin,department_admin,staff_admin'])->prefix('admin/')->group(function () {
    Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('admin.users.toggle-status');
    Route::patch('students/{student}/toggle-status', [StudentController::class, 'toggleStatus'])->name('admin.students.toggle-status');
});

require __DIR__ . '/auth.php';
