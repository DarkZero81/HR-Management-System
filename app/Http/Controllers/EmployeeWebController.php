<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\AuditLog;
use App\Models\Department;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Mpdf\Mpdf;

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

        if ($request->filled('shift_id')) {
            $query->where('shift_id', $request->shift_id);
        }

        $sortable = ['first_name', 'last_name', 'national_id', 'base_salary', 'join_date', 'created_at'];

        if ($request->filled('sort_by') && in_array($request->sort_by, $sortable, true)) {
            $query->orderBy($request->sort_by, $request->get('sort_direction') === 'desc' ? 'desc' : 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $employees = $query->paginate(15);
        $departments = Cache::remember('departments.all', 3600, fn() => Department::orderBy('name')->get());
        $shifts = Cache::remember('shifts.all', 3600, fn() => Shift::orderBy('shift_name')->get());

        if (! $departments instanceof \Illuminate\Support\Collection) {
            $departments = Department::orderBy('name')->get();
        }

        if (! $shifts instanceof \Illuminate\Support\Collection) {
            $shifts = Shift::orderBy('shift_name')->get();
        }

        return view('employees.index', compact('employees', 'departments', 'shifts'));
    }

    public function create(): View
    {
        $departments = Cache::remember('departments.all', 3600, fn() => Department::orderBy('name')->get());
        $shifts = Cache::remember('shifts.all', 3600, fn() => Shift::orderBy('shift_name')->get());
        $users = User::whereDoesntHave('employee')->get();

        if (! $departments instanceof \Illuminate\Support\Collection) {
            $departments = Department::orderBy('name')->get();
        }

        if (! $shifts instanceof \Illuminate\Support\Collection) {
            $shifts = Shift::orderBy('shift_name')->get();
        }

        return view('employees.create', compact('departments', 'shifts', 'users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id'            => ['required', 'exists:users,id', 'unique:employees,user_id'],
            'first_name'         => ['required', 'string', 'max:50'],
            'last_name'          => ['required', 'string', 'max:50'],
            'national_id'         => ['required', 'string', 'max:50', 'unique:employees,national_id'],
            'phone'             => ['nullable', 'string', 'max:20'],
            'base_salary'        => ['required', 'numeric', 'min:0'],
            'join_date'          => ['required', 'date'],
            'department_id'      => ['nullable', 'exists:departments,id'],
            'shift_id'           => ['nullable', 'exists:shifts,id'],
            'vacation_balance'   => ['nullable', 'integer', 'min:0'],
            'bank_account_iban'  => ['nullable', 'string', 'max:50'],
            'date_of_birth'      => ['nullable', 'date', 'before_or_equal:today'],
            'place_of_birth'      => ['nullable', 'string', 'max:100'],
            'education_level'     => ['nullable', 'in:high_school,diploma,bachelor,master,phd,other'],
            'marital_status'      => ['nullable', 'in:single,married,divorced,widowed'],
            'nationality'         => ['nullable', 'string', 'max:50'],
            'address'             => ['nullable', 'string', 'max:255'],
            'emergency_contact_name' => ['nullable', 'string', 'max:100'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:20'],
            'job_title'           => ['nullable', 'string', 'max:100'],
            'contract_end_date'   => ['nullable', 'date', 'after_or_equal:join_date'],
            'insurance_number'    => ['nullable', 'string', 'max:50'],
        ]);

        $employee = Employee::create($validated);

        AuditLog::create([
            'user_id'     => Auth::id(),
            'action_type' => 'create',
            'table_name'  => 'employees',
            'record_id'   => $employee->id,
            'old_values'  => null,
            'new_values'  => $employee->toArray(),
            'performed_at'=> now(),
        ]);

        return redirect()->route('employees.index')->with('success', 'تم إضافة الموظف بنجاح وإنشاء الملف الوظيفي.');
    }

    public function edit(Employee $employee): View
    {
        $departments = Cache::remember('departments.all', 3600, fn() => Department::orderBy('name')->get());
        $shifts = Cache::remember('shifts.all', 3600, fn() => Shift::orderBy('shift_name')->get());

        if (! $departments instanceof \Illuminate\Support\Collection) {
            $departments = Department::orderBy('name')->get();
        }

        if (! $shifts instanceof \Illuminate\Support\Collection) {
            $shifts = Shift::orderBy('shift_name')->get();
        }

        return view('employees.edit', compact('employee', 'departments', 'shifts'));
    }

    public function update(Request $request, Employee $employee): RedirectResponse
    {
        $validated = $request->validate([
            'first_name'         => ['required', 'string', 'max:50'],
            'last_name'          => ['required', 'string', 'max:50'],
            'national_id'         => ['required', 'string', 'max:50', 'unique:employees,national_id,' . $employee->id],
            'phone'              => ['nullable', 'string', 'max:20'],
            'base_salary'        => ['required', 'numeric', 'min:0'],
            'join_date'          => ['required', 'date'],
            'resign_date'        => ['nullable', 'date', 'after_or_equal:join_date'],
            'department_id'      => ['nullable', 'exists:departments,id'],
            'shift_id'           => ['nullable', 'exists:shifts,id'],
            'vacation_balance'   => ['required', 'integer', 'min:0'],
            'bank_account_iban'  => ['nullable', 'string', 'max:50'],
            'date_of_birth'      => ['nullable', 'date', 'before_or_equal:today'],
            'place_of_birth'      => ['nullable', 'string', 'max:100'],
            'education_level'     => ['nullable', 'in:high_school,diploma,bachelor,master,phd,other'],
            'marital_status'      => ['nullable', 'in:single,married,divorced,widowed'],
            'nationality'         => ['nullable', 'string', 'max:50'],
            'address'             => ['nullable', 'string', 'max:255'],
            'emergency_contact_name' => ['nullable', 'string', 'max:100'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:20'],
            'job_title'           => ['nullable', 'string', 'max:100'],
            'contract_end_date'   => ['nullable', 'date', 'after_or_equal:join_date'],
            'insurance_number'    => ['nullable', 'string', 'max:50'],
            'avatar'             => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('avatar')) {
            if ($employee->avatar && Storage::disk('public')->exists($employee->avatar)) {
                Storage::disk('public')->delete($employee->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

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
        if ($employee->attendanceLogs()->exists() || $employee->hrTransactions()->exists() || $employee->payrollOrders()->exists()) {
            return redirect()->route('employees.index')->with('error', 'لا يمكن حذف هذا الموظف لارتباطه بسجلات حضور أو طلبات أو رواتب تاريخية. يفضل تعيين تاريخ استقالة.');
        }

        $employeeData = $employee->toArray();

        if ($employee->avatar && Storage::disk('public')->exists($employee->avatar)) {
            Storage::disk('public')->delete($employee->avatar);
        }

        $employee->delete();

        AuditLog::create([
            'user_id'     => Auth::id(),
            'action_type' => 'delete',
            'table_name'  => 'employees',
            'record_id'   => $employee->id,
            'old_values'  => $employeeData,
            'new_values'  => null,
            'performed_at'=> now(),
        ]);

        return redirect()->route('employees.index')->with('success', 'تم حذف ملف الموظف نهائياً من النظام.');
    }

    public function show(Employee $employee): View
    {
        return view('employees.show', compact('employee'));
    }

    public function downloadPdf(Employee $employee): \Illuminate\Http\Response
    {
        if (!class_exists(Mpdf::class)) {
            abort(500, 'مكتبة mPDF غير مثبتة.');
        }

        $employee->load('user', 'department', 'shift');

        $html = view('employees.pdf', compact('employee'))->render();

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font' => 'dejavusans',
        ]);

        $mpdf->WriteHTML($html);

        return new \Illuminate\Http\Response($mpdf->Output('employee-' . $employee->id . '.pdf', 'D'), 200, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
