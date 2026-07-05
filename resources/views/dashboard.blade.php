@extends('layouts.app')

@section('title', 'الرئيسية')

@section('content')
<div class="space-y-6" dir="rtl">
    <section class="rounded-[32px] border border-white/10 bg-gradient-to-r from-slate-950 via-slate-900 to-slate-800 p-6 text-white shadow-2xl shadow-slate-950/40">
        <div class="flex flex-col gap-6 xl:flex-row xl:items-center xl:justify-between">
            <div class="space-y-3 text-right">
                <p class="text-sm uppercase tracking-[0.35em] text-slate-300">لوحة القيادة</p>
                <h1 class="text-3xl font-black">أهلاً بك، {{ auth()->user()->name ?? 'المستخدم' }}!</h1>
                <p class="text-sm text-slate-300">تابع الحضور، الطلبات، والرواتب من واجهة حديثة ومؤتمتة.</p>
            </div>
            <div class="min-w-[280px] rounded-[28px] border border-white/10 bg-white/10 p-5 shadow-xl shadow-cyan-600/10">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-xs uppercase tracking-[0.35em] text-slate-300">الوقت</p>
                        <p class="mt-3 text-3xl font-black">{{ now()->format('H:i') }}</p>
                        <p class="mt-1 text-xs text-slate-300">{{ now()->format('d F Y') }}</p>
                    </div>
                    <div class="flex h-16 w-16 items-center justify-center rounded-3xl bg-slate-900/70 text-white">
                        <i data-lucide="clock" class="h-7 w-7"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if($viewMode === 'admin')
        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-[28px] bg-gradient-to-r from-blue-600 to-cyan-500 p-5 text-white shadow-lg">
                <p class="text-xs uppercase tracking-[0.35em] text-cyan-100">عدد الموظفين</p>
                <p class="mt-4 text-3xl font-black">{{ $employeeCount }}</p>
            </div>
            <div class="rounded-[28px] bg-gradient-to-r from-emerald-500 to-teal-500 p-5 text-white shadow-lg">
                <p class="text-xs uppercase tracking-[0.35em] text-emerald-100">حضور اليوم</p>
                <p class="mt-4 text-3xl font-black">{{ $todayAttendance }}</p>
            </div>
            <div class="rounded-[28px] bg-gradient-to-r from-orange-500 to-amber-500 p-5 text-white shadow-lg">
                <p class="text-xs uppercase tracking-[0.35em] text-amber-100">دقائق التأخير</p>
                <p class="mt-4 text-3xl font-black">{{ $lateMinutes }}</p>
            </div>
            <div class="rounded-[28px] bg-gradient-to-r from-violet-500 to-fuchsia-500 p-5 text-white shadow-lg">
                <p class="text-xs uppercase tracking-[0.35em] text-violet-100">الطلبات المعلقة</p>
                <p class="mt-4 text-3xl font-black">{{ $pendingRequests }}</p>
            </div>
        </section>

        <section class="grid gap-4 xl:grid-cols-2">
            <div class="rounded-[28px] border border-white/10 bg-slate-950/50 p-5">
                <h2 class="text-lg font-black text-white mb-4">آخر حركات الحضور</h2>
                @forelse($recentAttendance as $log)
                    <div class="flex items-center justify-between rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-300 mb-2">
                        <span>{{ $log->employee?->user?->name ?? 'موظف' }}</span>
                        <span>{{ $log->check_in?->format('H:i') ?? '—' }}</span>
                    </div>
                @empty
                    <div class="text-center text-slate-500">لا توجد حركات اليوم.</div>
                @endforelse
            </div>

            <div class="rounded-[28px] border border-white/10 bg-slate-950/50 p-5">
                <h2 class="text-lg font-black text-white mb-4">طلبات بانتظار الموافقة</h2>
                @forelse($pendingTransactions as $t)
                    <div class="flex items-center justify-between rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-300 mb-2">
                        <span>{{ $t->employee?->user?->name ?? 'موظف' }}</span>
                        <span>{{ $t->transaction_type }}</span>
                    </div>
                @empty
                    <div class="text-center text-slate-500">لا توجد طلبات معلقة.</div>
                @endforelse
            </div>
        </section>
    @else
        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-[28px] bg-gradient-to-r from-blue-600 to-cyan-500 p-5 text-white shadow-lg">
                <p class="text-xs uppercase tracking-[0.35em] text-cyan-100">الراتب الأساسي</p>
                <p class="mt-4 text-3xl font-black">{{ number_format((float) ($employee?->base_salary ?? 0), 2) }}</p>
            </div>
            <div class="rounded-[28px] bg-gradient-to-r from-emerald-500 to-teal-500 p-5 text-white shadow-lg">
                <p class="text-xs uppercase tracking-[0.35em] text-emerald-100">الحضور اليوم</p>
                <p class="mt-4 text-3xl font-black">{{ $attendanceToday ?? 0 }}</p>
            </div>
            <div class="rounded-[28px] bg-gradient-to-r from-violet-500 to-fuchsia-500 p-5 text-white shadow-lg">
                <p class="text-xs uppercase tracking-[0.35em] text-violet-100">رصيد الإجازات</p>
                <p class="mt-4 text-3xl font-black">{{ $vacationBalance }}</p>
            </div>
            <div class="rounded-[28px] bg-gradient-to-r from-slate-950 to-slate-700 p-5 text-white shadow-lg">
                <p class="text-xs uppercase tracking-[0.35em] text-slate-300">آخر كشف راتب</p>
                <p class="mt-4 text-3xl font-black">{{ $recentPayrolls->first()?->net_salary ? number_format((float) $recentPayrolls->first()->net_salary, 2) : '0.00' }}</p>
            </div>
        </section>

        <section class="rounded-[28px] border border-white/10 bg-slate-950/50 p-5">
            <h2 class="text-lg font-black text-white mb-4">آخر حركاتي</h2>
            @forelse($recentAttendance as $log)
                <div class="flex items-center justify-between rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-300 mb-2">
                    <span>{{ $log->log_date?->format('Y-m-d') ?? '—' }}</span>
                    <span>{{ $log->status }}</span>
                </div>
            @empty
                <div class="text-center text-slate-500">لم يتم تسجيل أي حركة بعد.</div>
            @endforelse
        </section>
    @endif
</div>
@endsection
