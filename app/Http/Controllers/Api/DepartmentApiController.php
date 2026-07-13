<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * API controller for department management.
 *
 * Handles:
 * - CRUD operations for departments via API
 * - Returns JSON responses with Arabic messages
 */
class DepartmentApiController extends Controller
{
    /**
     * Display a listing of all departments.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $departments = Department::withCount('employees')->get();

        return response()->json([
            'success' => true,
            'message' => 'تم جلب الأقسام بنجاح',
            'data' => $departments
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created department.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Display the specified department with its employees.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Update the specified department.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Remove the specified department.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
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
