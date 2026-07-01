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
        return view('dashboard', [
            'employeeCount' => Employee::count(),
            'shiftCount' => Shift::count(),
            'todayAttendance' => AttendanceLog::where('log_date', today())->count(),
            'pendingRequests' => HrTransaction::where('status', 'pending')->count(),
        ]);
    }
}