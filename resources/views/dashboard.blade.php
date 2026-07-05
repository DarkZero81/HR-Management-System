@extends('layouts.app')

@section('title', 'الرئيسية')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-xs font-black uppercase tracking-[0.35em] text-slate-400">لوحة القيادة</p>
            <h1 class="text-2xl md:text-3xl font-black text-white mt-1">أهلاً بك، {{ auth()->user()->name ?? 'المستخدم' }}!</h1>
            <p class="text-sm text-slate-400 mt-1">تابع الحضور، الطلبات، والرواتب من واجهة حديثة.</p>
        </div>
        <div class="shrink-0">
            <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-white rounded-xl font-medium transition-all border border-white/10">
                <i data-lucide="user" class="w-4 h-4"></i>
                <span>الملف الشخصي</span>
            </a>
        </div>
    </div>

    @if ($viewMode === 'admin')
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 md:gap-6">
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100 hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center">
                        <i data-lucide="users" class="w-6 h-6 text-blue-600"></i>
                    </div>
                    <span class="text-xs text-slate-400 font-medium">الإجمالي</span>
                </div>
                <p class="text-3xl font-bold text-slate-800">{{ $employeeCount }}</p>
                <p class="text-sm text-slate-500 mt-2">موظف</p>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100 hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center">
                        <i data-lucide="check-circle" class="w-6 h-6 text-emerald-600"></i>
                    </div>
                    <span class="text-xs text-slate-400 font-medium">اليوم</span>
                </div>
                <p class="text-3xl font-bold text-slate-800">{{ $todayAttendance }}</p>
                <p class="text-sm text-slate-500 mt-2">حاضر اليوم</p>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100 hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center">
                        <i data-lucide="clock" class="w-6 h-6 text-amber-600"></i>
                    </div>
                    <span class="text-xs text-slate-400 font-medium">تأخير</span>
                </div>
                <p class="text-3xl font-bold text-slate-800">{{ $lateMinutes }}</p>
                <p class="text-sm text-slate-500 mt-2">دقيقة تأخير</p>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100 hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-violet-100 flex items-center justify-center">
                        <i data-lucide="clipboard-list" class="w-6 h-6 text-violet-600"></i>
                    </div>
                    <span class="text-xs text-slate-400 font-medium">معلق</span>
                </div>
                <p class="text-3xl font-bold text-slate-800">{{ $pendingRequests }}</p>
                <p class="text-sm text-slate-500 mt-2">طلب معلق</p>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
            <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
                <h2 class="text-lg font-black text-slate-800 mb-4">آخر حركات الحضور</h2>
                <div class="space-y-3">
                    @forelse($recentAttendance as $log)
                        <div class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                            <span class="font-semibold text-slate-800">{{ $log->employee?->first_name }} {{ $log->employee?->last_name }}</span>
                            <span class="rounded-lg bg-cyan-500/10 px-3 py-1 font-mono font-bold text-cyan-600 text-sm">
                                @if ($log->check_in)
                                    {{ is_string($log->check_in) ? \Carbon\Carbon::parse($log->check_in)->format('h:i A') : $log->check_in->format('h:i A') }}
                                @else
                                    —
                                @endif
                            </span>
                        </div>
                    @empty
                        <p class="text-center text-slate-500 py-4">لا توجد حركات اليوم.</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
                <h2 class="text-lg font-black text-slate-800 mb-4">طلبات بانتظار الموافقة</h2>
                <div class="space-y-3">
                    @forelse($pendingTransactions as $t)
                        <div class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                            <span class="font-semibold text-slate-800">{{ $t->employee?->first_name }} {{ $t->employee?->last_name }}</span>
                            <span class="rounded-lg bg-amber-500/10 px-3 py-1 font-bold text-amber-600 text-sm">
                                @if ($t->transaction_type == 'leave')
                                    إجازة
                                @elseif($t->transaction_type == 'permission')
                                    إذن غياب
                                @elseif($t->transaction_type == 'penalty')
                                    جزاء مالي
                                @elseif($t->transaction_type == 'promotion')
                                    ترقية
                                @elseif($t->transaction_type == 'transfer')
                                    نقل إداري
                                @else
                                    {{ $t->transaction_type }}
                                @endif
                            </span>
                        </div>
                    @empty
                        <p class="text-center text-slate-500 py-4">لا توجد طلبات معلقة.</p>
                    @endforelse
                </div>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 md:gap-6">
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100 hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center">
                        <i data-lucide="wallet" class="w-6 h-6 text-blue-600"></i>
                    </div>
                    <span class="text-xs text-slate-400 font-medium">الراتب</span>
                </div>
                <p class="text-2xl font-bold text-slate-800">{{ number_format((float) ($employee?->base_salary ?? 0), 2) }}</p>
                <p class="text-sm text-slate-500 mt-2">الراتب الأساسي</p>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100 hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center">
                        <i data-lucide="check-circle" class="w-6 h-6 text-emerald-600"></i>
                    </div>
                    <span class="text-xs text-slate-400 font-medium">اليوم</span>
                </div>
                <p class="text-3xl font-bold text-slate-800">{{ $attendanceToday ?? 0 }}</p>
                <p class="text-sm text-slate-500 mt-2">حضور اليوم</p>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100 hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-violet-100 flex items-center justify-center">
                        <i data-lucide="umbrella" class="w-6 h-6 text-violet-600"></i>
                    </div>
                    <span class="text-xs text-slate-400 font-medium">الإجازات</span>
                </div>
                <p class="text-3xl font-bold text-slate-800">{{ $vacationBalance }}</p>
                <p class="text-sm text-slate-500 mt-2">رصيد الإجازات</p>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100 hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center">
                        <i data-lucide="receipt" class="w-6 h-6 text-amber-600"></i>
                    </div>
                    <span class="text-xs text-slate-400 font-medium">آخر راتب</span>
                </div>
                <p class="text-2xl font-bold text-slate-800">{{ $recentPayrolls->first()?->net_salary ? number_format((float) $recentPayrolls->first()->net_salary, 2) : '0.00' }}</p>
                <p class="text-sm text-slate-500 mt-2">كشف راتب</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
            <h2 class="text-lg font-black text-slate-800 mb-4">آخر حركاتي</h2>
            <div class="space-y-3">
                @forelse($recentAttendance as $log)
                    <div class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                        <span class="text-slate-600">{{ $log->log_date?->format('Y-m-d') ?? '—' }}</span>
                        <span class="font-semibold text-slate-800">{{ $log->status }}</span>
                    </div>
                @empty
                    <p class="text-center text-slate-500 py-4">لم يتم تسجيل أي حركة بعد.</p>
                @endforelse
            </div>
        </div>
    @endif
</div>
@endsection
