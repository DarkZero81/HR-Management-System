<?php

namespace App\Http\Controllers;

use App\Models\AttendanceLog;
use App\Models\Department;
use App\Models\Document;
use App\Models\Employee;
use App\Models\HrTransaction;
use App\Models\PayrollOrder;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();
        $today = today();
        $role = strtolower(optional($user?->role)->role_name ?? '');
        $viewMode = in_array($role, ['admin', 'hr', 'manager'], true) ? 'admin' : 'employee';

        if ($viewMode === 'admin') {
            $todayAttendance = AttendanceLog::query()->where('log_date', $today)->count('*');

            $weekStart = $today->copy()->startOfWeek(\Carbon\Carbon::SATURDAY);
            $weekEnd = $today->copy()->endOfWeek(\Carbon\Carbon::FRIDAY);

            $weekDays = [];
            $weeklyAttendance = [];
            $weeklyAttendanceRate = [];

            for ($d = $weekStart->copy(); $d->lte($weekEnd); $d->addDay()) {
                $weekDays[] = match($d->dayOfWeek) {
                    0 => 'الأحد',
                    1 => 'الاثنين',
                    2 => 'الثلاثاء',
                    3 => 'الأربعاء',
                    4 => 'الخميس',
                    5 => 'الجمعة',
                    6 => 'السبت',
                    default => $d->format('D'),
                };

                $dayLogs = AttendanceLog::query()->where('log_date', $d->toDateString())->get();
                $totalEmployees = Employee::query()->count('*');
                $presentCount = $dayLogs->whereIn('status', ['present', 'late'])->count();
                $attendanceRate = $totalEmployees > 0 ? round(($presentCount / $totalEmployees) * 100, 1) : 0;

                $weeklyAttendance[] = $presentCount;
                $weeklyAttendanceRate[] = $attendanceRate;
            }

            $currentMonth = $today->format('Y-m');
            $monthlyPayrolls = PayrollOrder::query()->where('salary_month', $currentMonth)->get();
            $totalNetSalary = $monthlyPayrolls->sum('net_salary');
            $totalDeductions = $monthlyPayrolls->sum('deductions');
            $totalAllowances = $monthlyPayrolls->sum('allowances');

            $totalBaseSalary = Employee::sum('base_salary');
            $totalPayrollCost = $totalBaseSalary + $totalAllowances;
            $netSalaryRatio = $totalPayrollCost > 0 ? round(($totalNetSalary / $totalPayrollCost) * 100, 1) : 0;
            $deductionRatio = $totalPayrollCost > 0 ? round(100 - $netSalaryRatio, 1) : 0;

            $profitMarginData = [
                'totalPayrollCost' => $totalPayrollCost,
                'totalNetPaid' => $totalNetSalary,
                'totalDeductions' => $totalDeductions,
                'totalAllowances' => $totalAllowances,
                'netSalaryRatio' => $netSalaryRatio,
                'deductionRatio' => $deductionRatio,
            ];

            return view('dashboard', [
                'viewMode' => 'admin',
                'employeeCount' => Employee::query()->count('*'),
                'shiftCount' => Shift::query()->count('*'),
                'todayAttendance' => $todayAttendance,
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
                'recentPayrolls' => PayrollOrder::query()->latest('salary_month')->take(6)->get(),
                'weekDays' => $weekDays,
                'weeklyAttendance' => $weeklyAttendance,
                'weeklyAttendanceRate' => $weeklyAttendanceRate,
                'profitMarginData' => $profitMarginData,
            ]);
        }

        $employee = $user->employee;
        if ($employee) {
            $employee->load('documents');
        }
        $employeeId = optional($employee)->id;

        $departmentAvgScore = null;
        if ($employee?->department_id) {
            $departmentAvgScore = Employee::where('department_id', $employee->department_id)
                ->whereNotNull('performance_score')
                ->avg('performance_score');
        }

        $quarterlyLabels = [];
        $quarterlyPresent = [];
        $quarterlyLate = [];
        $quarterlyAbsent = [];

        for ($i = 11; $i >= 0; $i--) {
            $weekStart = now()->subWeeks($i)->startOfWeek();
            $weekEnd = now()->subWeeks($i)->endOfWeek();
            $quarterlyLabels[] = 'أسبوع ' . (12 - $i);

            $weekData = AttendanceLog::query()
                ->where('employee_id', $employeeId)
                ->whereBetween('log_date', [$weekStart->toDateString(), $weekEnd->toDateString()])
                ->selectRaw('SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as present_days')
                ->selectRaw('SUM(CASE WHEN status = "late" THEN 1 ELSE 0 END) as late_days')
                ->selectRaw('SUM(CASE WHEN status IN ("absent", "holiday") THEN 1 ELSE 0 END) as absent_days')
                ->first();

            $quarterlyPresent[] = $weekData?->present_days ?? 0;
            $quarterlyLate[] = $weekData?->late_days ?? 0;
            $quarterlyAbsent[] = $weekData?->absent_days ?? 0;
        }

        $quarterlyPerformance = [
            'labels' => $quarterlyLabels,
            'present' => $quarterlyPresent,
            'late' => $quarterlyLate,
            'absent' => $quarterlyAbsent,
        ];

        return view('dashboard', [
            'viewMode' => 'employee',
            'employee' => $employee,
            'attendanceToday' => AttendanceLog::query()->where('employee_id', $employeeId)->where('log_date', $today)->count('*'),
            'vacationBalance' => $employee?->vacation_balance ?? 0,
            'pendingRequests' => HrTransaction::query()->where('employee_id', $employeeId)->where('status', 'pending')->count('*'),
            'recentAttendance' => AttendanceLog::query()->where('employee_id', $employeeId)->latest('log_date')->take(5)->get(),
            'recentPayrolls' => PayrollOrder::query()->where('employee_id', $employeeId)->latest('salary_month')->take(4)->get(),
            'departmentAvgScore' => $departmentAvgScore,
            'quarterlyPerformance' => $quarterlyPerformance,
        ]);
    }

    public function reports(): View
    {
        $today = today();
        $currentMonth = $today->format('Y-m');

        $totalBaseSalary = Employee::sum('base_salary');
        $totalNetSalary = PayrollOrder::where('salary_month', $currentMonth)->sum('net_salary');
        $totalDeductions = PayrollOrder::where('salary_month', $currentMonth)->sum('deductions');
        $totalAllowances = PayrollOrder::where('salary_month', $currentMonth)->sum('allowances');

        $monthlyPayrolls = PayrollOrder::query()->where('salary_month', $currentMonth)->count('*');

        $salaryData = PayrollOrder::with('employee')
            ->where('salary_month', $currentMonth)
            ->get()
            ->take(5);

        $departments = Department::withCount(['employees' => function ($q) {
            $q->whereNull('resign_date');
        }])->get();

        return view('reports.index', [
            'totalEmployees' => Employee::query()->count('*'),
            'activeShifts' => Shift::query()->count('*'),
            'pendingRequests' => HrTransaction::query()->where('status', 'pending')->count('*'),
            'monthlyAttendance' => AttendanceLog::query()
                ->where('log_date', '>=', $today->copy()->startOfMonth()->toDateString())
                ->where('log_date', '<=', $today->copy()->endOfMonth()->toDateString())
                ->count('*'),
            'monthlyPayrolls' => $monthlyPayrolls,
            'financialData' => [
                'totalBaseSalary' => $totalBaseSalary,
                'totalNetSalary' => $totalNetSalary,
                'totalDeductions' => $totalDeductions,
                'totalAllowances' => $totalAllowances,
            ],
            'salaryData' => $salaryData,
            'departments' => $departments,
        ]);
    }

    public function downloadFinancialReportPdf(): \Illuminate\Http\Response
    {
        $today = today();
        $currentMonth = $today->format('Y-m');

        $totalBaseSalary = Employee::sum('base_salary');
        $totalNetSalary = PayrollOrder::where('salary_month', $currentMonth)->sum('net_salary');
        $totalDeductions = PayrollOrder::where('salary_month', $currentMonth)->sum('deductions');
        $totalAllowances = PayrollOrder::where('salary_month', $currentMonth)->sum('allowances');

        $salaryData = PayrollOrder::with('employee')
            ->where('salary_month', $currentMonth)
            ->get();

        $departments = Department::withCount(['employees' => function ($q) {
            $q->whereNull('resign_date');
        }])->get();

        $this->ensureMpdfAvailable();

        $html = view('reports.financial_pdf', [
            'date' => now()->format('Y-m-d'),
            'financialData' => [
                'totalBaseSalary' => $totalBaseSalary,
                'totalNetSalary' => $totalNetSalary,
                'totalDeductions' => $totalDeductions,
                'totalAllowances' => $totalAllowances,
            ],
            'salaryData' => $salaryData,
            'departments' => $departments,
        ])->render();

        $mpdf = $this->makeMpdf();
        $mpdf->WriteHTML($html);

        return new \Illuminate\Http\Response($mpdf->Output('financial-report-' . $currentMonth . '.pdf', 'D'), 200, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    private function ensureMpdfAvailable(): void
    {
        if (!class_exists(\Mpdf\Mpdf::class)) {
            throw new \RuntimeException('مكتبة mPDF غير مثبتة بعد. شغّل: composer require mpdf/mpdf');
        }
    }

    private function makeMpdf(): \Mpdf\Mpdf
    {
        return new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font' => 'dejavusans',
        ]);
    }
}