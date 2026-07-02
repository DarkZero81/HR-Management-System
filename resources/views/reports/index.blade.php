@extends('layouts.app')

@section('title', 'التقارير والإحصائيات')

@section('content')
<div class="space-y-6">
    <div class="rounded-[28px] border border-slate-200/70 bg-white/80 p-6 shadow-[0_20px_60px_-35px_rgba(15,23,42,0.45)] backdrop-blur">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.35em] text-slate-500">لوحة التقارير</p>
                <h2 class="mt-2 text-3xl font-black text-slate-900">ملخص الأداء والتقارير التشغيلية</h2>
                <p class="mt-2 text-sm text-slate-600">اطّلع على المؤشرات التشغيلية والمالية في مكان واحد.</p>
            </div>
            <button class="rounded-2xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">تصدير PDF</button>
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-[24px] border border-slate-200/70 bg-white/80 p-5 shadow-[0_20px_60px_-35px_rgba(15,23,42,0.45)] backdrop-blur">
            <p class="text-sm font-semibold text-slate-500">عدد الموظفين</p>
            <div class="mt-3 text-3xl font-black text-slate-900">{{ $totalEmployees }}</div>
        </div>
        <div class="rounded-[24px] border border-slate-200/70 bg-white/80 p-5 shadow-[0_20px_60px_-35px_rgba(15,23,42,0.45)] backdrop-blur">
            <p class="text-sm font-semibold text-slate-500">الوردية النشطة</p>
            <div class="mt-3 text-3xl font-black text-slate-900">{{ $activeShifts }}</div>
        </div>
        <div class="rounded-[24px] border border-slate-200/70 bg-white/80 p-5 shadow-[0_20px_60px_-35px_rgba(15,23,42,0.45)] backdrop-blur">
            <p class="text-sm font-semibold text-slate-500">الطلبات المعلقة</p>
            <div class="mt-3 text-3xl font-black text-slate-900">{{ $pendingRequests }}</div>
        </div>
        <div class="rounded-[24px] border border-slate-200/70 bg-white/80 p-5 shadow-[0_20px_60px_-35px_rgba(15,23,42,0.45)] backdrop-blur">
            <p class="text-sm font-semibold text-slate-500">سجلات الدوام الشهرية</p>
            <div class="mt-3 text-3xl font-black text-slate-900">{{ $monthlyAttendance }}</div>
        </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-[1.4fr_0.8fr]">
        <section class="rounded-[28px] border border-slate-200/70 bg-white/80 p-6 shadow-[0_20px_60px_-35px_rgba(15,23,42,0.45)] backdrop-blur">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-black text-slate-900">تفاصيل الرواتب</h3>
                    <p class="mt-1 text-sm text-slate-500">عدد كشوف الرواتب الشهر الحالي</p>
                </div>
                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">{{ $monthlyPayrolls }} كشف</span>
            </div>
            <div class="mt-6 overflow-hidden rounded-3xl border border-slate-200/70 bg-slate-950/5 p-4">
                <div class="grid gap-4 sm:grid-cols-3">
                    <div class="rounded-3xl bg-slate-950/10 p-4 text-slate-900">
                        <p class="text-sm font-semibold">إجمالي المدفوعات</p>
                        <p class="mt-3 text-2xl font-black">{{ $monthlyPayrolls }} دفعة</p>
                    </div>
                    <div class="rounded-3xl bg-slate-950/10 p-4 text-slate-900">
                        <p class="text-sm font-semibold">معدل الإنجاز</p>
                        <p class="mt-3 text-2xl font-black">90%</p>
                    </div>
                    <div class="rounded-3xl bg-slate-950/10 p-4 text-slate-900">
                        <p class="text-sm font-semibold">طلب الموافقة</p>
                        <p class="mt-3 text-2xl font-black">{{ $pendingRequests }}</p>
                    </div>
                </div>
            </div>
        </section>

        <aside class="rounded-[28px] border border-slate-200/70 bg-gradient-to-br from-slate-950 to-slate-800 p-6 text-white shadow-[0_20px_60px_-35px_rgba(15,23,42,0.9)]">
            <h3 class="text-xl font-black">ملاحظات سريعة</h3>
            <p class="mt-3 text-sm text-slate-300">راقب أداء الموارد البشرية بالتقارير الرقمية وراجع الانقطاعات، الحضور، وحالة طلبات الإجازات.</p>
            <ul class="mt-5 space-y-3 text-sm text-slate-300">
                <li class="rounded-2xl bg-white/5 px-4 py-3">احفظ نسخة PDF من تقرير الرواتب قبل نهاية الشهر.</li>
                <li class="rounded-2xl bg-white/5 px-4 py-3">راجع الطلبات المعلقة يومياً لتجنب تأخير الإجراءات.</li>
                <li class="rounded-2xl bg-white/5 px-4 py-3">ضبط إعدادات التنبيهات للتقارير الحساسة.</li>
            </ul>
        </aside>
    </div>
</div>
@endsection
