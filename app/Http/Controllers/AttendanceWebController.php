<?php

namespace App\Http\Controllers;

use App\Models\AttendanceLog;
use App\Models\Employee;
use App\Models\AttendanceDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceWebController extends Controller
{
    public function index(Request $request)
    {
        $query = AttendanceLog::with(['employee', 'device']);

        if ($request->has('date') && $request->date != '') {
            $query->where('log_date', $request->date);
        } else {
            $query->where('log_date', Carbon::today()->format('Y-m-d'));
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $logs = $query->latest()->paginate(8);
        $devices = AttendanceDevice::all();

        return view('attendance.index', compact('logs', 'devices'));
    }

    public function myAttendance(Request $request)
    {
        // حماية أمنية: التأكد من أن المستخدم الحالي مرتبط بالموظف
        $employee = Auth::user()?->employee;

        if (!$employee) {
            return redirect()->route('dashboard')->with('error', 'هذا الحساب غير مربوط بملف موظف لعرض سجل الحضور الشخصي.');
        }

        $logs = AttendanceLog::with(['device'])
            ->where('employee_id', $employee->id)
            ->latest('log_date')
            ->paginate(8);

        return view('attendance.my_index', compact('logs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'device_id' => ['nullable', 'exists:attendance_devices,id'],
        ]);

        $employee = Employee::find($request->employee_id);
        $today = Carbon::today()->format('Y-m-d');
        $now = Carbon::now();

        // استخدام تحديث أو إنشاء (updateOrCreate) الآمنة لتجنب خطأ القيد الفريد لقاعدة البيانات في حال البصم المزدوج
        $shift = $employee->shift;
        $lateMinutes = 0;
        $status = 'present';

        if ($shift) {
            $shiftStart = Carbon::parse($today . ' ' . $shift->start_time);
            if ($now->gt($shiftStart)) {
                $diffInMinutes = $now->diffInMinutes($shiftStart);
                if ($diffInMinutes > $shift->grace_period_minutes) {
                    $lateMinutes = $diffInMinutes;
                    $status = 'late';
                }
            }
        }

        AttendanceLog::updateOrCreate(
            [
                'employee_id' => $employee->id,
                'log_date' => $today,
            ],
            [
                'device_id' => $request->device_id,
                'check_in' => $now,
                'late_minutes' => $lateMinutes,
                'status' => $status
            ]
        );

        return redirect()->route('attendance.index')->with('success', 'تم تسجيل وتحديث حركة الحضور بنظام الأمان والمطابقة بنجاح.');
    }

    /**
     * تسجيل حركة الانصراف (Check-Out) لسجل الحضور الحالي لهذا اليوم، مع احتساب العمل الإضافي تلقائياً.
     */
    public function checkOut(Request $request)
    {
        $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
        ]);

        $today = Carbon::today()->format('Y-m-d');
        $now = Carbon::now();

        $log = AttendanceLog::with('employee.shift')
            ->where('employee_id', $request->employee_id)
            ->where('log_date', $today)
            ->first();

        if (! $log) {
            return redirect()->route('attendance.index')->with('error', 'لا يوجد تسجيل حضور لهذا الموظف اليوم لتسجيل انصرافه.');
        }

        $overtimeMinutes = 0;
        $shift = $log->employee?->shift;

        if ($shift) {
            $shiftEnd = Carbon::parse($today . ' ' . $shift->end_time);
            if ($now->gt($shiftEnd)) {
                $overtimeMinutes = $now->diffInMinutes($shiftEnd);
            }
        }

        $log->update([
            'check_out' => $now,
            'overtime_minutes' => $overtimeMinutes,
        ]);

        return redirect()->route('attendance.index')->with('success', 'تم تسجيل حركة الانصراف واحتساب العمل الإضافي بنجاح.');
    }
}
