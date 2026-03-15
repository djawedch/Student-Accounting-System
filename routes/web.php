<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\{
    AuditLogController,
    DashboardController as AdminDashboardController,
    UniversityController as AdminUniversityController,
    DepartmentController as AdminDepartmentController,
    ExportController,
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
    Route::get('students/filter', [StudentController::class, 'filter'])->name('students.filter');
    Route::resource('students', StudentController::class)->except(['destroy']);
    Route::resource('fees', AdminFeeController::class);
    Route::get('invoices/export/pdf', [ExportController::class, 'invoicesListPdf'])->name('invoices.export.pdf');
    Route::get('payments/export/pdf', [ExportController::class, 'paymentsListPdf'])->name('payments.export.pdf');
    Route::get('invoices/{invoice}/pdf', [ExportController::class, 'invoicePdf'])->name('invoices.pdf');
    Route::get('payments/{payment}/pdf', [ExportController::class, 'paymentPdf'])->name('payments.pdf');
    Route::resource('invoices', AdminInvoiceController::class)->except(['destroy']);
    Route::resource('payments', AdminPaymentController::class)->except(['destroy']);
    Route::resource('scholarships', ScholarshipController::class);
    Route::resource('scholarship-awards', AdminScholarshipAwardController::class)->except(['destroy']);
    Route::resource('audit-logs', AuditLogController::class)->only(['index', 'show']);
    Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::patch('students/{student}/toggle-status', [StudentController::class, 'toggleStatus'])->name('students.toggle-status');
});

Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    Route::get('university', [StudentUniversityController::class, 'show'])->name('university.show');
    Route::get('department', [StudentDepartmentController::class, 'show'])->name('department.show');
    Route::get('fees', [StudentFeeController::class, 'index'])->name('fees.index');
    Route::get('fees/{fee}', [StudentFeeController::class, 'show'])->name('fees.show');
    Route::get('invoices', [StudentInvoiceController::class, 'index'])->name('invoices.index');
    Route::get('invoices/{invoice}', [StudentInvoiceController::class, 'show'])->name('invoices.show');
    Route::get('payments', [StudentPaymentController::class, 'index'])->name('payments.index');
    Route::get('payments/{payment}', [StudentPaymentController::class, 'show'])->name('payments.show');
    Route::get('scholarship-awards', [StudentScholarshipAwardController::class, 'index'])->name('scholarship-awards.index');
    Route::get('scholarship-awards/{award}', [StudentScholarshipAwardController::class, 'show'])->name('scholarship-awards.show');
});

require __DIR__ . '/auth.php';
