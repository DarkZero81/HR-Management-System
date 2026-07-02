<?php

namespace App\Http\Controllers;

use App\Models\AttendanceLog;
use App\Models\Employee;
use App\Models\HrTransaction;
use App\Models\PayrollOrder;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();
        $today = today();
        $role = strtolower(optional($user?->role)->role_name ?? '');
        $viewMode = in_array($role, ['admin', 'hr', 'manager'], true) ? 'admin' : 'employee';

        if ($viewMode === 'admin') {
            return view('dashboard', [
                'viewMode' => 'admin',
                'employeeCount' => Employee::query()->count('*'),
                'shiftCount' => Shift::query()->count('*'),
                'todayAttendance' => AttendanceLog::query()->where('log_date', $today)->count('*'),
                'lateMinutes' => AttendanceLog::query()->where('log_date', $today)->sum('late_minutes'),
                'pendingRequests' => HrTransaction::query()->where('status', 'pending')->count('*'),
                'recentAttendance' => AttendanceLog::query()->with('employee.user')
                    ->where('log_date', $today)
                    ->latest('check_in')
                    ->take(6)
                    ->get()
                    ->map(function($log) {
                        $log->check_in = $log->check_in ? \Carbon\Carbon::parse($log->check_in) : null;
                        return $log;
                    }),
                'pendingTransactions' => HrTransaction::query()->with('employee.user')
                    ->where('status', 'pending')
                    ->latest()
                    ->take(4)
                    ->get(),
                'recentPayrolls' => PayrollOrder::query()->latest('salary_month')->take(4)->get(),
            ]);
        }

        $employee = $user->employee;
        $employeeId = optional($employee)->id;
        return view('dashboard', [
            'viewMode' => 'employee',
            'employee' => $employee,
            'attendanceToday' => AttendanceLog::query()->where('employee_id', $employeeId)->where('log_date', $today)->count('*'),
            'vacationBalance' => $employee?->vacation_balance ?? 0,
            'pendingRequests' => HrTransaction::query()->where('employee_id', $employeeId)->where('status', 'pending')->count('*'),
            'recentAttendance' => AttendanceLog::query()->where('employee_id', $employeeId)->latest('log_date')->take(5)->get(),
            'recentPayrolls' => PayrollOrder::query()->where('employee_id', $employeeId)->latest('salary_month')->take(4)->get(),
        ]);
    }

    public function reports(): View
    {
        $today = today();

        return view('reports.index', [
            'totalEmployees' => Employee::query()->count('*'),
            'activeShifts' => Shift::query()->count('*'),
            'pendingRequests' => HrTransaction::query()->where('status', 'pending')->count('*'),
            'monthlyAttendance' => AttendanceLog::query()
                ->where('log_date', '>=', $today->copy()->startOfMonth()->toDateString())
                ->where('log_date', '<=', $today->copy()->endOfMonth()->toDateString())
                ->count('*'),
            'monthlyPayrolls' => PayrollOrder::query()->where('salary_month', $today->format('Y-m'))->count('*'),
        ]);
    }
}
