<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class EmployeeWebController extends Controller
{
    public function index(Request $request): View
    {
        $query = Employee::with(['shift', 'user']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('national_id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('sort_by')) {
            $query->orderBy($request->sort_by, $request->get('sort_direction', 'asc'));
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $employees = $query->paginate(15);
        return view('employees.index', compact('employees'));
    }

    public function create(): View
    {
        return view('employees.create');
    }

    public function show(Employee $employee): View
    {
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
        ]);

        Employee::create($validated);
        return redirect()->route('employees.index')->with('success', 'Employee created');
    }

    public function edit(Employee $employee): View
    {
        return view('employees.edit', compact('employee'));
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
        ]);

        $employee->update($validated);
        return redirect()->route('employees.index')->with('success', 'Employee updated');
    }

    public function destroy(Employee $employee): RedirectResponse
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee deleted');
    }
}
