<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DepartmentWebController extends Controller
{
    // عرض جميع الأقسام
    public function index()
    {
        $departments = Department::withCount('employees')->get();
        return view('departments.index', compact('departments'));
    }

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

    // واجهة إنشاء قسم جديد
    public function create()
    {
        return view('departments.create');
    }

    // حفظ القسم الجديد في قاعدة البيانات
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
            'description' => 'nullable|string',
        ]);

        Department::create($validated);

        return redirect()->route('departments.index')->with('success', 'تم إنشاء القسم بنجاح');
    }

    // واجهة تعديل القسم
    public function edit(Department $department)
    {
        return view('departments.edit', compact('department'));
    }

    // تحديث بيانات القسم
    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $department->id,
            'description' => 'nullable|string',
        ]);

        $department->update($validated);

        return redirect()->route('departments.index')->with('success', 'تم تحديث بيانات القسم بنجاح');
    }

    // حذف القسم
    public function destroy(Department $department): RedirectResponse
    {
        if ($department->employees()->exists()) {
            return redirect()->route('departments.index')->with('error', 'لا يمكن حذف هذا القسم لارتباط موظفين به حالياً.');
        }

        $department->delete();

        return redirect()->route('departments.index')->with('success', 'تم حذف القسم بنجاح');
    }
}
