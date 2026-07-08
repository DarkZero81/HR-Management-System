<?php

use App\Http\Controllers\AttendanceWebController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentWebController;
use App\Http\Controllers\DeviceWebController;
use App\Http\Controllers\DocumentWebController;
use App\Http\Controllers\EmployeeWebController;
use App\Http\Controllers\HolidayWebController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\PayrollWebController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RequestWebController;
use App\Http\Controllers\SmsMessageController;
use App\Http\Controllers\ShiftWebController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('my')->name('my.')->group(function () {
        Route::get('/attendance', [AttendanceWebController::class, 'myAttendance'])->name('attendance');
        Route::get('/documents', [DocumentWebController::class, 'myDocuments'])->name('documents');
        Route::get('/documents/create', [DocumentWebController::class, 'myCreate'])->name('documents.create');
        Route::resource('requests', RequestWebController::class)->only(['index', 'create', 'store']);
    });

    Route::get('/departments', [DepartmentWebController::class, 'index'])->name('departments.index');

    Route::middleware(['role:admin,hr,manager'])->group(function () {
        Route::resource('departments', DepartmentWebController::class)->except(['index', 'show']);
        Route::resource('employees', EmployeeWebController::class);
        Route::resource('shifts', ShiftWebController::class);
        Route::resource('holidays', HolidayWebController::class);
        Route::resource('devices', DeviceWebController::class);

        Route::get('/attendance', [AttendanceWebController::class, 'index'])->name('attendance.index');
        Route::post('/attendance/check-in', [AttendanceWebController::class, 'store'])->name('attendance.checkin');
        Route::post('/attendance/check-out', [AttendanceWebController::class, 'checkOut'])->name('attendance.checkout');

        Route::get('/documents', [DocumentWebController::class, 'index'])->name('documents.index');
        Route::get('/documents/create', [DocumentWebController::class, 'create'])->name('documents.create');
        Route::get('/documents/{document}/edit', [DocumentWebController::class, 'edit'])->name('documents.edit');
        Route::put('/documents/{document}', [DocumentWebController::class, 'update'])->name('documents.update');
        Route::delete('/documents/{id}', [DocumentWebController::class, 'destroy'])->name('documents.destroy');

        Route::get('/requests', [RequestWebController::class, 'index'])->name('requests.index');
        Route::patch('/requests/{transaction}/status', [RequestWebController::class, 'update'])->name('requests.update_status');

        Route::get('/payroll', [PayrollWebController::class, 'index'])->name('payroll.index');
        Route::post('/payroll/generate', [PayrollWebController::class, 'store'])->name('payroll.generate');
        Route::get('/payroll/{employeeId}/download-pdf', [PayrollWebController::class, 'downloadPayslipPdf'])->name('payroll.download_pdf');
        Route::get('/reports', [DashboardController::class, 'reports'])->name('reports.index');
        Route::get('/reports/financial-pdf', [DashboardController::class, 'downloadFinancialReportPdf'])->name('reports.financial_pdf');

        // SMS Messaging for admins
        Route::get('/sms', [SmsMessageController::class, 'index'])->name('sms.index');
        Route::get('/sms/create', [SmsMessageController::class, 'create'])->name('sms.create');
        Route::post('/sms', [SmsMessageController::class, 'store'])->name('sms.store');
    });

    Route::post('/documents', [DocumentWebController::class, 'store'])->name('documents.store');
    Route::post('/my/documents', [DocumentWebController::class, 'storeMy'])->name('my.documents.store');
    Route::delete('/my/documents/{id}', [DocumentWebController::class, 'destroy'])->name('my.documents.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// OTP Public Routes
Route::prefix('otp')->name('otp.')->group(function () {
    Route::get('/login', [OtpController::class, 'showLoginForm'])->name('login');
    Route::post('/send', [OtpController::class, 'sendOtp'])->name('send');
    Route::get('/verify', [OtpController::class, 'showVerifyForm'])->name('verify.form');
    Route::post('/verify', [OtpController::class, 'verifyOtp'])->name('verify');
    Route::post('/resend', [OtpController::class, 'resendOtp'])->name('resend');
});