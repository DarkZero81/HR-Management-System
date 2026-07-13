<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Controller for department management.
 *
 * Handles:
 * - Listing departments with employee counts
 * - Creating, editing, updating, and deleting departments
 * - Viewing department details with employee stats
 */
class DepartmentWebController extends Controller
{
    /**
     * Display a listing of all departments.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $departments = Department::withCount('employees')->get();
        return view('departments.index', compact('departments'));
    }

    /**
     * Display the specified department with its employees and stats.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\View\View
     */
    public function show(Department $department): View
    {
        $department->load(['employees' => function ($query) {
            $query->orderBy('resign_date')->orderByDesc('join_date');
        }]);

        $employees = $department->employees;

        $stats = [
            'total' => $employees->count(),
            'active' => $employees->whereNull('resign_date')->count(),
            'resigned' => $employees->whereNotNull('resign_date')->count(),
            'total_salary' => $employees->sum('base_salary'),
            'avg_performance' => $employees->avg('performance_score'),
        ];

        return view('departments.show', compact('department', 'stats', 'employees'));
    }

    /**
     * Show the form for creating a new department.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        return view('departments.create');
    }

    /**
     * Store a newly created department in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
            'description' => 'nullable|string',
        ]);

        Department::create($validated);

        return redirect()->route('departments.index')->with('success', 'تم إنشاء القسم بنجاح');
    }

    /**
     * Show the form for editing the specified department.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\View\View
     */
    public function edit(Department $department): View
    {
        return view('departments.edit', compact('department'));
    }

    /**
     * Update the specified department in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $department->id,
            'description' => 'nullable|string',
        ]);

        $department->update($validated);

        return redirect()->route('departments.index')->with('success', 'تم تحديث بيانات القسم بنجاح');
    }

    /**
     * Remove the specified department from storage.
     *
     * Prevents deletion if employees are assigned to the department.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Department $department): RedirectResponse
    {
        if ($department->employees()->exists()) {
            return redirect()->route('departments.index')->with('error', 'لا يمكن حذف هذا القسم لارتباط موظفين به حالياً.');
        }

        $department->delete();

        return redirect()->route('departments.index')->with('success', 'تم حذف القسم بنجاح');
    }
}
