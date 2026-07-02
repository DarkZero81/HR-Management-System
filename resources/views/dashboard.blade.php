@extends('layouts.app')

@section('title', 'لوحة التحكم')

@section('content')
<div class="space-y-6">
    <div class="rounded-[32px] border border-slate-200/70 bg-gradient-to-br from-slate-950 to-slate-900 p-6 shadow-[0_30px_100px_-50px_rgba(15,23,42,0.75)] text-white">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.35em] text-slate-300">{{ $viewMode === 'admin' ? 'لوحة الإدارة' : 'الملف الشخصي' }}</p>
                <h1 class="mt-2 text-4xl font-black">مرحباً، {{ auth()->user()->name ?? 'الموظف' }}</h1>
                <p class="mt-2 max-w-2xl text-sm text-slate-300">{{ $viewMode === 'admin' ? 'إدارة الموارد البشرية بسهولة من لوحة التحكم.' : 'تابع حالة الحضور، الرواتب، والطلبات من لوحة التحكم المركزية.' }}</p>
            </div>
            <div class="flex items-center gap-4 rounded-[28px] bg-white/10 p-4">
                <div class="rounded-full border border-white/20 bg-white/10 p-4 text-slate-100">
                    <i data-lucide="{{ $viewMode === 'admin' ? 'shield' : 'user' }}" class="h-6 w-6"></i>
                </div>
                <div>
                    <p class="text-sm text-slate-300">البريد الإلكتروني</p>
                    <p class="text-lg font-semibold">{{ auth()->user()->email ?? 'غير متوفر' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        @php($stats = $viewMode === 'admin' 
            ? [
                ['label' => 'إجمالي الموظفين', 'value' => $employeeCount ?? 0, 'icon' => 'users', 'color' => 'from-cyan-500 to-blue-500'],
                ['label' => 'المناوذج', 'value' => $shiftCount ?? 0, 'icon' => 'clock', 'color' => 'from-emerald-500 to-lime-500'],
                ['label' => 'الطلبات المعلقة', 'value' => $pendingRequests ?? 0, 'icon' => 'alert-circle', 'color' => 'from-amber-500 to-orange-500'],
                ['label' => 'متأخر اليوم', 'value' => $lateMinutes ?? 0, 'suffix' => 'دقيقة', 'icon' => 'timer', 'color' => 'from-violet-500 to-fuchsia-500'],
            ]
            : [
                ['label' => 'الحضور اليوم', 'value' => $attendanceToday ?? 0, 'icon' => 'check-circle', 'color' => 'from-cyan-500 to-blue-500'],
                ['label' => 'رصيد الإجازات', 'value' => $vacationBalance ?? 0, 'suffix' => 'يوم', 'icon' => 'calendar', 'color' => 'from-emerald-500 to-lime-500'],
                ['label' => 'الطلبات المعلقة', 'value' => $pendingRequests ?? 0, 'icon' => 'clock', 'color' => 'from-amber-500 to-orange-500'],
                ['label' => 'آخر راتب', 'value' => ($recentPayrolls?->first()?->net_salary ?? 0) ? number_format((float) $recentPayrolls->first()->net_salary, 2) . ' د.ع' : '0.00 د.ع', 'icon' => 'dollar-sign', 'color' => 'from-violet-500 to-fuchsia-500'],
            ])
        @foreach($stats as $stat)
            <div class="rounded-[28px] border border-slate-200/70 bg-white/90 p-5 shadow-[0_20px_60px_-35px_rgba(15,23,42,0.12)] backdrop-blur">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm font-semibold text-slate-500">{{ $stat['label'] }}</p>
                        <p class="mt-4 text-3xl font-black text-slate-900">{{ $stat['value'] }}{{ $stat['suffix'] ?? '' }}</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-3xl bg-gradient-to-br {{ $stat['color'] }} text-white">
                        <i data-lucide="{{ $stat['icon'] }}" class="h-5 w-5"></i>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if($viewMode === 'admin')
    <div class="grid gap-6 xl:grid-cols-[1.6fr_0.9fr]">
        <div class="rounded-[32px] border border-slate-200/70 bg-white/90 p-6 shadow-[0_25px_80px_-35px_rgba(15,23,42,0.12)] backdrop-blur">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm font-semibold text-slate-500">الطلبات المعلقة</p>
                    <h2 class="text-2xl font-black text-slate-900">طلبات تحتاج موافقة</h2>
                </div>
                <a href="{{ route('requests.index') }}" class="rounded-2xl bg-slate-950 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-900">عرض كل الطلبات</a>
            </div>
            <div class="mt-6 overflow-hidden rounded-[28px] border border-slate-200 bg-slate-50">
                <table class="min-w-full text-right text-sm">
                    <thead class="bg-slate-100 text-slate-600">
                        <tr>
                            <th class="px-5 py-4 font-semibold">الموظف</th>
                            <th class="px-5 py-4 font-semibold">النوع</th>
                            <th class="px-5 py-4 font-semibold">الحالة</th>
                            <th class="px-5 py-4 font-semibold">التاريخ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse($pendingTransactions ?? [] as $transaction)
                            <tr class="transition hover:bg-slate-50">
                                <td class="px-5 py-4 text-slate-900">{{ $transaction->employee->user->name ?? '—' }}</td>
                                <td class="px-5 py-4 text-slate-600">{{ $transaction->transaction_type }}</td>
                                <td class="px-5 py-4"><span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">{{ $transaction->status }}</span></td>
                                <td class="px-5 py-4 text-slate-600">{{ $transaction->created_at->format('Y-m-d') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-10 text-center text-slate-500">لا توجد طلبات معلقة.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @else
    <div class="grid gap-6 xl:grid-cols-[1.6fr_0.9fr]">
        <div class="rounded-[32px] border border-slate-200/70 bg-white/90 p-6 shadow-[0_25px_80px_-35px_rgba(15,23,42,0.12)] backdrop-blur">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm font-semibold text-slate-500">سجل الدوام</p>
                    <h2 class="text-2xl font-black text-slate-900">أحدث الحضور الخاص بك</h2>
                </div>
                <a href="{{ route('my.attendance') }}" class="rounded-2xl bg-slate-950 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-900">عرض كل السجلات</a>
            </div>
            <div class="mt-6 overflow-hidden rounded-[28px] border border-slate-200 bg-slate-50">
                <table class="min-w-full text-right text-sm">
                    <thead class="bg-slate-100 text-slate-600">
                        <tr>
                            <th class="px-5 py-4 font-semibold">التاريخ</th>
                            <th class="px-5 py-4 font-semibold">دخول</th>
                            <th class="px-5 py-4 font-semibold">تأخير</th>
                            <th class="px-5 py-4 font-semibold">الحالة</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse($recentAttendance ?? [] as $log)
                            <tr class="transition hover:bg-slate-50">
                                <td class="px-5 py-4 text-slate-900">{{ $log->log_date }}</td>
                                <td class="px-5 py-4 text-slate-600">{{ $log->check_in ? $log->check_in->format('H:i') : '—' }}</td>
                                <td class="px-5 py-4 text-slate-600">{{ $log->late_minutes }} د</td>
                                <td class="px-5 py-4"><span class="rounded-full bg-cyan-50 px-3 py-1 text-xs font-semibold text-cyan-700">{{ $log->status }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-10 text-center text-slate-500">لم تقم بعد بأي حركة دوام.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-[32px] border border-slate-200/70 bg-slate-950 p-6 text-white shadow-[0_25px_80px_-35px_rgba(15,23,42,0.3)]">
                <p class="text-sm font-semibold text-slate-400">خدمة الموظف</p>
                <h2 class="mt-2 text-2xl font-black">إجراء سريع للطلبات</h2>
                <p class="mt-3 text-sm text-slate-300">تقدم بطلب جديد أو راجع مستنداتك الخاصة بسهولة وسرعة.</p>
                <div class="mt-6 grid gap-3">
                    <a href="{{ route('my.requests.create') }}" class="rounded-2xl bg-white/10 px-4 py-3 text-sm font-semibold text-white transition hover:bg-white/20">تقديم طلب جديد</a>
                    <a href="{{ route('my.documents') }}" class="rounded-2xl bg-white/10 px-4 py-3 text-sm font-semibold text-white transition hover:bg-white/20">عرض مستنداتي</a>
                </div>
            </div>

            <div class="rounded-[32px] border border-slate-200/70 bg-white/90 p-6 shadow-[0_25px_80px_-35px_rgba(15,23,42,0.12)] backdrop-blur">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-slate-500">آخر الرواتب</p>
                        <h2 class="text-xl font-black text-slate-900">المعالجة الأخيرة</h2>
                    </div>
                    <a href="{{ route('payroll.index') }}" class="text-sm font-semibold text-blue-600">عرض الرواتب</a>
                </div>
                <div class="mt-4 space-y-3">
                    @forelse($recentPayrolls ?? [] as $payroll)
                        <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4">
                            <div class="flex items-center justify-between">
                                <span class="font-semibold text-slate-900">{{ $payroll->salary_month }}</span>
                                <span class="rounded-full bg-cyan-100 px-3 py-1 text-xs font-semibold text-cyan-700">{{ $payroll->payment_status }}</span>
                            </div>
                            <p class="mt-2 text-sm text-slate-600">صافي الراتب: {{ number_format((float) $payroll->net_salary, 2) }} د.ع</p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">لا توجد كشوف رواتب حديثة.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection