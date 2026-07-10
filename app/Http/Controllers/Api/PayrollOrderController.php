<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\HrTransaction;
use App\Models\PayrollOrder;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayrollOrderController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = PayrollOrder::with('employee.user');

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->filled('salary_month')) {
            $query->where('salary_month', $request->salary_month);
        }

        $payrolls = $query->paginate($request->get('per_page', 15));
        return response()->json(['data' => $payrolls], 200);
    }

    public function generateMonthlyPayroll(string $salaryMonth): JsonResponse
    {
        return DB::transaction(function () use ($salaryMonth) {
            $employees = Employee::all();
            $createdOrders = [];

            foreach ($employees as $employee) {
                $existing = PayrollOrder::where('employee_id', $employee->id)
                    ->where('salary_month', $salaryMonth)
                    ->first();

                if (!$existing) {
                    $transportAllowance = 50000;
                    $housingAllowance = 100000;
                    $allowances = $transportAllowance + $housingAllowance;

                    $lateMinutes = $employee->attendanceLogs()
                        ->whereMonth('log_date', Carbon::parse($salaryMonth)->month)
                        ->whereYear('log_date', Carbon::parse($salaryMonth)->year)
                        ->sum('late_minutes');

                    $latePenaltyRate = 500;
                    $lateDeductions = $lateMinutes * $latePenaltyRate;

                    $penaltyDeductions = HrTransaction::where('employee_id', $employee->id)
                        ->where('transaction_type', 'penalty')
                        ->whereMonth('start_date_time', Carbon::parse($salaryMonth)->month)
                        ->whereYear('start_date_time', Carbon::parse($salaryMonth)->year)
                        ->where('status', 'approved')
                        ->sum('financial_impact');

                    $deductions = $lateDeductions + $penaltyDeductions;
                    $netSalary = max(0, $employee->base_salary + $allowances - $deductions);

                    $payroll = PayrollOrder::create([
                        'employee_id' => $employee->id,
                        'salary_month' => $salaryMonth,
                        'allowances' => $allowances,
                        'deductions' => $deductions,
                        'net_salary' => $netSalary,
                        'payment_status' => 'draft',
                    ]);

                    $createdOrders[] = $payroll;
                }
            }

            return response()->json(['message' => 'Payroll generated', 'data' => $createdOrders], 201);
        });
    }

    public function getEmployeePayslip(int $employeeId): JsonResponse
    {
        $payrolls = PayrollOrder::where('employee_id', $employeeId)
            ->orderBy('salary_month', 'desc')
            ->get();

        return response()->json(['data' => $payrolls], 200);
    }
}