<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\PayrollOrder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

/**
 * Controller for payroll management.
 *
 * Handles:
 * - Monthly payroll listing for admin/manager
 * - Personal payroll history for employees
 * - Bulk payroll generation for all active employees
 * - Payslip PDF download with Arabic support
 * - Marking payroll orders as paid
 */
class PayrollWebController extends Controller
{
    /**
     * Display payroll orders for the selected month (admin view).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request): View
    {
        $month = $request->get('month', \Carbon\Carbon::now()->format('Y-m'));

        $query = PayrollOrder::with('employee')->where('salary_month', $month);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('employee', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $payrolls = $query->latest()->paginate(10)->appends($request->query());

        return view('payroll.index', compact('payrolls', 'month'));
    }

    /**
     * Display payroll history for the authenticated employee (My Area).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function myPayroll(Request $request): View
    {
        $employeeId = Auth::user()?->employee?->id;

        if (!$employeeId) {
            abort(403, 'لا يوجد ملف موظف مرتبط بحسابك.');
        }

        $month = $request->get('month', \Carbon\Carbon::now()->format('Y-m'));

        $payrolls = PayrollOrder::with('employee')
            ->where('employee_id', $employeeId)
            ->where('salary_month', $month)
            ->latest()
            ->paginate(10)
            ->appends($request->query());

        return view('payroll.my_index', compact('payrolls', 'month'));
    }

    /**
     * Generate payroll orders for all active employees for the selected month.
     *
     * Calculates:
     * - Late deductions based on attendance logs
     * - HR deductions from approved penalty transactions
     * - Allowances from approved promotion transactions
     * - Net salary = base_salary + allowances - deductions
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'salary_month' => ['required', 'date_format:Y-m'],
        ]);

        $month = $validated['salary_month'];

        $employees = Employee::whereNull('resign_date')->get();

        foreach ($employees as $employee) {
            $alreadyExists = PayrollOrder::where('employee_id', $employee->id)
                ->where('salary_month', $month)
                ->exists();

            if ($alreadyExists) {
                continue;
            }

            $lateMinutes = \App\Models\AttendanceLog::where('employee_id', $employee->id)
                ->where('log_date', 'like', $month . '%')
                ->sum('late_minutes');

            $hourlyRate = $employee->base_salary / 30 / 8;
            $lateDeductions = round(($lateMinutes / 60) * $hourlyRate, 2);

            $hrDeductions = \App\Models\HrTransaction::where('employee_id', $employee->id)
                ->where('status', 'approved')
                ->where('transaction_type', 'penalty')
                ->where('start_date_time', 'like', $month . '%')
                ->sum('financial_impact');

            $totalDeductions = round($lateDeductions + $hrDeductions, 2);

            $totalAllowances = \App\Models\HrTransaction::where('employee_id', $employee->id)
                ->where('status', 'approved')
                ->where('transaction_type', 'promotion')
                ->where('start_date_time', 'like', $month . '%')
                ->sum('financial_impact');

            $netSalary = round(($employee->base_salary + $totalAllowances) - $totalDeductions, 2);

            $payroll = PayrollOrder::create([
                'employee_id' => $employee->id,
                'salary_month' => $month,
                'allowances' => $totalAllowances,
                'deductions' => $totalDeductions,
                'net_salary' => $netSalary,
                'payment_status' => 'draft',
            ]);

            \App\Models\AuditLog::create([
                'user_id' => auth()->id(),
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

    /**
     * Download the payslip PDF for a specific employee.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $employeeId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function downloadPayslipPdf(Request $request, int $employeeId): SymfonyResponse
    {
        $employee = Employee::with(['department', 'shift'])->findOrFail($employeeId);

        $month = $request->query('month', \Carbon\Carbon::now()->format('Y-m'));

        $payroll = PayrollOrder::where('employee_id', $employeeId)
            ->where('salary_month', $month)
            ->firstOrFail();

        $companyName = \App\Models\SystemSetting::where('setting_key', 'company_name')->value('setting_value') ?? 'المنقذ';

        $html = view('payroll.pdf_payslip', [
            'employee' => $employee,
            'payroll'  => $payroll,
            'date'     => now()->format('Y-m-d'),
            'company_name' => $companyName,
        ])->render();

        $mpdf = $this->makeMpdf();
        $mpdf->WriteHTML($html);

        $filename = 'payslip-' . $employee->full_name . '-' . $payroll->salary_month . '.pdf';

        return new Response($mpdf->Output($filename, 'D'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Mark a payroll order as paid.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAsPaid(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $payroll = PayrollOrder::findOrFail($id);

        $payroll->update([
            'payment_status' => 'paid',
            'paid_at' => now(),
        ]);

        return back()->with('success', 'تم تحديث حالة الدفع إلى: مدفوع.');
    }

    /**
     * Check if mPDF library is available.
     *
     * @return void
     * @throws \RuntimeException
     */
    private function ensureMpdfAvailable(): void
    {
        if (!class_exists(\Mpdf\Mpdf::class)) {
            throw new \RuntimeException('مكتبة mPDF غير مثبتة بعد. شغّل: composer require mpdf/mpdf');
        }
    }

    /**
     * Create a configured mPDF instance.
     *
     * @return \Mpdf\Mpdf
     */
    private function makeMpdf(): \Mpdf\Mpdf
    {
        return new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font' => 'dejavusans',
        ]);
    }
}
