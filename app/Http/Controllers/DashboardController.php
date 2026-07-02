<?php

namespace App\Http\Controllers;

use App\Models\AttendanceLog;
use App\Models\Employee;
use App\Models\HrTransaction;
use App\Models\Shift;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $today = today();

        return view('dashboard', [
            'employeeCount' => Employee::query()->count('*'),
            'shiftCount' => Shift::query()->count('*'),
            'todayAttendance' => AttendanceLog::query()->where('log_date', $today)->count('*'),
            'lateMinutes' => AttendanceLog::query()->where('log_date', $today)->sum('late_minutes'),
            'pendingRequests' => HrTransaction::query()->where('status', 'pending')->count('*'),
            'recentAttendance' => AttendanceLog::query()->with('employee.user')
                ->where('log_date', $today)
                ->latest('check_in')
                ->take(6)
                ->get(),
            'pendingTransactions' => HrTransaction::with('employee.user')
                ->where('status', 'pending')
                ->latest()
                ->take(4)
                ->get(),
        ]);
    }
}
