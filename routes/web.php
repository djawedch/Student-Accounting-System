<?php

use App\Http\Controllers\{UniversityController, DepartmentController};
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

Route::middleware(['auth', 'role:super_admin, university_admin'])->prefix('universities')->controller(UniversityController::class)->group(function () {
    Route::get('/', 'index')->name('universities.index');
    Route::get('/create', 'create')->name('universities.create');
    Route::post('/', 'store')->name('universities.store');
    Route::get('/{university}', 'show')->name('universities.show');
    Route::get('/{university}/edit', 'edit')->name('universities.edit');
    Route::patch('/{university}', 'update')->name('universities.update');
    Route::delete('/{university}', 'destroy')->name('universities.destroy');
});

Route::middleware(['auth', 'role:super_admin, university_admin'])->prefix('departments')->controller(DepartmentController::class)->group(function () {
    Route::get('/', 'index')->name('departments.index');
    Route::get('/create', 'create')->name('departments.create');
    Route::post('/', 'store')->name('departments.store');
    Route::get('/{department}', 'show')->name('departments.show');
    Route::get('/{department}/edit', 'edit')->name('departments.edit');
    Route::patch('/{department}', 'update')->name('departments.update');
    Route::delete('/{department}', 'destroy')->name('departments.destroy');
});

Route::middleware(['auth', 'role:super_admin,university_admin'])->prefix('admin/users')->controller(UserController::class)->group(function () {
    Route::get('/', 'index')->name('admin.users.index');
    Route::get('/create', 'create')->name('admin.users.create');
    Route::post('/', 'store')->name('admin.users.store');
    Route::get('/{user}', 'show')->name('admin.users.show');
    Route::get('/{user}/edit', 'edit')->name('admin.users.edit');
    Route::patch('/{user}', 'update')->name('admin.users.update');
    Route::patch('/users/{user}/toggle-status', 'toggleStatus')->name('admin.users.toggle-status');
});

require __DIR__ . '/auth.php';
