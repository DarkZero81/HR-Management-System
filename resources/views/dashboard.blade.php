@extends('layouts.app')

@section('title', 'الرئيسية')

@section('content')
<div class="space-y-6" dir="rtl">
    <section class="rounded-[32px] bg-gradient-to-r from-slate-950 to-slate-800 p-6 shadow-2xl shadow-slate-900/20 text-white">
        <div class="flex flex-col gap-6 xl:flex-row xl:items-center xl:justify-between">
            <div class="space-y-3 text-right">
                <p class="text-sm uppercase tracking-[0.35em] text-slate-300">لوحة القيادة</p>
                <h1 class="text-3xl font-black">أهلاً بك، {{ auth()->user()->name ?? 'المستخدم' }}!</h1>
                <p class="text-sm text-slate-300">يمكنك متابعة الحضور، الرواتب، والطلبات من هنا بسهولة وسرعة.</p>
            </div>
            <div class="rounded-[28px] bg-white/10 p-5 shadow-xl shadow-blue-700/10 min-w-[320px]">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-xs uppercase tracking-[0.35em] text-slate-300">الوقت</p>
                        <p class="mt-3 text-3xl font-black">{{ now()->format('H:i') }}</p>
                        <p class="text-xs text-slate-300 mt-1">{{ now()->format('d F Y') }}</p>
                    </div>
                    <div class="flex h-16 w-16 items-center justify-center rounded-3xl bg-slate-900 text-white">
                        <i data-lucide="clock" class="h-7 w-7"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="grid gap-4 xl:grid-cols-[1.4fr_0.9fr]">
        <div class="rounded-[32px] bg-white border border-slate-200/70 p-6 shadow-sm">
            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-[28px] bg-gradient-to-r from-blue-600 to-cyan-500 p-5 text-white shadow-lg shadow-blue-500/10">
                    <p class="text-xs uppercase tracking-[0.35em] text-cyan-100">الراتب الأساسي</p>
                    <p class="mt-4 text-3xl font-black">{{ number_format(auth()->user()->employee->base_salary ?? 0) }}</p>
                    <p class="mt-3 text-sm text-cyan-100/80">ريال سعودي</p>
                </div>
                <div class="rounded-[28px] bg-gradient-to-r from-emerald-500 to-teal-500 p-5 text-white shadow-lg shadow-emerald-500/10">
                    <p class="text-xs uppercase tracking-[0.35em] text-emerald-100">أيام الحضور</p>
                    <p class="mt-4 text-3xl font-black">0</p>
                    <p class="mt-3 text-sm text-emerald-100/80">هذا الأسبوع</p>
                </div>
                <div class="rounded-[28px] bg-gradient-to-r from-violet-500 to-fuchsia-500 p-5 text-white shadow-lg shadow-fuchsia-500/10">
                    <p class="text-xs uppercase tracking-[0.35em] text-violet-100">الطلبات المعلقة</p>
                    <p class="mt-4 text-3xl font-black">0</p>
                    <p class="mt-3 text-sm text-violet-100/80">تحتاج متابعة</p>
                </div>
                <div class="rounded-[28px] bg-gradient-to-r from-slate-900 to-slate-700 p-5 text-white shadow-lg shadow-slate-900/10">
                    <p class="text-xs uppercase tracking-[0.35em] text-slate-300">صافي الراتب</p>
                    <p class="mt-4 text-3xl font-black">0</p>
                    <p class="mt-3 text-sm text-slate-300/80">آخر كشف</p>
                </div>
            </div>

            <div class="mt-6 rounded-[28px] border border-slate-200/70 bg-slate-50 p-5">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-sm font-semibold text-slate-500">آخر الحركات</p>
                        <h2 class="mt-2 text-xl font-black text-slate-900">نظرة سريعة</h2>
                    </div>
                    <span class="rounded-full bg-slate-100 px-4 py-2 text-xs font-semibold text-slate-700">0 سجل</span>
                </div>
                <div class="mt-6 text-center text-slate-500">لم يتم تسجيل أي حركة بعد.</div>
            </div>
        </div>

        <aside class="space-y-4">
            <div class="rounded-[32px] bg-slate-950 p-6 text-white shadow-xl shadow-slate-950/20">
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

            <div class="rounded-[32px] bg-white border border-slate-200/70 p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-semibold text-slate-500">الإشعارات</p>
                    <span class="rounded-full bg-slate-100 px-4 py-2 text-xs font-semibold text-slate-700">0 جديد</span>
                </div>
                <div class="mt-6 rounded-[24px] bg-slate-50 p-4 text-slate-600">لا توجد إشعارات حالياً.</div>
            </div>
        </aside>
    </section>
</div>
@endsection
