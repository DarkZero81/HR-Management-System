<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AttendanceDevice;
use App\Models\AttendanceLog;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceLogController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = AttendanceLog::with(['employee.user', 'device']);

        if ($request->filled('log_date')) {
            $query->where('log_date', $request->log_date);
        }

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        $logs = $query->paginate($request->get('per_page', 15));
        return response()->json(['data' => $logs], 200);
    }

    public function storeCheckIn(Request $request): JsonResponse
    {
        return DB::transaction(function () use ($request) {
            $validated = $request->validate([
                'employee_id' => ['required', 'exists:employees,id'],
                'device_id' => ['nullable', 'exists:attendance_devices,id'],
            ]);

            $employee = Employee::with('shift')->findOrFail($validated['employee_id']);
            $logDate = Carbon::today();
            $checkInTime = now();

            $lateMinutes = 0;
            if ($employee->shift) {
                $shiftStart = Carbon::parse($logDate->format('Y-m-d') . ' ' . $employee->shift->start_time);
                $gracePeriod = $employee->shift->grace_period_minutes;
                if ($checkInTime > $shiftStart->addMinutes($gracePeriod)) {
                    $lateMinutes = $checkInTime->diffInMinutes($shiftStart);
                }
            }

            $attendanceLog = AttendanceLog::updateOrCreate(
                [
                    'employee_id' => $validated['employee_id'],
                    'log_date' => $logDate,
                ],
                [
                    'device_id' => $validated['device_id'],
                    'check_in' => $checkInTime,
                    'check_out' => null,
                    'late_minutes' => $lateMinutes,
                    'overtime_minutes' => 0,
                    'status' => $lateMinutes > 0 ? 'late' : 'present',
                ]
            );

            return response()->json(['data' => $attendanceLog->load(['employee.user', 'device'])], 201);
        });
    }

    public function updateCheckOut(Request $request, int $id): JsonResponse
    {
        return DB::transaction(function () use ($request, $id) {
            $attendanceLog = AttendanceLog::findOrFail($id);

            $checkOutTime = now();
            $overtimeMinutes = 0;

            $employee = $attendanceLog->employee->load('shift');
            if ($employee->shift && $attendanceLog->check_in) {
                $shiftEnd = Carbon::parse($attendanceLog->log_date->format('Y-m-d') . ' ' . $employee->shift->end_time);
                if ($checkOutTime > $shiftEnd) {
                    $overtimeMinutes = $checkOutTime->diffInMinutes($shiftEnd);
                }
            }

            $attendanceLog->update([
                'check_out' => $checkOutTime,
                'overtime_minutes' => $overtimeMinutes,
            ]);

            return response()->json(['data' => $attendanceLog->load(['employee.user', 'device'])], 200);
        });
    }

    public function getDailyAttendanceStatus(string $date): JsonResponse
    {
        $logs = AttendanceLog::with('employee.user')
            ->where('log_date', $date)
            ->get();

        return response()->json(['data' => $logs], 200);
    }
}