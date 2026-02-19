<?php

use App\Http\Controllers\{UniversityController, DepartmentController};
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

Route::resource('departments', DepartmentController::class)->middleware(['auth', 'role:super_admin,university_admin']);

require __DIR__ . '/auth.php';
