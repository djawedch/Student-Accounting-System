<?php

use App\Http\Controllers\Admin\{UniversityController, DepartmentController, UserController, StudentController, FeeController, InvoiceController, PaymentController, ScholarshipController, StudentScholarshipController};
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

Route::middleware(['auth', 'role:super_admin,university_admin,department_admin,staff_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('universities', UniversityController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('users', UserController::class)->except(['destroy']);
    Route::resource('fees', FeeController::class);
    Route::resource('invoices', InvoiceController::class)->except(['destroy']);
    Route::resource('payments', PaymentController::class)->except(['destroy']);
    Route::resource('scholarships', ScholarshipController::class);
    Route::resource('student-scholarships', StudentScholarshipController::class);
});

Route::middleware(['auth', 'role:super_admin,university_admin,department_admin,staff_admin'])->prefix('admin/users')->controller(UserController::class)->group(function () {
    Route::patch('/{user}/toggle-status', 'toggleStatus')->name('admin.users.toggle-status');
});

Route::middleware(['auth', 'role:super_admin,university_admin,department_admin,staff_admin'])->prefix('admin/students')->controller(StudentController::class)->group(function () {
    Route::get('/', 'index')->name('admin.students.index');
    Route::get('/create', 'create')->name('admin.students.create');
    Route::post('/', 'store')->name('admin.students.store');
    Route::get('/{student}', 'show')->name('admin.students.show');
    Route::get('/{student}/edit', 'edit')->name('admin.students.edit');
    Route::patch('/{student}', 'update')->name('admin.students.update');
    Route::patch('/{student}/toggle-status', 'toggleStatus')->name('admin.students.toggle-status');
});

require __DIR__ . '/auth.php';
