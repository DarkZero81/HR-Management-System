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
use App\Http\Controllers\ShiftWebController;
use App\Http\Controllers\SmsMessageController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

Route::middleware(['auth', 'user.active'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // attendance
    Route::get('/attendance', [AttendanceWebController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/check-in', [AttendanceWebController::class, 'store'])->name('attendance.checkin');
    Route::post('/attendance/check-out', [AttendanceWebController::class, 'checkOut'])->name('attendance.checkout');
    Route::get('/attendance/{attendance}/edit', [AttendanceWebController::class, 'edit'])->name('attendance.edit');
    Route::put('/attendance/{attendance}', [AttendanceWebController::class, 'update'])->name('attendance.update');
    Route::delete('/attendance/{attendance}', [AttendanceWebController::class, 'destroy'])->name('attendance.destroy');

    // departments
    Route::get('/departments', [DepartmentWebController::class, 'index'])->name('departments.index');
    Route::get('/departments/{department}', [DepartmentWebController::class, 'show'])->name('departments.show');
    Route::middleware(['role:admin,manager'])->group(function () {
        Route::post('/departments', [DepartmentWebController::class, 'store'])->name('departments.store');
        Route::get('/departments/create', [DepartmentWebController::class, 'create'])->name('departments.create');
        Route::get('/departments/{department}/edit', [DepartmentWebController::class, 'edit'])->name('departments.edit');
        Route::put('/departments/{department}', [DepartmentWebController::class, 'update'])->name('departments.update');
        Route::delete('/departments/{department}', [DepartmentWebController::class, 'destroy'])->name('departments.destroy');
    });

    // employees
    Route::get('/employees', [EmployeeWebController::class, 'index'])->name('employees.index');
    Route::get('/employees/{employee}', [EmployeeWebController::class, 'show'])->name('employees.show');
    Route::get('/employees/{employee}/pdf', [EmployeeWebController::class, 'downloadPdf'])->name('employees.pdf');
    Route::middleware(['role:admin,manager'])->group(function () {
        Route::post('/employees', [EmployeeWebController::class, 'store'])->name('employees.store');
        Route::get('/employees/create', [EmployeeWebController::class, 'create'])->name('employees.create');
        Route::get('/employees/{employee}/edit', [EmployeeWebController::class, 'edit'])->name('employees.edit');
        Route::put('/employees/{employee}', [EmployeeWebController::class, 'update'])->name('employees.update');
        Route::delete('/employees/{employee}', [EmployeeWebController::class, 'destroy'])->name('employees.destroy');
    });

    // shifts
    Route::get('/shifts', [ShiftWebController::class, 'index'])->name('shifts.index');
    Route::get('/shifts/{shift}', [ShiftWebController::class, 'show'])->name('shifts.show');
    Route::middleware(['role:admin,manager'])->group(function () {
        Route::post('/shifts', [ShiftWebController::class, 'store'])->name('shifts.store');
        Route::get('/shifts/create', [ShiftWebController::class, 'create'])->name('shifts.create');
        Route::get('/shifts/{shift}/edit', [ShiftWebController::class, 'edit'])->name('shifts.edit');
        Route::put('/shifts/{shift}', [ShiftWebController::class, 'update'])->name('shifts.update');
        Route::delete('/shifts/{shift}', [ShiftWebController::class, 'destroy'])->name('shifts.destroy');
    });

    // holidays
    Route::get('/holidays', [HolidayWebController::class, 'index'])->name('holidays.index');
    Route::get('/holidays/calendar', [HolidayWebController::class, 'calendar'])->name('holidays.calendar');
    Route::get('/holidays/{holiday}', [HolidayWebController::class, 'show'])->name('holidays.show');
    Route::middleware(['role:admin,manager'])->group(function () {
        Route::post('/holidays', [HolidayWebController::class, 'store'])->name('holidays.store');
        Route::get('/holidays/create', [HolidayWebController::class, 'create'])->name('holidays.create');
        Route::get('/holidays/{holiday}/edit', [HolidayWebController::class, 'edit'])->name('holidays.edit');
        Route::put('/holidays/{holiday}', [HolidayWebController::class, 'update'])->name('holidays.update');
        Route::delete('/holidays/{holiday}', [HolidayWebController::class, 'destroy'])->name('holidays.destroy');
    });

    // devices
    Route::get('/devices', [DeviceWebController::class, 'index'])->name('devices.index');
    Route::get('/devices/{device}', [DeviceWebController::class, 'show'])->name('devices.show');
    Route::middleware(['role:admin,manager'])->group(function () {
        Route::post('/devices', [DeviceWebController::class, 'store'])->name('devices.store');
        Route::get('/devices/create', [DeviceWebController::class, 'create'])->name('devices.create');
        Route::get('/devices/{device}/edit', [DeviceWebController::class, 'edit'])->name('devices.edit');
        Route::put('/devices/{device}', [DeviceWebController::class, 'update'])->name('devices.update');
        Route::delete('/devices/{device}', [DeviceWebController::class, 'destroy'])->name('devices.destroy');
    });

    // documents admin
    Route::get('/documents', [DocumentWebController::class, 'index'])->name('documents.index');
    Route::get('/documents/create', [DocumentWebController::class, 'create'])->name('documents.create');
    Route::post('/documents', [DocumentWebController::class, 'store'])->name('documents.store');
    Route::get('/documents/{document}', [DocumentWebController::class, 'show'])->name('documents.show');
    Route::get('/documents/{document}/edit', [DocumentWebController::class, 'edit'])->name('documents.edit');
    Route::put('/documents/{document}', [DocumentWebController::class, 'update'])->name('documents.update');
    Route::delete('/documents/{id}', [DocumentWebController::class, 'destroy'])->name('documents.destroy');

    // requests
    Route::get('/requests', [RequestWebController::class, 'index'])->name('requests.index');
    Route::get('/requests/{request}', [RequestWebController::class, 'show'])->name('requests.show');
    Route::get('/requests/create', [RequestWebController::class, 'create'])->name('requests.create');
    Route::post('/requests', [RequestWebController::class, 'store'])->name('requests.store');
    Route::delete('/requests/{request}', [RequestWebController::class, 'destroy'])->name('requests.destroy');
    Route::post('/requests/{request}/status', [RequestWebController::class, 'updateStatus'])->name('requests.update_status');
    Route::patch('/requests/{request}/status', [RequestWebController::class, 'updateStatus'])->name('requests.update_status.patch');
    Route::post('/requests/{transaction}/status', [RequestWebController::class, 'updateStatus'])->name('requests.update_status.post');
    Route::get('/requests/export-csv', [RequestWebController::class, 'downloadCsv'])->name('requests.export.csv');
    Route::get('requests/{transaction}/pdf', [RequestWebController::class, 'downloadPdf'])->name('requests.pdf.employee');

    // payroll
    Route::get('/payroll', [PayrollWebController::class, 'index'])->name('payroll.index');
    Route::post('/payroll/generate', [PayrollWebController::class, 'store'])->name('payroll.generate');
    Route::get('/payroll/{employeeId}/download-pdf', [PayrollWebController::class, 'downloadPayslipPdf'])->name('payroll.download_pdf');
    Route::post('/payroll/{payroll}/mark-paid', [PayrollWebController::class, 'markAsPaid'])->name('payroll.mark_paid');

    // reports
    Route::get('/reports', [DashboardController::class, 'reports'])->name('reports.index');
    Route::get('/reports/financial-pdf', [DashboardController::class, 'downloadFinancialReportPdf'])->name('reports.financial_pdf');
    Route::get('/reports/export-csv', [DashboardController::class, 'exportCsv'])->name('reports.export.csv');

    // sms
    Route::get('/sms', [SmsMessageController::class, 'index'])->name('sms.index');
    Route::get('/sms/create', [SmsMessageController::class, 'create'])->name('sms.create');
    Route::post('/sms', [SmsMessageController::class, 'store'])->name('sms.store');

    // my area
    Route::prefix('my')->name('my.')->group(function () {
        Route::get('/attendance', [AttendanceWebController::class, 'myAttendance'])->name('attendance');
        Route::get('/documents', [DocumentWebController::class, 'myDocuments'])->name('documents.index');
        Route::get('/documents/create', [DocumentWebController::class, 'myCreate'])->name('documents.create');
        Route::get('/documents/{document}', [DocumentWebController::class, 'myShow'])->name('documents.show');
        Route::get('/documents/{document}/edit', [DocumentWebController::class, 'myEdit'])->name('documents.edit');
        Route::put('/documents/{document}', [DocumentWebController::class, 'myUpdate'])->name('documents.update');
        Route::get('/files', function () {
            return redirect()->route('my.documents.index');
        })->name('files');
        Route::get('/requests', [RequestWebController::class, 'index'])->name('requests.index');
        Route::get('/requests/create', [RequestWebController::class, 'create'])->name('requests.create');
        Route::post('/requests', [RequestWebController::class, 'store'])->name('requests.store');
        Route::get('/requests/{request}', [RequestWebController::class, 'show'])->name('requests.show');
        Route::delete('/requests/{request}', [RequestWebController::class, 'destroy'])->name('requests.destroy');
        Route::post('/requests/{request}/status', [RequestWebController::class, 'updateStatus'])->name('requests.update_status');
        Route::get('/requests/export-csv', [RequestWebController::class, 'downloadCsv'])->name('requests.export.csv');
        Route::get('requests/{transaction}/pdf', [RequestWebController::class, 'downloadPdf'])->name('requests.pdf.employee');
    });

    Route::post('/my/documents', [DocumentWebController::class, 'storeMy'])->name('my.documents.store');
    Route::delete('/my/documents/{id}', [DocumentWebController::class, 'destroy'])->name('my.documents.destroy');

    // profile
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
