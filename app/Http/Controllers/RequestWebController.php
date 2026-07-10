<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\HrTransaction;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Mpdf\Mpdf;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RequestWebController extends Controller
{
    private const TRANSACTION_TYPES = ['leave', 'permission', 'promotion', 'penalty', 'transfer'];

    private const ADMIN_ROLES = ['admin', 'hr', 'manager'];

    /**
     * يعرض قائمة الطلبات: الموظف العادي يرى طلباته فقط، والإداري (admin/hr/manager) يرى كل الطلبات.
     * هذا الميثود الواحد يخدم كلاً من راوت "my.requests.index" وراوت "requests.index" الإداري.
     */
    public function index(Request $request): View
    {
        $employee = Auth::user()?->employee;
        $isAdmin = $this->isAdminUser();
        $isManager = $this->isManagerUser();

        $baseQuery = HrTransaction::query()
            ->with(['employee.user', 'approver'])
            ->where('status', '!=', 'draft');

        if ($isManager && ! $isAdmin) {
            $managerDepartmentId = $employee?->department_id;
            $baseQuery->whereHas('employee', fn ($q) => $q->where('department_id', $managerDepartmentId));
        } elseif (! $isAdmin) {
            $baseQuery->where('employee_id', $employee?->id);
        }

        $stats = [
            'total' => (clone $baseQuery)->count(),
            'pending' => (clone $baseQuery)->where('status', 'pending')->count(),
            'approved' => (clone $baseQuery)->where('status', 'approved')->count(),
            'rejected' => (clone $baseQuery)->where('status', 'rejected')->count(),
        ];

        if ($request->filled('status')) {
            $baseQuery->where('status', $request->string('status'));
        }

        if ($request->filled('transaction_type')) {
            $baseQuery->where('transaction_type', $request->string('transaction_type'));
        }

        if ($request->filled('from_date')) {
            $baseQuery->whereDate('start_date_time', '>=', $request->string('from_date'));
        }

        if ($request->filled('to_date')) {
            $baseQuery->whereDate('end_date_time', '<=', $request->string('to_date'));
        }

        if ($request->filled('search')) {
            $search = $request->string('search');
            $baseQuery->where(function ($q) use ($search) {
                $q->where('description', 'like', '%'.$search.'%')
                    ->orWhereHas('employee.user', fn ($uq) => $uq->where('name', 'like', '%'.$search.'%'));
            });
        }

        $transactions = $baseQuery->latest()->paginate($isAdmin || $isManager ? 10 : 5)->appends($request->query());

        $pendingTransactions = (clone $baseQuery)->where('status', 'pending')->latest()->take(5)->get();

        $exportRoute = $isAdmin || $isManager ? 'requests.export.csv' : 'my.requests.export.csv';
        $basePath = $isAdmin || $isManager ? '/requests' : '/my/requests';

        if ($request->filled('sort_by')) {
            $allowedSortColumns = ['transaction_type', 'status', 'start_date_time', 'end_date_time', 'created_at'];
            $sortColumn = in_array($request->sort_by, $allowedSortColumns, true) ? $request->sort_by : 'created_at';
            $sortDirection = $request->filled('sort_direction') && in_array(strtolower($request->sort_direction), ['asc', 'desc'], true) ? strtolower($request->sort_direction) : 'desc';
            $baseQuery->orderBy($sortColumn, $sortDirection);
        } else {
            $baseQuery->orderBy('created_at', 'desc');
        }

        $calendarEvents = [];
        foreach ($transactions as $t) {
            $colors = match ($t->status) {
                'pending' => '#f59e0b',
                'approved' => '#10b981',
                'rejected' => '#ef4444',
                default => '#6b7280',
            };
            $title = match ($t->transaction_type) {
                'leave' => 'إجازة',
                'permission' => 'إذن',
                'promotion' => 'ترقية',
                'penalty' => 'عقوبة',
                'transfer' => 'نقل',
                default => $t->transaction_type,
            };
            $calendarEvents[] = [
                'title' => $title.' - '.($t->employee->full_name ?? ''),
                'start' => $t->start_date_time,
                'end' => $t->end_date_time,
                'backgroundColor' => $colors,
                'borderColor' => $colors,
            ];
        }

        return view('requests.index', [
            'transactions' => $transactions,
            'transaction_types' => self::TRANSACTION_TYPES,
            'stats' => $stats,
            'pendingTransactions' => $pendingTransactions,
            'filters' => $request->only(['status', 'transaction_type', 'from_date', 'to_date', 'search', 'sort_by', 'sort_direction']),
            'exportRoute' => $exportRoute,
            'isAdmin' => $isAdmin,
            'isManager' => $isManager,
            'basePath' => $basePath,
            'calendarEvents' => $calendarEvents,
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
            'transaction_type' => ['required', 'in:'.implode(',', self::TRANSACTION_TYPES)],
            'start_date_time' => ['required', 'date'],
            'end_date_time' => ['required', 'date', 'after_or_equal:start_date_time'],
            'description' => ['nullable', 'string', 'max:1000'],
            'financial_impact' => ['nullable', 'numeric', 'min:0'],
            'status' => ['nullable', 'in:pending,draft'],
        ]);

        $employee = Auth::user()?->employee;

        if (! $employee) {
            return back()->with('error', 'لا يمكن تقديم طلب، حسابك غير مرتبط بملف موظف.');
        }

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
            'status' => $validated['status'] ?? 'pending',
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

    public function destroy(Request $request, HrTransaction $transaction): RedirectResponse
    {
        $employee = Auth::user()?->employee;
        $isAdmin = $this->isAdminUser();

        if (! $isAdmin && $transaction->employee_id !== $employee?->id) {
            return back()->with('error', 'عذراً، لا يمكنك إلغاء طلب لا ينتمي إليك.');
        }

        if ($transaction->status !== 'pending') {
            return back()->with('error', 'لا يمكن إلغاء الطلب بعد اعتماده أو رفضه.');
        }

        $transactionData = $transaction->toArray();
        $transaction->delete();

        AuditLog::create([
            'user_id' => Auth::id(),
            'action_type' => 'delete',
            'table_name' => 'hr_transactions',
            'record_id' => $transaction->id,
            'old_values' => $transactionData,
            'performed_at' => now(),
        ]);

        return back()->with('success', 'تم إلغاء الطلب بنجاح.');
    }

    public function show(HrTransaction $transaction): View
    {
        $employee = Auth::user()?->employee;
        $isAdmin = $this->isAdminUser();

        if (! $isAdmin && $transaction->employee_id !== $employee?->id) {
            abort(403, 'عذراً، لا يمكنك عرض طلب لا ينتمي إليك.');
        }

        $transaction->load('employee.user', 'employee.department', 'employee.shift', 'approver');

        $timeline = collect([
            [
                'label' => 'تم إنشاء الطلب',
                'description' => 'تم تقديم الطلب وهو قيد المراجعة',
                'date' => $transaction->created_at,
                'icon' => 'plus-circle',
                'color' => 'blue',
            ],
        ]);

        if ($transaction->approved_by) {
            $actionLabel = $transaction->status === 'approved' ? 'تم اعتماد الطلب' : 'تم رفض الطلب';
            $actionDescription = $transaction->status === 'approved' ? 'تمت الموافقة على الطلب وتطبيق التأثيرات' : 'تم رفض الطلب بدون تطبيق تأثيرات';
            $actionIcon = $transaction->status === 'approved' ? 'check-circle' : 'x-circle';
            $actionColor = $transaction->status === 'approved' ? 'emerald' : 'rose';

            $timeline->push([
                'label' => $actionLabel,
                'description' => $actionDescription.' بواسطة '.($transaction->approver->name ?? 'النظام'),
                'date' => $transaction->updated_at,
                'icon' => $actionIcon,
                'color' => $actionColor,
            ]);
        }

        $editHistory = AuditLog::query()
            ->where('table_name', 'hr_transactions')
            ->where('record_id', $transaction->id)
            ->orderByDesc('performed_at')
            ->take(20)
            ->get();

        $previousRequests = HrTransaction::query()
            ->where('employee_id', $transaction->employee_id)
            ->where('id', '!=', $transaction->id)
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        $shareUrl = url($isAdmin ? '/requests/'.$transaction->id : '/my/requests/'.$transaction->id);

        return view('requests.show', [
            'transaction' => $transaction,
            'timeline' => $timeline,
            'editHistory' => $editHistory,
            'previousRequests' => $previousRequests,
            'shareUrl' => $shareUrl,
            'isAdmin' => $isAdmin,
            'isPending' => $transaction->status === 'pending',
            'basePath' => $isAdmin ? '/requests' : '/my/requests',
            'pdfUrl' => $transaction->id ? route($isAdmin ? 'requests.pdf.admin' : 'my.requests.pdf.employee', $transaction->id) : '#',            'csvUrl' => url(($isAdmin ? '/requests' : '/my/requests').'/export-csv?'.http_build_query(request()->only(['status', 'transaction_type', 'from_date', 'to_date', 'search']))),
        ]);
    }

    public function downloadPdf(HrTransaction $transaction): Response
    {
        if (! class_exists(Mpdf::class)) {
            abort(500, 'مكتبة mPDF غير مثبتة.');
        }

        $employee = Auth::user()?->employee;
        $isAdmin = $this->isAdminUser();

        if (! $isAdmin && $transaction->employee_id !== $employee?->id) {
            abort(403, 'عذراً، لا يمكنك تصدير طلب لا ينتمي إليك.');
        }

        $transaction->load('employee.user', 'employee.department', 'employee.shift', 'approver');

        $html = view('requests.pdf', compact('transaction'))->render();

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font' => 'dejavusans',
        ]);

        $mpdf->WriteHTML($html);

        return new Response($mpdf->Output('request-'.$transaction->id.'.pdf', 'D'), 200, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    public function downloadCsv(Request $request): StreamedResponse
    {
        $employee = Auth::user()?->employee;
        $isAdmin = $this->isAdminUser();
        $isManager = $this->isManagerUser();

        if (! $isAdmin && ! $isManager) {
            abort(403, 'عذراً، لا يمكنك تصدير البيانات.');
        }

        $query = HrTransaction::query()
            ->with(['employee.user', 'approver']);

        if ($isManager && ! $isAdmin) {
            $managerDepartmentId = $employee?->department_id;
            $query->whereHas('employee', fn ($q) => $q->where('department_id', $managerDepartmentId));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('transaction_type')) {
            $query->where('transaction_type', $request->string('transaction_type'));
        }

        if ($request->filled('from_date')) {
            $query->whereDate('start_date_time', '>=', $request->string('from_date'));
        }

        if ($request->filled('to_date')) {
            $query->whereDate('end_date_time', '<=', $request->string('to_date'));
        }

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', '%'.$search.'%')
                    ->orWhereHas('employee.user', fn ($uq) => $uq->where('name', 'like', '%'.$search.'%'));
            });
        }

        $transactions = $query->latest()->get();

        $headers = [
            'مقدم الطلب',
            'البريد الإلكتروني',
            'نوع الطلب',
            'الحالة',
            'تاريخ البداية',
            'تاريخ النهاية',
            'المدة (يوم)',
            'التأثير المالي',
            'المراجع',
            'ملاحظات',
            'تاريخ الإنشاء',
        ];

        $callback = function () use ($transactions, $headers) {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF");
            fputcsv($file, $headers, ',');

            foreach ($transactions as $transaction) {
                $row = [
                    $transaction->employee->full_name ?? '—',
                    $transaction->employee->email ?? '',
                    match ($transaction->transaction_type) {
                        'leave' => 'إجازة',
                        'permission' => 'إذن',
                        'promotion' => 'ترقية',
                        'penalty' => 'عقوبة',
                        'transfer' => 'نقل',
                        default => $transaction->transaction_type,
                    },
                    match ($transaction->status) {
                        'pending' => 'معلقة',
                        'approved' => 'موافق عليها',
                        'rejected' => 'مرفوضة',
                        default => $transaction->status,
                    },
                    Carbon::parse($transaction->start_date_time)->format('Y-m-d H:i'),
                    Carbon::parse($transaction->end_date_time)->format('Y-m-d H:i'),
                    Carbon::parse($transaction->start_date_time)->diffInDays(Carbon::parse($transaction->end_date_time)) + 1 .' يوم',
                    number_format($transaction->financial_impact, 2).' ل.س',
                    $transaction->approver->name ?? '',
                    $transaction->description ?? '',
                    $transaction->created_at->format('Y-m-d H:i'),
                ];

                fputcsv($file, $row, ',');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="requests-'.now()->format('Y-m-d-H-i').'.csv"',
        ]);
    }

    public function updateStatus(Request $request, HrTransaction $transaction): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:approved,rejected'],
        ]);

        $isAdmin = $this->isAdminUser();
        $isManager = $this->isManagerUser();

        if (! $isAdmin && ! $isManager) {
            return back()->with('error', 'عذراً، لا تمتلك الصلاحيات الإدارية الكافية.');
        }

        if ($isManager && ! $isAdmin) {
            $managerDepartmentId = Auth::user()?->employee?->department_id;
            $employeeDepartmentId = $transaction->employee?->department_id;

            if ($managerDepartmentId !== $employeeDepartmentId) {
                return back()->with('error', 'عذراً، لا يمكنك مراجعة طلبات موظفين لا ينتمون إلى قسمك.');
            }
        }

        return DB::transaction(function () use ($validated, $transaction) {
            $transaction = HrTransaction::with('employee')->lockForUpdate()->findOrFail($transaction->id);

            $previousStatus = $transaction->status;
            $newStatus = $validated['status'];

            if ($previousStatus === $newStatus) {
                return back()->with('error', 'الطلب موجود بالفعل بهذه الحالة.');
            }

            $employee = $transaction->employee;

            if ($transaction->transaction_type === 'leave' && $employee) {
                $days = $this->calculateDaysRequested($transaction->start_date_time, $transaction->end_date_time);

                if ($newStatus === 'approved' && $previousStatus !== 'approved') {
                    if ($days > $employee->vacation_balance) {
                        return back()->with('error', 'لا يمكن الموافقة: رصيد إجازات الموظف الحالي غير كافٍ.');
                    }
                    $employee->decrement('vacation_balance', $days);
                }

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
