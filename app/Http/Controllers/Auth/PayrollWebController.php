<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PayrollOrder;
use App\Models\Employee;
use App\Models\AttendanceLog;
use App\Models\HrTransaction;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PayrollWebController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', Carbon::now()->format('Y-m'));
        $payrolls = PayrollOrder::with('employee')->where('salary_month', $month)->get();

        return view('payroll.index', compact('payrolls', 'month'));
    }

    /**
     * دالة المعالجة الجماعية (Batch Process) لتوليد كشوف الرواتب لشهر محدد برمجياً وبشكل مؤتمت بالكامل.
     */
    public function store(Request $request)
    {
        $request->validate([
            'salary_month' => 'required|date_format:Y-m', // صيغة YYYY-MM
        ]);

        $month = $request->salary_month;
        $startOfMonth = Carbon::parse($month . '-01')->startOfMonth();
        $endOfMonth = Carbon::parse($month . '-01')->endOfMonth();

        $employees = Employee::all();

        // استخدام Database Transaction لضمان سلامة العمليات المالية ومنع حدوث أخطاء شبكية أثناء التوليد الجماعي
        DB::transaction(function () use ($employees, $month, $startOfMonth, $endOfMonth) {
            foreach ($employees as $employee) {

                // 1. استدعاء الراتب الأساسي الثابت من جدول الموظف
                $baseSalary = $employee->base_salary;

                // 2. حساب البدلات والمكافآت (Promotions/Allowances) المعتمدة والمقبولة من جدول الوقوعات خلال الشهر
                $allowances = HrTransaction::query()->where('employee_id', $employee->id)
                    ->where('transaction_type', 'promotion')
                    ->where('status', 'approved')
                    ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                    ->sum('financial_impact');

                // 3. حساب الخصومات والسلف والعقوبات (Penalties/Leaves) المعتمدة من جدول الوقوعات
                $transactionDeductions = HrTransaction::query()->where('employee_id', $employee->id)
                    ->whereIn('transaction_type', ['penalty', 'leave']) // إجازات بلا راتب أو عقوبات مالية
                    ->where('status', 'approved')
                    ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                    ->sum('financial_impact');

                // 4. حساب خصم التأخيرات التشغيلية برمجياً من جدول الدوام
                // محاكاة بشرية: لنفترض أن دقيقة التأخير تحسب بقيمة مادية مستقطعة (مثلاً: الراتب الأساسي تقسيم 30 يوم تقسيم 8 ساعات تقسيم 60 دقيقة)
                $totalLateMinutes = AttendanceLog::query()
                    ->where('employee_id', $employee->id)
                    ->whereBetween('log_date', [$startOfMonth->format('Y-m-d'), $endOfMonth->format('Y-m-d')])
                    ->sum('late_minutes');

                $hourlyRate = ($baseSalary / 30) / 8;
                $minuteRate = $hourlyRate / 60;
                $attendanceDeductions = $totalLateMinutes * $minuteRate;

                // إجمالي الخصومات المالية والتشغيلية
                $totalDeductions = $transactionDeductions + $attendanceDeductions;

                // 5. احتساب الراتب الصافي النهائي
                $netSalary = $baseSalary + $allowances - $totalDeductions;

                // الحماية من القيم السالبة في الحالات النادرة جداً
                if ($netSalary < 0) { $netSalary = 0; }

                // 6. الحفظ أو التحديث التلقائي في قاعدة البيانات (Upsert) لمنع التكرار
                PayrollOrder::updateOrCreate(
                    [
                        'employee_id' => $employee->id,
                        'salary_month' => $month,
                    ],
                    [
                        'allowances' => $allowances,
                        'deductions' => $totalDeductions,
                        'net_salary' => $netSalary,
                        'payment_status' => 'draft', // يبدأ كمسودة حتى يعتمده المستثمر بصيغة مصدقة
                    ]
                );
            }
        });

        return redirect()->route('payroll.index', ['month' => $month])
                         ->with('success', 'تم تشغيل محرك الرواتب وأتمتة الحسابات الشهرية لكافة الموظفين بنجاح.');
    }
}
