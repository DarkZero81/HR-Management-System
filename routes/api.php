<?php

use App\Http\Controllers\Api\AuditLogController;
use App\Http\Controllers\Api\AttendanceDeviceController;
use App\Http\Controllers\Api\AttendanceLogController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DepartmentApiController; // Imported the new department controller
use App\Http\Controllers\Api\DocumentController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\HolidayController;
use App\Http\Controllers\Api\HrTransactionController;
use App\Http\Controllers\Api\PayrollOrderController;
use App\Http\Controllers\Api\RolePermissionController;
use App\Http\Controllers\Api\ShiftController;
use App\Http\Controllers\Api\SystemSettingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware(\Illuminate\Auth\Middleware\Authenticate::class)->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::get('/roles', [RolePermissionController::class, 'index']);
    Route::get('/roles/{id}', [RolePermissionController::class, 'show']);

    Route::get('/settings', [SystemSettingController::class, 'index']);
    Route::put('/settings', [SystemSettingController::class, 'update']);

    Route::apiResource('shifts', ShiftController::class);

    Route::get('/devices', [AttendanceDeviceController::class, 'index']);
    Route::post('/devices', [AttendanceDeviceController::class, 'store']);
    Route::get('/devices/{id}', [AttendanceDeviceController::class, 'show']);
    Route::put('/devices/{id}', [AttendanceDeviceController::class, 'update']);
    Route::delete('/devices/{id}', [AttendanceDeviceController::class, 'destroy']);
    Route::patch('/devices/{id}/toggle-status', [AttendanceDeviceController::class, 'toggleStatus']);

    Route::apiResource('holidays', HolidayController::class);

    Route::apiResource('departments', DepartmentApiController::class); // Added the department resource routes

    Route::apiResource('employees', EmployeeController::class);

    Route::apiResource('documents', DocumentController::class);
    Route::get('/documents/expiring/{days?}', [DocumentController::class, 'getExpiringDocuments']);

    Route::get('/attendance-logs', [AttendanceLogController::class, 'index']);
    Route::post('/attendance-logs/check-in', [AttendanceLogController::class, 'storeCheckIn']);
    Route::patch('/attendance-logs/{id}/check-out', [AttendanceLogController::class, 'updateCheckOut']);
    Route::get('/attendance-logs/daily/{date}', [AttendanceLogController::class, 'getDailyAttendanceStatus']);

    Route::get('/hr-transactions', [HrTransactionController::class, 'index']);
    Route::post('/hr-transactions', [HrTransactionController::class, 'submitRequest']);
    Route::post('/hr-transactions/{id}/process-approval', [HrTransactionController::class, 'processApproval']);

    Route::get('/payroll-orders', [PayrollOrderController::class, 'index']);
    Route::post('/payroll-orders/generate/{salaryMonth}', [PayrollOrderController::class, 'generateMonthlyPayroll']);
    Route::get('/payroll-orders/employee/{employeeId}', [PayrollOrderController::class, 'getEmployeePayslip']);

    Route::get('/audit-logs', [AuditLogController::class, 'index']);
});
