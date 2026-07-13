<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HrTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * API controller for HR transactions (requests) management.
 *
 * Handles:
 * - Listing all HR transactions
 * - Submitting new requests
 * - Processing approval/rejection
 */
class HrTransactionController extends Controller
{
    /**
     * Display a listing of all HR transactions.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $transactions = HrTransaction::with(['employee.user', 'approver'])->get();
        return response()->json(['data' => $transactions], 200);
    }

    /**
     * Submit a new HR transaction request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitRequest(Request $request): JsonResponse
    {
        return DB::transaction(function () use ($request) {
            $validated = $request->validate([
                'employee_id' => ['required', 'exists:employees,id'],
                'transaction_type' => ['required', 'in:leave,permission,promotion,penalty,transfer'],
                'start_date_time' => ['required', 'date'],
                'end_date_time' => ['required', 'date', 'after:start_date_time'],
                'description' => ['nullable', 'string'],
                'financial_impact' => ['sometimes', 'numeric', 'min:0'],
            ]);

            $transaction = HrTransaction::create($validated);
            return response()->json(['data' => $transaction], 201);
        });
    }

    /**
     * Process approval or rejection of an HR transaction.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function processApproval(Request $request, int $id): JsonResponse
    {
        return DB::transaction(function () use ($request, $id) {
            $transaction = HrTransaction::findOrFail($id);

            $validated = $request->validate([
                'status' => ['required', 'in:approved,rejected'],
                'approved_by' => ['required', 'exists:users,id'],
            ]);

            $transaction->update([
                'status' => $validated['status'],
                'approved_by' => $validated['approved_by'],
            ]);

            return response()->json(['data' => $transaction->load(['employee.user', 'approver'])], 200);
        });
    }
}
