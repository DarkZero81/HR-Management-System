<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DepartmentApiController extends Controller
{
    // عرض جميع الأقسام عبر الـ API
    public function index()
    {
        $departments = Department::withCount('employees')->get();

        return response()->json([
            'success' => true,
            'message' => 'تم جلب الأقسام بنجاح',
            'data' => $departments
        ], Response::HTTP_OK);
    }

    // حفظ قسم جديد عبر الـ API
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
            'description' => 'nullable|string',
        ]);

        $department = Department::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'تم إنشاء القسم بنجاح',
            'data' => $department
        ], Response::HTTP_CREATED);
    }

    // عرض تفاصيل قسم محدد مع موظفيه
    public function show($id)
    {
        $department = Department::with('employees')->find($id);

        if (!$department) {
            return response()->json([
                'success' => false,
                'message' => 'القسم غير موجود'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'success' => true,
            'message' => 'تم جلب تفاصيل القسم بنجاح',
            'data' => $department
        ], Response::HTTP_OK);
    }

    // تحديث بيانات القسم عبر الـ API
    public function update(Request $request, $id)
    {
        $department = Department::find($id);

        if (!$department) {
            return response()->json([
                'success' => false,
                'message' => 'القسم غير موجود'
            ], Response::HTTP_NOT_FOUND);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $id,
            'description' => 'nullable|string',
        ]);

        $department->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث القسم بنجاح',
            'data' => $department
        ], Response::HTTP_OK);
    }

    // حذف القسم عبر الـ API
    public function destroy($id)
    {
        $department = Department::find($id);

        if (!$department) {
            return response()->json([
                'success' => false,
                'message' => 'القسم غير موجود'
            ], Response::HTTP_NOT_FOUND);
        }

        $department->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف القسم بنجاح'
        ], Response::HTTP_OK);
    }
}
