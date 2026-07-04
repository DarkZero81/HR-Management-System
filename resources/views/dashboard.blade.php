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

    <section class="grid gap-4 xl:grid-cols-[1.4fr_0.9fr]">
        <div class="rounded-[32px] border border-white/10 bg-slate-900/70 p-6 shadow-sm">
            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-[28px] bg-gradient-to-r from-blue-600 to-cyan-500 p-5 text-white shadow-lg shadow-cyan-500/10">
                    <p class="text-xs uppercase tracking-[0.35em] text-cyan-100">الراتب الأساسي</p>
                    <p class="mt-4 text-3xl font-black">{{ number_format((float) (auth()->user()->employee->base_salary ?? 0), 2) }}</p>
                    <p class="mt-3 text-sm text-cyan-100/80">ريال سعودي</p>
                </div>
                <div class="rounded-[28px] bg-gradient-to-r from-emerald-500 to-teal-500 p-5 text-white shadow-lg shadow-emerald-500/10">
                    <p class="text-xs uppercase tracking-[0.35em] text-emerald-100">أيام الحضور</p>
                    <p class="mt-4 text-3xl font-black">{{ $attendanceToday ?? 0 }}</p>
                    <p class="mt-3 text-sm text-emerald-100/80">هذا اليوم</p>
                </div>
                <div class="rounded-[28px] bg-gradient-to-r from-violet-500 to-fuchsia-500 p-5 text-white shadow-lg shadow-fuchsia-500/10">
                    <p class="text-xs uppercase tracking-[0.35em] text-violet-100">الطلبات المعلقة</p>
                    <p class="mt-4 text-3xl font-black">{{ $pendingRequests ?? 0 }}</p>
                    <p class="mt-3 text-sm text-violet-100/80">تحتاج متابعة</p>
                </div>
                <div class="rounded-[28px] bg-gradient-to-r from-slate-950 to-slate-700 p-5 text-white shadow-lg shadow-slate-900/10">
                    <p class="text-xs uppercase tracking-[0.35em] text-slate-300">آخر كشف</p>
                    <p class="mt-4 text-3xl font-black">{{ $recentPayrolls->first()?->net_salary ? number_format((float) $recentPayrolls->first()->net_salary, 2) : '0.00' }}</p>
                    <p class="mt-3 text-sm text-slate-300/80">صافي الراتب</p>
                </div>
            </div>

            <div class="mt-6 rounded-[28px] border border-white/10 bg-slate-950/50 p-5">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-sm font-semibold text-slate-400">آخر الحركات</p>
                        <h2 class="mt-2 text-xl font-black text-white">نظرة سريعة</h2>
                    </div>
                    <span class="rounded-full bg-white/10 px-4 py-2 text-xs font-semibold text-slate-300">{{ $recentAttendance->count() }} سجل</span>
                </div>
                <div class="mt-6 space-y-3">
                    @forelse($recentAttendance as $log)
                        <div class="flex items-center justify-between rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-300">
                            <span>{{ $log->employee?->full_name ?? 'موظف' }}</span>
                            <span>{{ $log->log_date?->format('Y-m-d') ?? '—' }}</span>
                        </div>
                    @empty
                        <div class="text-center text-slate-500">لم يتم تسجيل أي حركة بعد.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <aside class="space-y-4">
            <div class="rounded-[32px] border border-white/10 bg-slate-950/70 p-6 text-white shadow-xl shadow-slate-950/20">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-sm uppercase tracking-[0.35em] text-slate-400">نظرة عامة سريعة</p>
                        <h2 class="mt-2 text-xl font-black">الوضع الحالي</h2>
                    </div>
                    <span class="rounded-full bg-white/10 px-4 py-2 text-sm font-semibold">مباشر</span>
                </div>
                <div class="mt-6 grid gap-3">
                    <div class="rounded-[24px] bg-white/5 p-4">
                        <p class="text-sm text-slate-300">المستخدم الحالي</p>
                        <p class="mt-2 text-lg font-semibold">{{ auth()->user()->name }}</p>
                    </div>
                    <div class="rounded-[24px] bg-white/5 p-4">
                        <p class="text-sm text-slate-300">آخر تسجيل دخول</p>
                        <p class="mt-2 text-lg font-semibold">{{ auth()->user()->last_login_at?->format('d F Y H:i') ?? 'غير متوفر' }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-[32px] border border-white/10 bg-slate-900/70 p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-semibold text-slate-400">الإشعارات</p>
                    <span class="rounded-full bg-white/10 px-4 py-2 text-xs font-semibold text-slate-300">{{ $pendingRequests ?? 0 }} جديد</span>
                </div>
                <div class="mt-6 rounded-[24px] border border-white/10 bg-slate-950/50 p-4 text-slate-400">لا توجد إشعارات حالياً، ولكن الطلبات المعلقة تظهر هنا عند توفرها.</div>
            </div>
        </aside>
    </section>
</div>
@endsection
