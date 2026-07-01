<?php

namespace App\Http\Controllers;

use App\Models\AttendanceLog;
use Illuminate\View\View;

class AttendanceWebController extends Controller
{
    public function index(): View
    {
        $logs = AttendanceLog::with(['employee.user', 'device'])
            ->orderBy('log_date', 'desc')
            ->paginate(15);

        return view('attendance.index', compact('logs'));
    }
}
