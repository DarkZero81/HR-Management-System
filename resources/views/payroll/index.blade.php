@extends('layouts.app')

@section('title', 'كشف الرواتب')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 rounded-3xl border border-slate-200/70 bg-white/80 p-6 shadow-[0_25px_80px_-35px_rgba(15,23,42,0.35)] backdrop-blur xl:flex-row xl:items-end xl:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.35em] text-slate-500">لوحة الرواتب</p>
            <h2 class="mt-2 text-3xl font-bold text-slate-900">إدارة كشوف الرواتب والخصومات</h2>
            <p class="mt-3 max-w-2xl text-sm text-slate-600">نظرة مالية موحدة لكافة الموظفين مع زر تشغيل تلقائي لحسابات الشهر الحالي وعرض فواتير جاهزة للطباعة.</p>
        </div>
        <form action="{{ route('payroll.generate') }}" method="POST" class="flex flex-col gap-3 rounded-2xl bg-slate-950 p-4 text-white shadow-xl sm:flex-row sm:items-center">
            @csrf
            <input type="month" name="salary_month" value="{{ $month ?? now()->format('Y-m') }}" class="rounded-2xl border border-white/10 bg-white/10 px-4 py-3 text-sm text-white outline-none ring-0 focus:border-blue-400 focus:ring-2 focus:ring-blue-500" />
            <button type="submit" class="rounded-2xl bg-blue-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-600">تشغيل محرك الرواتب</button>
        </form>
    </div>

    <div class="overflow-hidden rounded-[28px] border border-slate-200/70 bg-slate-950 shadow-[0_30px_90px_-40px_rgba(2,6,23,0.85)]">
        <div class="flex items-center justify-between border-b border-white/10 px-6 py-5">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.35em] text-slate-400">كشف شهر</p>
                <h3 class="mt-1 text-xl font-semibold text-white">{{ $month ?? now()->format('Y-m') }}</h3>
            </div>
            <button class="rounded-2xl border border-white/10 bg-white/10 px-4 py-2 text-sm font-semibold text-slate-200 transition hover:bg-white/20">طباعة المجموعات</button>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-white/10 text-sm text-slate-200">
                <thead class="bg-white/5 text-right">
                    <tr>
                        <th class="px-6 py-4 font-semibold">الموظف</th>
                        <th class="px-6 py-4 font-semibold">البدلات</th>
                        <th class="px-6 py-4 font-semibold">الخصومات</th>
                        <th class="px-6 py-4 font-semibold">الصافي</th>
                        <th class="px-6 py-4 font-semibold">الحالة</th>
                        <th class="px-6 py-4 font-semibold">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10 bg-slate-950/90">
                    @forelse($payrolls as $payroll)
                        <tr class="transition hover:bg-white/5">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-white">{{ $payroll->employee->full_name ?? '—' }}</div>
                                <div class="mt-1 text-xs text-slate-400">{{ $payroll->employee->national_id ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4 text-emerald-400">{{ number_format((float) $payroll->allowances, 2) }} د.ع</td>
                            <td class="px-6 py-4 text-rose-400">{{ number_format((float) $payroll->deductions, 2) }} د.ع</td>
                            <td class="px-6 py-4 text-sky-300">{{ number_format((float) $payroll->net_salary, 2) }} د.ع</td>
                            <td class="px-6 py-4">
                                <span class="rounded-full bg-amber-500/15 px-3 py-1 text-xs font-semibold text-amber-300">{{ $payroll->payment_status }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <button class="rounded-full border border-blue-400/30 bg-blue-500/10 px-3 py-2 text-sm font-semibold text-blue-300 transition hover:bg-blue-500/20">عرض payslip</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-slate-400">لا توجد بيانات رواتب لهذا الشهر بعد.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
