<?php

use App\Http\Controllers\AttendanceWebController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeviceWebController;
use App\Http\Controllers\DocumentWebController;
use App\Http\Controllers\EmployeeWebController;
use App\Http\Controllers\HolidayWebController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RequestWebController;
use App\Http\Controllers\ShiftWebController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::resource('employees', EmployeeWebController::class)->only(['index', 'create', 'show', 'edit', 'store', 'update', 'destroy']);

    Route::get('/attendance', [AttendanceWebController::class, 'index'])->name('attendance.index');

    Route::resource('requests', RequestWebController::class)->only(['index', 'create', 'store']);

    Route::resource('shifts', ShiftWebController::class)->only(['index', 'create', 'edit', 'store', 'update', 'destroy']);

    Route::get('/devices', [DeviceWebController::class, 'index'])->name('devices.index');

    Route::resource('holidays', HolidayWebController::class)->only(['index', 'create', 'edit', 'store', 'update', 'destroy']);

    Route::get('/documents', [DocumentWebController::class, 'index'])->name('documents.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
