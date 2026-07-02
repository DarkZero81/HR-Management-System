<?php

namespace App\Http\Controllers;

use App\Models\HrTransaction;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class RequestWebController extends Controller
{
    public function adminIndex(): View
    {
        $transactions = HrTransaction::with(['employee.user', 'approver'])
            ->latest()
            ->paginate(15);

        return view('requests.index', compact('transactions'))->with('viewMode', 'admin');
    }

    public function index(): View
    {
        $employeeId = Auth::user()?->employee?->id;
        $transactions = HrTransaction::with(['employee.user', 'approver'])
            ->where('employee_id', $employeeId)
            ->latest()
            ->paginate(15);

        return view('requests.index', compact('transactions'))->with('viewMode', 'employee');
    }

    public function create(): View
    {
        return view('requests.create', ['transaction_types' => ['leave', 'permission', 'promotion', 'penalty', 'transfer']]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'transaction_type' => ['required', 'in:leave,permission,promotion,penalty,transfer'],
            'start_date_time' => ['required', 'date'],
            'end_date_time' => ['required', 'date', 'after:start_date_time'],
            'description' => ['nullable', 'string'],
        ]);

        $validated['employee_id'] = Auth::user()->employee?->id;
        $validated['status'] = 'pending';
        $validated['financial_impact'] = 0;

        HrTransaction::create($validated);

        return redirect()->route('my.requests.index')->with('success', 'تم إرسال الطلب بنجاح وجرى إرساله إلى الإدارة للمراجعة.');
    }

    public function updateStatus(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'in:approved,rejected'],
        ]);

        $transaction = HrTransaction::findOrFail($id);
        $transaction->update([
            'status' => $request->status,
            'approved_by' => Auth::id(),
        ]);

        return back()->with('success', 'تم تحديث حالة الطلب بنجاح.');
    }
}
