<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\{
    AuditLogController,
    DashboardController as AdminDashboardController,
    UniversityController as AdminUniversityController,
    DepartmentController as AdminDepartmentController,
    UserController,
    StudentController,
    FeeController as AdminFeeController,
    InvoiceController as AdminInvoiceController,
    PaymentController as AdminPaymentController,
    ScholarshipController,
    ScholarshipAwardController as AdminScholarshipAwardController
};
use App\Http\Controllers\Student\{
    DashboardController as StudentDashboardController,
    UniversityController as StudentUniversityController,
    DepartmentController as StudentDepartmentController,
    FeeController as StudentFeeController,
    InvoiceController as StudentInvoiceController,
    PaymentController as StudentPaymentController,
    ScholarshipAwardController as StudentScholarshipAwardController
};

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
});

Route::middleware(['auth', 'role:super_admin,university_admin,department_admin,staff_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('universities', AdminUniversityController::class);
    Route::resource('departments', AdminDepartmentController::class);
    Route::resource('users', UserController::class)->except(['destroy']);
    Route::resource('students', StudentController::class)->except(['destroy']);
    Route::resource('fees', AdminFeeController::class);
    Route::resource('invoices', AdminInvoiceController::class)->except(['destroy']);
    Route::resource('payments', AdminPaymentController::class)->except(['destroy']);
    Route::resource('scholarships', ScholarshipController::class);
    Route::resource('student-scholarships', AdminScholarshipAwardController::class)->except(['destroy']);
    Route::resource('audit-logs', AuditLogController::class)->only(['index', 'show']);
    Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::patch('students/{student}/toggle-status', [StudentController::class, 'toggleStatus'])->name('students.toggle-status');
});

Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    Route::get('universities', [StudentUniversityController::class, 'index'])->name('universities.index');
    Route::get('departments', [StudentDepartmentController::class, 'index'])->name('departments.index');
    Route::get('fees', [StudentFeeController::class, 'index'])->name('fees.index');
    Route::get('invoices', [StudentInvoiceController::class, 'index'])->name('invoices.index');
    Route::get('payments', [StudentPaymentController::class, 'index'])->name('payments.index');
    Route::get('scholarship-awards', [StudentScholarshipAwardController::class, 'index'])->name('scholarship-awards.index');
});

require __DIR__ . '/auth.php';
