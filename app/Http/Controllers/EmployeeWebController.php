<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\AuditLog;
use App\Models\Department;
use App\Models\Shift;
use App\Models\User; // تم الاستدعاء لربط حساب المستخدم بالملف الوظيفي
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

        $employees = $query->paginate(8);
        $departments = Department::orderBy('name')->get();
        return view('employees.index', compact('employees', 'departments'));
    }

    public function create(): View
    {
        $departments = Department::orderBy('name')->get();
        $shifts = Shift::orderBy('shift_name')->get();

        // جلب المستخدمين الذين ليس لديهم ملف موظف مرتبط بعد لمنع الازدواجية
        $users = User::whereDoesntHave('employee')->get();

        return view('employees.create', compact('departments', 'shifts', 'users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id'            => ['required', 'exists:users,id', 'unique:employees,user_id'], // حقل إجباري لربط النظام بالموظف
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

        // تصحيح: تحويل المصفوفة إلى JSON string لتفادي تضارب دمج البيانات في قاعدة البيانات
        AuditLog::create([
            'user_id'     => Auth::id(),
            'action_type' => 'create',
            'table_name'  => 'employees',
            'record_id'   => $employee->id,
            'old_values'  => null,
            'new_values'  => json_encode($employee->toArray(), JSON_UNESCAPED_UNICODE),
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

        // تصحيح: تحويل المصفوفات إلى مسارات نصوص JSON آمنة للتخزين داخل حقول الـ TEXT/JSON
        AuditLog::create([
            'user_id'     => Auth::id(),
            'action_type' => 'update',
            'table_name'  => 'employees',
            'record_id'   => $employee->id,
            'old_values'  => json_encode($oldValues, JSON_UNESCAPED_UNICODE),
            'new_values'  => json_encode($employee->fresh()->toArray(), JSON_UNESCAPED_UNICODE),
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

        // تصحيح: تشفير مصفوفة الحذف لتخزينها نصياً بشكل سليم
        AuditLog::create([
            'user_id'     => Auth::id(),
            'action_type' => 'delete',
            'table_name'  => 'employees',
            'record_id'   => $employee->id,
            'old_values'  => json_encode($employeeData, JSON_UNESCAPED_UNICODE),
            'new_values'  => null,
            'performed_at'=> now(),
        ]);

        return redirect()->route('employees.index')->with('success', 'تم حذف ملف الموظف نهائياً من النظام.');
    }

    public function show(Employee $employee): View
    {
        return view('employees.show', compact('employee'));
    }
}
