<?php

use App\Http\Controllers\AttendanceWebController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeviceWebController;
use App\Http\Controllers\DocumentWebController;
use App\Http\Controllers\EmployeeWebController;
use App\Http\Controllers\DepartmentWebController; // تم استدعاء متحكم الأقسام الجديد
use App\Http\Controllers\HolidayWebController;
use App\Http\Controllers\PayrollWebController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RequestWebController;
use App\Http\Controllers\ShiftWebController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

Route::middleware(['auth'])->group(function () {
    // لوحة التحكم الرئيسية
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // مسارات الخدمة الذاتية للموظف الحالي
    Route::prefix('my')->name('my.')->group(function () {
        Route::get('/attendance', [AttendanceWebController::class, 'myAttendance'])->name('attendance');
        Route::get('/documents', [DocumentWebController::class, 'myDocuments'])->name('documents');
        Route::resource('requests', RequestWebController::class)->only(['index', 'create', 'store']);
    });

    // مسارات الإدارة والموارد البشرية (صلاحيات مخصصة)
    Route::middleware(['role:admin,hr,manager'])->group(function () {
        Route::resource('employees', EmployeeWebController::class);
        Route::resource('departments', DepartmentWebController::class); // تم إضافة مسار إدارة الأقسام كاملاً
        Route::resource('shifts', ShiftWebController::class);
        Route::resource('holidays', HolidayWebController::class);
        Route::resource('devices', DeviceWebController::class); // تم اختصار مسارات الأجهزة هنا بسطر واحد

        // مسارات إدارة الحضور والانصراف
        Route::get('/attendance', [AttendanceWebController::class, 'index'])->name('attendance.index');
        Route::post('/attendance/check-in', [AttendanceWebController::class, 'store'])->name('attendance.checkin');
        Route::post('/attendance/check-out', [AttendanceWebController::class, 'checkOut'])->name('attendance.checkout');

        // مسارات إدارة مستندات الموظفين (تم اختصارها)
        Route::resource('documents', DocumentWebController::class)->only(['index', 'create', 'store', 'destroy']);

        // مسارات طلبات الموظفين (الاذونات والإجازات)
        Route::get('/requests', [RequestWebController::class, 'index'])->name('requests.index');
        Route::patch('/requests/{transaction}/status', [RequestWebController::class, 'update'])->name('requests.update_status');

        // مسارات مسيرات الرواتب والتقارير
        Route::get('/payroll', [PayrollWebController::class, 'index'])->name('payroll.index');
        Route::post('/payroll/generate', [PayrollWebController::class, 'store'])->name('payroll.generate');
        Route::get('/reports', [DashboardController::class, 'reports'])->name('reports.index');
    });

    // مسارات إدارة الملف الشخصي للمستخدم
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
