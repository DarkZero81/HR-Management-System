<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\PayrollOrder;

class PayrollViewer extends Component
{
    public $month;

    public function mount($month = null)
    {
        $this->month = $month ?? now()->format('Y-m');
    }

    public function render()
    {
        $user = Auth::user();
        $employee = $user?->employee;

        $query = PayrollOrder::query()->where('salary_month', $this->month);

        if ($employee && ! in_array(optional($user->role)->role_name, ['admin','hr','manager'])) {
            $query->where('employee_id', $employee->id);
        }

        $payrolls = $query->with('employee')->get();

        $summary = [
            'count' => $payrolls->count(),
            'totalAllowances' => $payrolls->sum('allowances'),
            'totalDeductions' => $payrolls->sum('deductions'),
            'totalNet' => $payrolls->sum('net_salary'),
        ];

        return view('livewire.payroll-viewer', compact('payrolls', 'summary'));
    }
}
