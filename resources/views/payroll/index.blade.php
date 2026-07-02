@extends('layouts.app')

@section('title', 'الرواتب')

@section('content')
<div class="space-y-6">
    <div class="rounded-[32px] border border-slate-200/70 bg-white/90 p-6 shadow-[0_25px_90px_-35px_rgba(15,23,42,0.15)] backdrop-blur">
        <div class="flex flex-col gap-5 xl:flex-row xl:items-center xl:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.35em] text-slate-500">الرواتب</p>
                <h1 class="mt-2 text-3xl font-black text-slate-900">إدارة كشوف الرواتب</h1>
                <p class="mt-3 text-sm text-slate-600">راجع بيانات الرواتب الشهرية، واطبع القسائم أو شغّل الحسابات التلقائية بسهولة.</p>
            </div>
            <form action="{{ route('payroll.generate') }}" method="POST" class="flex flex-col gap-3 rounded-[28px] border border-slate-200/70 bg-slate-950 p-4 text-white shadow-xl sm:flex-row sm:items-center">
                @csrf
                <input type="month" name="salary_month" value="{{ $month ?? now()->format('Y-m') }}" class="rounded-2xl border border-white/10 bg-white/10 px-4 py-3 text-sm text-white outline-none ring-0 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-500" />
                <button type="submit" class="rounded-2xl bg-cyan-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-cyan-600">تشغيل المحرك</button>
            </form>
        </div>
    </div>

    <div class="grid gap-4 lg:grid-cols-3">
        <div class="rounded-[28px] border border-slate-200/70 bg-white/90 p-5 shadow-[0_20px_60px_-35px_rgba(15,23,42,0.12)]">
            <p class="text-sm font-semibold text-slate-500">الراتب الأساسي</p>
            <p class="mt-4 text-3xl font-black text-slate-900">{{ $payrolls->sum('base_salary') ? number_format($payrolls->sum('base_salary'), 2) . ' د.ع' : '0.00 د.ع' }}</p>
        </div>
        <div class="rounded-[28px] border border-slate-200/70 bg-white/90 p-5 shadow-[0_20px_60px_-35px_rgba(15,23,42,0.12)]">
            <p class="text-sm font-semibold text-slate-500">صافي الراتب الأخير</p>
            <p class="mt-4 text-3xl font-black text-slate-900">{{ $payrolls->first()?->net_salary ? number_format((float) $payrolls->first()->net_salary, 2) . ' د.ع' : '0.00 د.ع' }}</p>
        </div>
        <div class="rounded-[28px] border border-slate-200/70 bg-white/90 p-5 shadow-[0_20px_60px_-35px_rgba(15,23,42,0.12)]">
            <p class="text-sm font-semibold text-slate-500">الرواتب الشهرية</p>
            <p class="mt-4 text-3xl font-black text-slate-900">{{ $payrolls->count() }} كشف</p>
        </div>
    </div>

    <div class="rounded-[32px] border border-slate-200/70 bg-slate-950 p-4 shadow-[0_30px_90px_-40px_rgba(2,6,23,0.85)]">
        <div class="flex items-center justify-between border-b border-white/10 px-4 py-4 text-white">
            <div>
                <p class="text-sm uppercase tracking-[0.35em] text-slate-400">كشف الشهر</p>
                <h2 class="mt-1 text-xl font-semibold">{{ $month ?? now()->format('Y-m') }}</h2>
            </div>
            <button class="rounded-2xl border border-white/10 bg-white/10 px-4 py-2 text-sm font-semibold transition hover:bg-white/20">طباعة القسائم</button>
        </div>

        <div class="mt-4 overflow-x-auto">
            <table class="min-w-full divide-y divide-white/10 text-sm text-slate-200">
                <thead class="bg-white/5 text-right text-slate-300">
                    <tr>
                        <th class="px-6 py-4 font-semibold">الموظف</th>
                        <th class="px-6 py-4 font-semibold">البدلات</th>
                        <th class="px-6 py-4 font-semibold">الخصومات</th>
                        <th class="px-6 py-4 font-semibold">الصافي</th>
                        <th class="px-6 py-4 font-semibold">الحالة</th>
                        <th class="px-6 py-4 font-semibold">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10 bg-slate-950/80">
                    @forelse($payrolls as $payroll)
                        <tr class="transition hover:bg-white/5">
                            <td class="px-6 py-5">
                                <p class="font-semibold text-white">{{ $payroll->employee->full_name ?? '—' }}</p>
                                <p class="mt-1 text-xs text-slate-400">{{ $payroll->employee->national_id ?? '' }}</p>
                            </td>
                            <td class="px-6 py-5 text-emerald-400">{{ number_format((float) $payroll->allowances, 2) }} د.ع</td>
                            <td class="px-6 py-5 text-rose-400">{{ number_format((float) $payroll->deductions, 2) }} د.ع</td>
                            <td class="px-6 py-5 text-sky-300">{{ number_format((float) $payroll->net_salary, 2) }} د.ع</td>
                            <td class="px-6 py-5">
                                <span class="rounded-full bg-amber-500/15 px-3 py-1 text-xs font-semibold text-amber-300">{{ $payroll->payment_status }}</span>
                            </td>
                            <td class="px-6 py-5">
                                <button class="rounded-full border border-blue-400/30 bg-blue-500/10 px-3 py-2 text-sm font-semibold text-blue-300 transition hover:bg-blue-500/20">عرض</button>
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
