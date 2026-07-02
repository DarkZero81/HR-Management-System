<?php

namespace App\Http\Controllers;

use App\Models\AttendanceLog;
use App\Models\Employee;
use App\Models\AttendanceDevice;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceWebController extends Controller
{
    /**
     * عرض سجلات الدوام اليومية والشهرية مع إمكانية الفلترة (للمدراء والـ HR).
     */
    public function index(Request $request)
    {
        $query = AttendanceLog::with(['employee', 'device']);

        // فلترة البحث بحسب التاريخ
        if ($request->has('date') && $request->date != '') {
            $query->where('log_date', $request->date);
        } else {
            $query->where('log_date', Carbon::today()->format('Y-m-d'));
        }

        // فلترة البحث بحسب حالة الدوام (حاضر، متأخر، غائب)
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $logs = $query->latest()->paginate(15);
        $devices = AttendanceDevice::all();

        return view('attendance.index', compact('logs', 'devices'));
    }

    /**
     * محاكاة الـ API أو العملية البرمجية لتسجيل الحضور (Check-In) واحتساب التأخير تلقائياً.
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists|exists:employees,id',
            'device_id' => 'nullable|exists:attendance_devices,id',
        ]);

        $employee = Employee::with('shift')->findOrFail($request->employee_id);
        $today = Carbon::today()->format('Y-m-d');
        $now = Carbon::now();

        // التأكد من عدم وجود تسجيل حضور مسبق لنفس الموظف في نفس اليوم
        $existingLog = AttendanceLog::query()->where('employee_id', $employee->id)
                                    ->where('log_date', $today)
                                    ->first();

        if ($existingLog) {
            return redirect()->back()->with('error', 'تم تسجيل حضور هذا الموظف مسبقاً لهذا اليوم.');
        }

        $shift = $employee->shift;
        $lateMinutes = 0;
        $status = 'present';

        if ($shift) {
            // تحويل وقت بداية الوردية الثابت إلى تاريخ اليوم للمقارنة الزمنية
            $shiftStart = Carbon::parse($today . ' ' . $shift->start_time);

            // إذا تجاوز الموظف وقت بداية الوردية الرسمي
            if ($now->gt($shiftStart)) {
                $diffInMinutes = $now->diffInMinutes($shiftStart);

                // إذا تجاوزت دقائق التأخير فترة السماح المعتمدة في جدول الورديات
                if ($diffInMinutes > $shift->grace_period_minutes) {
                    $lateMinutes = $diffInMinutes;
                    $status = 'late';
                }
            }
        }

        AttendanceLog::create([
            'employee_id' => $employee->id,
            'device_id' => $request->device_id,
            'log_date' => $today,
            'check_in' => $now,
            'late_minutes' => $lateMinutes,
            'status' => $status
        ]);

        return redirect()->route('attendance.index')->with('success', 'تم تسجيل حركة الحضور بنجاح واحتساب التأخير آلياً.');
    }
}
