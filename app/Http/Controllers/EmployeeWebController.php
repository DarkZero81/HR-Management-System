<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\AuditLog;
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
        $departments = \App\Models\Department::orderBy('name')->get();
        return view('employees.index', compact('employees', 'departments'));
    }

    public function create(): View
    {
        $departments = \App\Models\Department::orderBy('name')->get();
        $shifts = \App\Models\Shift::orderBy('shift_name')->get();
        return view('employees.create', compact('departments', 'shifts'));
    }

    public function show(Employee $employee): View
    {
        $employee->load('shift', 'user', 'department');
        return view('employees.show', compact('employee'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'max:50'],
            'national_id' => ['required', 'string', 'max:50', 'unique:employees'],
            'phone' => ['nullable', 'string', 'max:20'],
            'base_salary' => ['required', 'numeric', 'min:0'],
            'join_date' => ['required', 'date'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'shift_id' => ['nullable', 'exists:shifts,id'],
        ]);

        $employee = Employee::create($validated);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action_type' => 'create',
            'table_name' => 'employees',
            'record_id' => $employee->id,
            'new_values' => $employee->toArray(),
            'performed_at' => now(),
        ]);

        return redirect()->route('employees.index')->with('success', 'تم إنشاء الموظف بنجاح');
    }

    public function edit(Employee $employee): View
    {
        $departments = \App\Models\Department::orderBy('name')->get();
        $shifts = \App\Models\Shift::orderBy('shift_name')->get();
        return view('employees.edit', compact('employee', 'departments', 'shifts'));
    }

    public function update(Request $request, Employee $employee): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'max:50'],
            'national_id' => ['required', 'string', 'max:50', 'unique:employees,national_id,' . $employee->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'base_salary' => ['required', 'numeric', 'min:0'],
            'join_date' => ['required', 'date'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'shift_id' => ['nullable', 'exists:shifts,id'],
        ]);

        $oldValues = $employee->toArray();

        $employee->update($validated);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action_type' => 'update',
            'table_name' => 'employees',
            'record_id' => $employee->id,
            'old_values' => $oldValues,
            'new_values' => $employee->fresh()->toArray(),
            'performed_at' => now(),
        ]);

        return redirect()->route('employees.index')->with('success', 'تم تحديث بيانات الموظف بنجاح');
    }

    public function destroy(Employee $employee): RedirectResponse
    {
        $employeeData = $employee->toArray();
        $employee->delete();

        AuditLog::create([
            'user_id' => Auth::id(),
            'action_type' => 'delete',
            'table_name' => 'employees',
            'record_id' => $employee->id,
            'old_values' => $employeeData,
            'performed_at' => now(),
        ]);

        return redirect()->route('employees.index')->with('success', 'تم حذف الموظف بنجاح');
    }
}
