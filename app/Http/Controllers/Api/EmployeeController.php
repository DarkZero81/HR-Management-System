<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * API controller for employee management.
 *
 * Handles:
 * - CRUD operations for employees via API
 * - Search, filter, and sort functionality
 * - Paginated responses
 */
class EmployeeController extends Controller
{
    /**
     * Display a listing of employees with search, filter, and sort.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $query = Employee::with(['user', 'shift']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('national_id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('shift_id')) {
            $query->where('shift_id', $request->shift_id);
        }

        if ($request->filled('sort_by')) {
            $sortBy = $request->sort_by;
            $sortDirection = $request->sort_direction ?? 'asc';
            $query->orderBy($sortBy, $sortDirection);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $employees = $query->paginate($request->get('per_page', 15));
        return response()->json(['data' => $employees], 200);
    }

    /**
     * Store a newly created employee.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        return DB::transaction(function () use ($request) {
            $validated = $request->validate([
                'user_id' => ['nullable', 'exists:users,id'],
                'shift_id' => ['nullable', 'exists:shifts,id'],
                'first_name' => ['required', 'string', 'max:50'],
                'last_name' => ['required', 'string', 'max:50'],
                'national_id' => ['required', 'string', 'max:50', 'unique:employees'],
                'phone' => ['nullable', 'string', 'max:20'],
                'base_salary' => ['required', 'numeric', 'min:0'],
                'bank_account_iban' => ['nullable', 'string', 'max:50'],
                'join_date' => ['required', 'date'],
                'vacation_balance' => ['sometimes', 'integer', 'min:0'],
                'performance_score' => ['sometimes', 'numeric', 'min:0', 'max:5'],
            ]);

            $employee = Employee::create($validated);
            return response()->json(['data' => $employee], 201);
        });
    }

    /**
     * Display the specified employee with all relations.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $employee = Employee::with(['user', 'shift', 'documents', 'attendanceLogs', 'hrTransactions', 'payrollOrders'])->findOrFail($id);
        return response()->json(['data' => $employee], 200);
    }

    /**
     * Update the specified employee.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        return DB::transaction(function () use ($request, $id) {
            $employee = Employee::findOrFail($id);

            $validated = $request->validate([
                'user_id' => ['sometimes', 'exists:users,id'],
                'shift_id' => ['sometimes', 'exists:shifts,id'],
                'first_name' => ['sometimes', 'string', 'max:50'],
                'last_name' => ['sometimes', 'string', 'max:50'],
                'national_id' => ['sometimes', 'string', 'max:50', 'unique:employees,national_id,' . $id],
                'phone' => ['sometimes', 'nullable', 'string', 'max:20'],
                'base_salary' => ['sometimes', 'numeric', 'min:0'],
                'bank_account_iban' => ['sometimes', 'nullable', 'string', 'max:50'],
                'join_date' => ['sometimes', 'date'],
                'resign_date' => ['sometimes', 'nullable', 'date'],
                'vacation_balance' => ['sometimes', 'integer', 'min:0'],
                'performance_score' => ['sometimes', 'numeric', 'min:0', 'max:5'],
            ]);

            $employee->update($validated);
            return response()->json(['data' => $employee], 200);
        });
    }

    /**
     * Remove the specified employee.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        return DB::transaction(function () use ($id) {
            $employee = Employee::findOrFail($id);
            $employee->delete();
            return response()->json(['message' => 'Employee deleted successfully'], 200);
        });
    }
}
