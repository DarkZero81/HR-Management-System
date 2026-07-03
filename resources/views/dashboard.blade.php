@extends('layouts.app')

@section('title', 'الرئيسية')

@section('content')
<div class="space-y-6" dir="rtl">
    <div class="rounded-[32px] bg-white/95 border border-slate-200/70 shadow-lg shadow-slate-300/10 p-6 backdrop-blur-xl">
        <div class="flex flex-col gap-6 xl:flex-row xl:items-center xl:justify-between">
            <div class="space-y-3 text-right">
                <p class="text-sm uppercase tracking-[0.35em] text-slate-500">الرئيسية</p>
                <h1 class="text-3xl font-black text-slate-900">مرحباً، {{ auth()->user()->name ?? 'المستخدم' }}!</h1>
                <p class="text-sm text-slate-500">نتمنى لك يوماً موفقاً في عملك.</p>
            </div>
            <div class="rounded-[28px] bg-gradient-to-r from-slate-900 to-blue-700 p-5 text-white shadow-xl shadow-blue-700/10 flex items-center justify-between gap-5 min-w-[320px]">
                <div>
                    <p class="text-xs uppercase tracking-[0.35em] text-slate-300">الوقت الحالي</p>
                    <p class="mt-3 text-3xl font-black">{{ now()->format('H:i') }}</p>
                    <p class="text-xs text-slate-300 mt-1">{{ now()->format('d F Y') }}</p>
                </div>
                <div class="flex h-16 w-16 items-center justify-center rounded-3xl bg-white/10 text-white">
                    <i data-lucide="clock" class="h-7 w-7"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid gap-4 xl:grid-cols-[1.5fr_0.9fr]">
        <div class="rounded-[32px] bg-white border border-slate-200/70 p-6 shadow-sm">
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="rounded-[26px] bg-gradient-to-r from-blue-600 to-cyan-500 p-5 text-white shadow-lg shadow-blue-500/10">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-[0.35em] text-cyan-100">الراتب الأساسي</p>
                            <p class="mt-4 text-3xl font-black">{{ number_format(auth()->user()->employee->base_salary ?? 0) }}</p>
                        </div>
                        <div class="rounded-2xl bg-white/10 p-3 text-white">
                            <i data-lucide="dollar-sign" class="h-5 w-5"></i>
                        </div>
                    </div>
                    <p class="mt-4 text-sm text-cyan-100/80">ريال سعودي</p>
                </div>

                <div class="rounded-[26px] bg-gradient-to-r from-emerald-500 to-teal-500 p-5 text-white shadow-lg shadow-emerald-500/10">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-[0.35em] text-emerald-100">صافي الراتب الأخير</p>
                            <p class="mt-4 text-3xl font-black">0</p>
                        </div>
                        <div class="rounded-2xl bg-white/10 p-3 text-white">
                            <i data-lucide="trending-up" class="h-5 w-5"></i>
                        </div>
                    </div>
                    <p class="mt-4 text-sm text-emerald-100/80">لا يوجد</p>
                </div>

                <div class="rounded-[26px] bg-gradient-to-r from-slate-900 to-slate-700 p-5 text-white shadow-lg shadow-slate-900/10">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-[0.35em] text-slate-300">أيام الحضور</p>
                            <p class="mt-4 text-3xl font-black">0</p>
                        </div>
                        <div class="rounded-2xl bg-white/10 p-3 text-white">
                            <i data-lucide="check-square" class="h-5 w-5"></i>
                        </div>
                    </div>
                    <p class="mt-4 text-sm text-slate-300/80">هذا الأسبوع</p>
                </div>

                <div class="rounded-[26px] bg-gradient-to-r from-rose-500 to-pink-500 p-5 text-white shadow-lg shadow-rose-500/10">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-[0.35em] text-rose-100">الطلبات المعلقة</p>
                            <p class="mt-4 text-3xl font-black">0</p>
                        </div>
                        <div class="rounded-2xl bg-white/10 p-3 text-white">
                            <i data-lucide="alert-circle" class="h-5 w-5"></i>
                        </div>
                    </div>
                    <p class="mt-4 text-sm text-rose-100/80">طلب بانتظار الموافقة</p>
                </div>
            </div>

            <div class="mt-6 rounded-[28px] border border-slate-200/70 bg-slate-50 p-5">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-black text-slate-900">سجل الدوام الأخير</h2>
                    <span class="rounded-full bg-slate-100 px-4 py-2 text-xs font-semibold text-slate-700">0 حركة</span>
                </div>
                <div class="mt-6 text-center text-slate-500">
                    لا توجد سجلات لهذا الشهر
                </div>
            </div>
        </div>

        <div class="space-y-4">
            <div class="rounded-[32px] bg-white border border-slate-200/70 p-6 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-sm font-semibold text-slate-500">رصيد الإجازات</p>
                        <h2 class="mt-2 text-xl font-black text-slate-900">30 يوم</h2>
                    </div>
                    <span class="rounded-full bg-slate-100 px-4 py-2 text-xs font-semibold text-slate-700">من أصل 30</span>
                </div>
                <div class="mt-6 grid gap-3">
                    <div class="rounded-[24px] bg-slate-50 p-4">
                        <p class="text-xs text-slate-500">الرصيد المستخدم</p>
                        <p class="mt-2 text-xl font-black text-slate-900">0 يوم</p>
                    </div>
                    <div class="rounded-[24px] bg-slate-50 p-4">
                        <p class="text-xs text-slate-500">الرصيد المتبقي</p>
                        <p class="mt-2 text-xl font-black text-slate-900">30 يوم</p>
                    </div>
                </div>
            </div>

            <div class="rounded-[32px] bg-white border border-slate-200/70 p-6 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-sm font-semibold text-slate-500">العروض السريعة</p>
                        <h2 class="mt-2 text-xl font-black text-slate-900">تحديثات الموظف</h2>
                    </div>
                    <span class="rounded-full bg-slate-100 px-4 py-2 text-xs font-semibold text-slate-700">مباشر</span>
                </div>
                <div class="mt-6 grid gap-4">
                    <div class="rounded-[24px] bg-slate-50 p-4 text-slate-700">
                        لا توجد تنبيهات جديدة.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
