<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\HrTransaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RequestWebController extends Controller
{
    public function index(): View
    {
        // ====== التعديل هنا ======
        // أضفنا تعريف أنواع الطلبات لاستخدامها في الفلتر
        $transaction_types = ['leave', 'permission', 'promotion', 'penalty', 'transfer'];
        // ====== نهاية التعديل ======

        $transactions = HrTransaction::query()
            ->with(['employee.user'])
            ->latest()
            ->paginate(10);

        // ====== التعديل هنا ======
        // أضفنا $transaction_types إلى compact
        return view('requests.index', compact('transactions', 'transaction_types'));
        // ====== نهاية التعديل ======
    }

    public function create(): View
    {
        return view('requests.create', [
            'transaction_types' => ['leave', 'permission', 'promotion', 'penalty', 'transfer'],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'transaction_type' => ['required', 'in:leave,permission,promotion,penalty,transfer'],
            'start_date_time' => ['required', 'date'],
            'end_date_time' => ['required', 'date', 'after:start_date_time'],
            'description' => ['nullable', 'string', 'max:1000'],
            'financial_impact' => ['nullable', 'numeric', 'min:0'],
        ]);

        $employee = Auth::user()?->employee;

        if (! $employee) {
            return back()->with('error', 'لا يوجد ملف موظف مرتبط بهذا الحساب.');
        }

        $transaction = HrTransaction::create([
            'employee_id' => $employee->id,
            'transaction_type' => $validated['transaction_type'],
            'start_date_time' => $validated['start_date_time'],
            'end_date_time' => $validated['end_date_time'],
            'description' => $validated['description'] ?? null,
            'financial_impact' => $validated['financial_impact'] ?? 0,
            'status' => 'pending',
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action_type' => 'create',
            'table_name' => 'hr_transactions',
            'record_id' => $transaction->id,
            'new_values' => $transaction->toArray(),
            'performed_at' => now(),
        ]);

        return redirect()->route('my.requests.index')->with('success', 'تم إرسال الطلب بنجاح وهو قيد المراجعة.');
    }

    public function adminIndex(): View
    {
        // ====== التعديل هنا ======
        // أضفنا تعريف أنواع الطلبات لاستخدامها في الفلتر
        $transaction_types = ['leave', 'permission', 'promotion', 'penalty', 'transfer'];
        // ====== نهاية التعديل ======

        $transactions = HrTransaction::query()
            ->with(['employee.user', 'approver'])
            ->latest()
            ->paginate(15);

        // ====== التعديل هنا ======
        // أضفنا $transaction_types إلى compact
        return view('requests.index', compact('transactions', 'transaction_types'));
        // ====== نهاية التعديل ======
    }

    public function updateStatus(Request $request, int $id): RedirectResponse
    {
        $transaction = HrTransaction::findOrFail($id);

        $validated = $request->validate([
            'status' => ['required', 'in:approved,rejected,pending'],
        ]);

        $transaction->update([
            'status' => $validated['status'],
            'approved_by' => Auth::id(),
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action_type' => 'update',
            'table_name' => 'hr_transactions',
            'record_id' => $transaction->id,
            'old_values' => $transaction->getOriginal(),
            'new_values' => $transaction->fresh()->toArray(),
            'performed_at' => now(),
        ]);

        return back()->with('success', 'تم تحديث حالة الطلب بنجاح.');
    }
}
