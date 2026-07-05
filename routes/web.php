<?php

use App\Http\Controllers\AttendanceWebController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeviceWebController;
use App\Http\Controllers\DocumentWebController;
use App\Http\Controllers\EmployeeWebController;
use App\Http\Controllers\HolidayWebController;
use App\Http\Controllers\PayrollWebController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RequestWebController;
use App\Http\Controllers\ShiftWebController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('my')->name('my.')->group(function () {
        Route::get('/attendance', [AttendanceWebController::class, 'myAttendance'])->name('attendance');
        Route::get('/documents', [DocumentWebController::class, 'myDocuments'])->name('documents');
        Route::resource('requests', RequestWebController::class)->only(['index', 'create', 'store']);
    });

    Route::middleware(['role:admin,hr,manager'])->group(function () {
        Route::resource('employees', EmployeeWebController::class);
        Route::resource('shifts', ShiftWebController::class);
        Route::resource('holidays', HolidayWebController::class);

        Route::get('/attendance', [AttendanceWebController::class, 'index'])->name('attendance.index');
        Route::post('/attendance/check-in', [AttendanceWebController::class, 'store'])->name('attendance.checkin');
        Route::post('/attendance/check-out', [AttendanceWebController::class, 'checkOut'])->name('attendance.checkout');

        Route::get('/devices', [DeviceWebController::class, 'index'])->name('devices.index');
        Route::get('/devices/create', [DeviceWebController::class, 'create'])->name('devices.create');
        Route::post('/devices', [DeviceWebController::class, 'store'])->name('devices.store');
        Route::get('/devices/{device}/edit', [DeviceWebController::class, 'edit'])->name('devices.edit');
        Route::put('/devices/{device}', [DeviceWebController::class, 'update'])->name('devices.update');
        Route::delete('/devices/{device}', [DeviceWebController::class, 'destroy'])->name('devices.destroy');

        Route::get('/documents', [DocumentWebController::class, 'index'])->name('documents.index');
        Route::get('/documents/create', [DocumentWebController::class, 'create'])->name('documents.create');
        Route::post('/documents', [DocumentWebController::class, 'store'])->name('documents.store');
        Route::delete('/documents/{document}', [DocumentWebController::class, 'destroy'])->name('documents.destroy');

        Route::get('/requests', [RequestWebController::class, 'adminIndex'])->name('requests.index');
        Route::patch('/requests/{id}/status', [RequestWebController::class, 'updateStatus'])->name('requests.update_status');

        Route::get('/payroll', [PayrollWebController::class, 'index'])->name('payroll.index');
        Route::post('/payroll/generate', [PayrollWebController::class, 'store'])->name('payroll.generate');

        Route::get('/reports', [DashboardController::class, 'reports'])->name('reports.index');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
