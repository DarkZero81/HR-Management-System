<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\HrTransaction;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RequestWebController extends Controller
{
    private const TRANSACTION_TYPES = ['leave', 'permission', 'promotion', 'penalty', 'transfer'];
    private const ADMIN_ROLES = ['admin', 'hr', 'manager'];

    /**
     * يعرض قائمة الطلبات: الموظف العادي يرى طلباته فقط، والإداري (admin/hr/manager) يرى كل الطلبات.
     * هذا الميثود الواحد يخدم كلاً من راوت "my.requests.index" وراوت "requests.index" الإداري.
     */
    public function index(): View
    {
        $employee = Auth::user()?->employee;
        $isAdmin = $this->isAdminUser();
        $isManager = $this->isManagerUser();

        $query = HrTransaction::query()
            ->with(['employee.user', 'approver']);

        // Manager sees only their department's requests
        if ($isManager && ! $isAdmin) {
            $managerDepartmentId = $employee?->department_id;
            $query->whereHas('employee', fn($q) => $q->where('department_id', $managerDepartmentId));
        } elseif (! $isAdmin) {
            $query->where('employee_id', $employee?->id);
        }

        $transactions = $query->latest()->paginate($isAdmin || $isManager ? 8 : 3);

        return view('requests.index', [
            'transactions' => $transactions,
            'transaction_types' => self::TRANSACTION_TYPES,
        ]);
    }

    public function create(): View
    {
        return view('requests.create', [
            'transaction_types' => self::TRANSACTION_TYPES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'transaction_type' => ['required', 'in:' . implode(',', self::TRANSACTION_TYPES)],
            'start_date_time' => ['required', 'date'],
            'end_date_time' => ['required', 'date', 'after_or_equal:start_date_time'],
            'description' => ['nullable', 'string', 'max:1000'],
            'financial_impact' => ['nullable', 'numeric', 'min:0'],
        ]);

        $employee = Auth::user()?->employee;

        if (! $employee) {
            return back()->with('error', 'لا يمكن تقديم طلب، حسابك غير مرتبط بملف موظف.');
        }

        // التحقق من كفاية رصيد الإجازة المتبقي قبل قبول الطلب
        if ($validated['transaction_type'] === 'leave') {
            $days = $this->calculateDaysRequested($validated['start_date_time'], $validated['end_date_time']);

            if ($days > $employee->vacation_balance) {
                return back()->withInput()->with('error', "رصيد إجازاتك الحالي ({$employee->vacation_balance} يوم) لا يكفي لتغطية الطلب ({$days} يوم).");
            }
        }

        $transaction = HrTransaction::create([
            'employee_id' => $employee->id,
            'transaction_type' => $validated['transaction_type'],
            'start_date_time' => $validated['start_date_time'],
            'end_date_time' => $validated['end_date_time'],
            'description' => $validated['description'] ?? null,
            'financial_impact' => $validated['financial_impact'] ?? 0.00,
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

        // مهم: الموظف العادي يرجع لصفحة "طلباتي" وليس صفحة الإدارة (المحمية بصلاحيات admin/hr/manager)
        return redirect()->route('my.requests.index')->with('success', 'تم تقديم الطلب بنجاح وهو قيد المراجعة حالياً.');
    }

    /**
     * اعتماد أو رفض الطلب (صلاحية إدارية فقط). يستخدم Route Model Binding مباشرة.
     */
    public function update(Request $request, HrTransaction $transaction): RedirectResponse
    {
        $isAdmin = $this->isAdminUser();
        $isManager = $this->isManagerUser();

        if (! $isAdmin && ! $isManager) {
            return back()->with('error', 'عذراً، لا تمتلك الصلاحيات الإدارية الكافية لتعديل حالة الطلبات.');
        }

        // Manager can only manage requests from their own department
        if ($isManager && ! $isAdmin) {
            $managerDepartmentId = Auth::user()?->employee?->department_id;
            $employeeDepartmentId = $transaction->employee?->department_id;

            if ($managerDepartmentId !== $employeeDepartmentId) {
                return back()->with('error', 'عذراً، لا يمكنك مراجعة طلبات موظفين لا ينتمون إلى قسمك.');
            }
        }

        $validated = $request->validate([
            'status' => ['required', 'in:approved,rejected,pending'],
        ]);

        return DB::transaction(function () use ($validated, $transaction) {
            // قفل الصف لمنع تعارض معالجة نفس الطلب من طلبين متزامنين
            $transaction = HrTransaction::with('employee')->lockForUpdate()->findOrFail($transaction->id);

            $previousStatus = $transaction->status;
            $newStatus = $validated['status'];

            if ($previousStatus === $newStatus) {
                return back()->with('error', 'الطلب موجود بالفعل بهذه الحالة.');
            }

            $employee = $transaction->employee;

            if ($transaction->transaction_type === 'leave' && $employee) {
                $days = $this->calculateDaysRequested($transaction->start_date_time, $transaction->end_date_time);

                // خصم الرصيد عند الاعتماد لأول مرة
                if ($newStatus === 'approved' && $previousStatus !== 'approved') {
                    if ($days > $employee->vacation_balance) {
                        return back()->with('error', 'لا يمكن الموافقة: رصيد إجازات الموظف الحالي غير كافٍ.');
                    }
                    $employee->decrement('vacation_balance', $days);
                }

                // إعادة الرصيد عند التراجع عن موافقة سابقة
                if ($previousStatus === 'approved' && $newStatus !== 'approved') {
                    $employee->increment('vacation_balance', $days);
                }
            }

            $oldValues = $transaction->getOriginal();

            $transaction->update([
                'status' => $newStatus,
                'approved_by' => Auth::id(),
            ]);

            AuditLog::create([
                'user_id' => Auth::id(),
                'action_type' => 'update',
                'table_name' => 'hr_transactions',
                'record_id' => $transaction->id,
                'old_values' => $oldValues,
                'new_values' => $transaction->fresh()->toArray(),
                'performed_at' => now(),
            ]);

            return back()->with('success', 'تم تحديث حالة الطلب ومعالجة التأثيرات بنجاح.');
        });
    }

    private function isAdminUser(): bool
    {
        $role = strtolower(Auth::user()?->role?->role_name ?? '');
        return in_array($role, self::ADMIN_ROLES, true);
    }

    private function isManagerUser(): bool
    {
        return strtolower(Auth::user()?->role?->role_name ?? '') === 'manager';
    }

    /**
     * يحسب عدد أيام الإجازة المطلوبة شاملاً يوم البداية والنهاية.
     */
    private function calculateDaysRequested(string $start, string $end): int
    {
        $startDate = Carbon::parse($start)->startOfDay();
        $endDate = Carbon::parse($end)->startOfDay();

        return $startDate->diffInDays($endDate) + 1;
    }
}
