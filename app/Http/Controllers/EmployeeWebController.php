<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\AuditLog;
use App\Models\Department;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class EmployeeWebController extends Controller
{
    public function index(Request $request): View
    {
        $query = Employee::with(['shift', 'user', 'department']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('national_id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('sort_by')) {
            $query->orderBy($request->sort_by, $request->get('sort_direction', 'asc'));
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $employees = $query->paginate(15);
        $departments = Department::orderBy('name')->get();
        return view('employees.index', compact('employees', 'departments'));
    }

    public function create(): View
    {
        $departments = Department::orderBy('name')->get();
        $shifts = Shift::orderBy('shift_name')->get();
        return view('employees.create', compact('departments', 'shifts'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name'         => ['required', 'string', 'max:50'],
            'last_name'          => ['required', 'string', 'max:50'],
            'national_id'        => ['required', 'string', 'max:50', 'unique:employees,national_id'],
            'phone'              => ['nullable', 'string', 'max:20'],
            'base_salary'        => ['required', 'numeric', 'min:0'],
            'join_date'          => ['required', 'date'],
            'department_id'      => ['nullable', 'exists:departments,id'],
            'shift_id'           => ['nullable', 'exists:shifts,id'],
            'vacation_balance'   => ['nullable', 'integer', 'min:0'],
            'bank_account_iban'  => ['nullable', 'string', 'max:50'],
        ]);

        $employee = Employee::create($validated);

        AuditLog::create([
            'user_id'     => Auth::id(),
            'action_type' => 'create',
            'table_name'  => 'employees',
            'record_id'   => $employee->id,
            'new_values'  => $employee->toArray(),
            'performed_at'=> now(),
        ]);

        return redirect()->route('employees.index')->with('success', 'تم إضافة الموظف بنجاح وإنشاء الملف الوظيفي.');
    }

    public function edit(Employee $employee): View
    {
        $departments = Department::orderBy('name')->get();
        $shifts = Shift::orderBy('shift_name')->get();
        return view('employees.edit', compact('employee', 'departments', 'shifts'));
    }

    public function update(Request $request, Employee $employee): RedirectResponse
    {
        // استثناء المعرف الحالي لمنع انهيار التحقق عند الحفظ بدون تغيير الهوية الوطنية
        $validated = $request->validate([
            'first_name'         => ['required', 'string', 'max:50'],
            'last_name'          => ['required', 'string', 'max:50'],
            'national_id'        => ['required', 'string', 'max:50', 'unique:employees,national_id,' . $employee->id],
            'phone'              => ['nullable', 'string', 'max:20'],
            'base_salary'        => ['required', 'numeric', 'min:0'],
            'join_date'          => ['required', 'date'],
            'resign_date'        => ['nullable', 'date', 'after_or_equal:join_date'],
            'department_id'      => ['nullable', 'exists:departments,id'],
            'shift_id'           => ['nullable', 'exists:shifts,id'],
            'vacation_balance'   => ['required', 'integer', 'min:0'],
            'bank_account_iban'  => ['nullable', 'string', 'max:50'],
        ]);

        $oldValues = $employee->toArray();
        $employee->update($validated);

        AuditLog::create([
            'user_id'     => Auth::id(),
            'action_type' => 'update',
            'table_name'  => 'employees',
            'record_id'   => $employee->id,
            'old_values'  => $oldValues,
            'new_values'  => $employee->fresh()->toArray(),
            'performed_at'=> now(),
        ]);

        return redirect()->route('employees.index')->with('success', 'تم تحديث بيانات ملف الموظف بنجاح.');
    }

    public function destroy(Employee $employee): RedirectResponse
    {
        $employeeData = $employee->toArray();

        try {
            $employee->delete();
        } catch (\Exception $e) {
            return redirect()->route('employees.index')->with('error', 'لا يمكن حذف الموظف لارتباطه بسجلات رواتب أو حضور تاريخية في قاعدة البيانات، يفضل تعيين تاريخ استقالة بدلاً من الحذف.');
        }

        AuditLog::create([
            'user_id'     => Auth::id(),
            'action_type' => 'delete',
            'table_name'  => 'employees',
            'record_id'   => $employee->id,
            'old_values'  => $employeeData,
            'performed_at'=> now(),
        ]);

        return redirect()->route('employees.index')->with('success', 'تم حذف ملف الموظف نهائياً من النظام.');
    }
}
