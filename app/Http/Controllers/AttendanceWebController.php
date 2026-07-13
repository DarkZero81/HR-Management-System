<?php

namespace App\Http\Controllers;

use App\Models\AttendanceLog;
use App\Models\Employee;
use App\Models\AttendanceDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

/**
 * Controller for attendance management (admin/manager view).
 *
 * Handles:
 * - Attendance logs listing with filters (date, status)
 * - Manual check-in/check-out for employees
 * - Editing and deleting attendance records
 * - My Attendance page for employees
 */
class AttendanceWebController extends Controller
{
    /**
     * Display all attendance logs with filters.
     *
     * Supports filtering by:
     * - Single date, date range, or today by default
     * - Attendance status (present, late, absent, holiday)
     * - Paginated results with stats summary
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = AttendanceLog::with(['employee', 'device']);

        if ($request->filled('from_date')) {
            $query->where('log_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->where('log_date', '<=', $request->to_date);
        }
        if ($request->filled('date') && $request->date != '') {
            $query->where('log_date', $request->date);
        } elseif (!$request->filled('from_date') && !$request->filled('to_date')) {
            $query->where('log_date', Carbon::today()->format('Y-m-d'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $logs = $query->latest()->paginate(10)->appends($request->query());
        $devices = AttendanceDevice::all();

        $totalPresent = AttendanceLog::whereIn('id', $logs->getCollection()->pluck('id'))
            ->where('status', 'present')->count();
        $totalLate = AttendanceLog::whereIn('id', $logs->getCollection()->pluck('id'))
            ->where('status', 'late')->count();
        $totalAbsent = AttendanceLog::whereIn('id', $logs->getCollection()->pluck('id'))
            ->where('status', 'absent')->count();

        $stats = [
            'total' => $logs->total(),
            'present' => (clone $query)->where('status', 'present')->count(),
            'late' => (clone $query)->where('status', 'late')->count(),
            'absent' => (clone $query)->where('status', 'absent')->count(),
        ];

        return view('attendance.index', compact('logs', 'devices', 'stats'));
    }

    /**
     * Display the authenticated employee's personal attendance records.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function myAttendance(Request $request)
    {
        $employee = Auth::user()?->employee;

        if (!$employee) {
            return redirect()->route('dashboard')->with('error', 'هذا الحساب غير مربوط بملف موظف لعرض سجل الحضور الشخصي.');
        }

        $query = AttendanceLog::with(['device'])
            ->where('employee_id', $employee->id);

        if ($request->filled('month')) {
            $query->whereMonth('log_date', Carbon::parse($request->month)->month)
                ->whereYear('log_date', Carbon::parse($request->month)->year);
        }

        $logs = $query->latest('log_date')->paginate(10)->appends($request->query());

        $stats = [
            'total' => (clone $query)->count(),
            'present' => (clone $query)->where('status', 'present')->count(),
            'late' => (clone $query)->where('status', 'late')->count(),
            'absent' => (clone $query)->where('status', 'absent')->count(),
        ];

        $today = Carbon::today()->format('Y-m-d');
        $todayLog = AttendanceLog::where('employee_id', $employee->id)
            ->where('log_date', $today)
            ->first();
        $devices = AttendanceDevice::all();

        return view('attendance.my_index', compact('logs', 'stats', 'todayLog', 'devices'));
    }

    /**
     * Record check-in for an employee.
     *
     * Calculates late minutes based on the employee's shift and grace period.
     * Creates or updates the attendance log for today.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'device_id' => ['nullable', 'exists:attendance_devices,id'],
        ]);

        $employee = Employee::with('shift')->findOrFail($request->employee_id);
        $today = Carbon::today()->format('Y-m-d');
        $now = Carbon::now();

        $lateMinutes = 0;
        $status = 'present';

        if ($employee->shift) {
            $shiftStart = Carbon::parse($today . ' ' . $employee->shift->start_time);
            $gracePeriod = $employee->shift->grace_period_minutes;
            if ($now->gt($shiftStart->addMinutes($gracePeriod))) {
                $lateMinutes = $now->diffInMinutes($shiftStart);
                $status = 'late';
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
     * Record check-in for the authenticated employee (My Area).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeMy(Request $request)
    {
        $employee = Auth::user()?->employee;

        if (!$employee) {
            return redirect()->route('my.attendance')->with('error', 'لا يمكن تسجيل الحضور لأن حسابك غير مربوط بملف موظف.');
        }

        $request->merge(['employee_id' => $employee->id]);

        return $this->store($request);
    }

    /**
     * Record check-out for an employee.
     *
     * Calculates overtime minutes based on the employee's shift end time.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
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

    /**
     * Record check-out for the authenticated employee (My Area).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function checkOutMy(Request $request)
    {
        $employee = Auth::user()?->employee;

        if (!$employee) {
            return redirect()->route('my.attendance')->with('error', 'لا يمكن تسجيل الانصراف لأن حسابك غير مربوط بملف موظف.');
        }

        $request->merge(['employee_id' => $employee->id]);

        return $this->checkOut($request);
    }

    /**
     * Show the edit form for an attendance log.
     *
     * @param  \App\Models\AttendanceLog  $log
     * @return \Illuminate\View\View
     */
    public function edit(AttendanceLog $log)
    {
        $devices = AttendanceDevice::all();
        return view('attendance.edit', compact('log', 'devices'));
    }

    /**
     * Update an existing attendance log.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AttendanceLog  $log
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, AttendanceLog $log)
    {
        $validated = $request->validate([
            'check_in' => ['nullable', 'date_format:H:i'],
            'check_out' => ['nullable', 'date_format:H:i'],
            'late_minutes' => ['nullable', 'integer', 'min:0'],
            'overtime_minutes' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'in:present,late,absent,holiday'],
            'device_id' => ['nullable', 'exists:attendance_devices,id'],
        ]);

        $today = $log->log_date;
        $checkIn = $validated['check_in'] ? Carbon::parse($today . ' ' . $validated['check_in']) : null;
        $checkOut = $validated['check_out'] ? Carbon::parse($today . ' ' . $validated['check_out']) : null;

        $log->update([
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'late_minutes' => $validated['late_minutes'] ?? 0,
            'overtime_minutes' => $validated['overtime_minutes'] ?? 0,
            'status' => $validated['status'],
            'device_id' => $validated['device_id'],
        ]);

        return redirect()->route('attendance.index')->with('success', 'تم تحديث سجل الحضور بنجاح.');
    }

    /**
     * Delete an attendance log.
     *
     * @param  \App\Models\AttendanceLog  $log
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(AttendanceLog $log)
    {
        $log->delete();

        return redirect()->route('attendance.index')->with('success', 'تم حذف سجل الحضور بنجاح.');
    }
}
