<?php

use App\Http\Controllers\AttendanceWebController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeviceWebController;
use App\Http\Controllers\DocumentWebController;
use App\Http\Controllers\EmployeeWebController;
use App\Http\Controllers\HolidayWebController;
use App\Http\Controllers\Auth\PayrollWebController;
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
        Route::get('/devices', [DeviceWebController::class, 'index'])->name('devices.index');
        Route::get('/documents', [DocumentWebController::class, 'index'])->name('documents.index');
        Route::get('/payroll', [PayrollWebController::class, 'index'])->name('payroll.index');
        Route::post('/payroll/generate', [PayrollWebController::class, 'store'])->name('payroll.generate');
        Route::patch('/requests/{id}/status', [RequestWebController::class, 'updateStatus'])->name('requests.update_status');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
