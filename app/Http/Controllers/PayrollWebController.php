<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\PayrollOrder;
use App\Models\AttendanceLog;
use App\Models\HrTransaction;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PayrollWebController extends Controller
{
    public function index(Request $request): View
    {
        $month = $request->get('month', Carbon::now()->format('Y-m'));
        $payrolls = PayrollOrder::with('employee')
            ->where('salary_month', $month)
            ->latest()
            ->paginate(10);

        return view('payroll.index', compact('payrolls', 'month'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'salary_month' => ['required', 'date_format:Y-m'],
        ]);

        $month = $validated['salary_month'];

        // جلب جميع الموظفين الذين لم يستقيلوا بعد
        $employees = Employee::whereNull('resign_date')->get();

        foreach ($employees as $employee) {
            // التحقق من عدم توليد كشف راتب مسبق لنفس الموظف في هذا الشهر لمنع كسر الـ Unique Constraint
            $alreadyExists = PayrollOrder::where('employee_id', $employee->id)
                ->where('salary_month', $month)
                ->exists();

            if ($alreadyExists) {
                continue;
            }

            // 1. حساب دقائق التأخير من سجلات الحضور لهذا الشهر
            $lateMinutes = AttendanceLog::where('employee_id', $employee->id)
                ->where('log_date', 'like', $month . '%')
                ->sum('late_minutes');

            // حساب الخصم الناتج عن التأخير (كل 60 دقيقة = خصم ساعة عمل)
            $hourlyRate = $employee->base_salary / 30 / 8;
            $lateDeductions = round(($lateMinutes / 60) * $hourlyRate, 2);

            // 2. حساب الجزاءات والخصومات المالية المعتمدة (approved) من الحركات والوقوعات لهذا الشهر
            $hrDeductions = HrTransaction::where('employee_id', $employee->id)
                ->where('status', 'approved')
                ->where('transaction_type', 'penalty')
                ->where('start_date_time', 'like', $month . '%')
                ->sum('financial_impact');

            // إجمالي الخصومات (تأخير + جزاءات موارد بشرية)
            $totalDeductions = round($lateDeductions + $hrDeductions, 2);

            // 3. حساب البدلات والمكافآت المالية المعتمدة (approved) الناتجة عن الترقيات وما شابه لهذا الشهر
            $totalAllowances = HrTransaction::where('employee_id', $employee->id)
                ->where('status', 'approved')
                ->where('transaction_type', 'promotion')
                ->where('start_date_time', 'like', $month . '%')
                ->sum('financial_impact');

            // 4. احتساب صافي الراتب النهائي
            $netSalary = round(($employee->base_salary + $totalAllowances) - $totalDeductions, 2);

            // حفظ أمر الصرف في قاعدة البيانات
            $payroll = PayrollOrder::create([
                'employee_id' => $employee->id,
                'salary_month' => $month,
                'allowances' => $totalAllowances,
                'deductions' => $totalDeductions,
                'net_salary' => $netSalary,
                'payment_status' => 'draft',
            ]);

            // تسجيل العملية في سجل التدقيق والمراقبة
            AuditLog::create([
                'user_id' => Auth::id(),
                'action_type' => 'create',
                'table_name' => 'payroll_orders',
                'record_id' => $payroll->id,
                'new_values' => $payroll->toArray(),
                'performed_at' => now(),
            ]);
        }

        return redirect()->route('payroll.index', ['month' => $month])
            ->with('success', 'تم توليد احتساب الرواتب للشهر المحدد بناءً على الحضور والوقوعات المالية المعتمدة.');
    }
}
