@extends('layouts.app')

@section('title', 'الرواتب')

@section('content')
<div class="space-y-6">
    <section class="rounded-[32px] bg-white border border-slate-200/70 p-6 shadow-sm">
        <div class="flex flex-col gap-5 xl:flex-row xl:items-center xl:justify-between">
            <div class="space-y-3 text-right">
                <p class="text-sm uppercase tracking-[0.35em] text-slate-500">الرواتب</p>
                <h1 class="text-3xl font-black text-slate-900">إدارة كشوف الرواتب</h1>
                <p class="text-sm text-slate-600">راجع كشوف الشهر، شغّل الحسابات، واطبع القسائم مباشرة من هنا.</p>
            </div>
            <form action="{{ route('payroll.generate') }}" method="POST" class="flex flex-col gap-3 rounded-[28px] border border-slate-200/70 bg-slate-950 p-4 text-white shadow-xl sm:flex-row sm:items-center">
                @csrf
                <input type="month" name="salary_month" value="{{ $month ?? now()->format('Y-m') }}" class="rounded-2xl border border-white/10 bg-white/10 px-4 py-3 text-sm text-white outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-500" />
                <button type="submit" class="rounded-2xl bg-cyan-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-cyan-600">تشغيل المحرك</button>
            </form>
        </div>
    </section>

    <section class="grid gap-4 lg:grid-cols-3">
        <div class="rounded-[28px] bg-gradient-to-r from-blue-600 to-cyan-500 p-6 text-white shadow-lg shadow-cyan-500/10">
            <p class="text-sm uppercase tracking-[0.35em] text-cyan-100">الراتب الأساسي</p>
            <p class="mt-4 text-3xl font-black">{{ $payrolls->sum('base_salary') ? number_format($payrolls->sum('base_salary'), 2) . ' د.ع' : '0.00 د.ع' }}</p>
            <p class="mt-3 text-sm text-cyan-100/80">إجمالي الشهر</p>
        </div>
        <div class="rounded-[28px] bg-gradient-to-r from-slate-900 to-slate-700 p-6 text-white shadow-lg shadow-slate-900/10">
            <p class="text-sm uppercase tracking-[0.35em] text-slate-300">صافي الراتب الأخير</p>
            <p class="mt-4 text-3xl font-black">{{ $payrolls->first()?->net_salary ? number_format((float) $payrolls->first()->net_salary, 2) . ' د.ع' : '0.00 د.ع' }}</p>
            <p class="mt-3 text-sm text-slate-300/80">آخر كشف</p>
        </div>
        <div class="rounded-[28px] bg-white border border-slate-200/70 p-6 shadow-sm">
            <p class="text-sm font-semibold text-slate-500">عدد الكشوف</p>
            <p class="mt-4 text-3xl font-black text-slate-900">{{ $payrolls->count() }} كشف</p>
            <p class="mt-3 text-sm text-slate-500">من خلال النظام</p>
        </div>
    </section>

    <section class="rounded-[32px] bg-slate-950 p-5 shadow-2xl shadow-slate-950/20 text-white">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.35em] text-slate-400">كشف الشهر</p>
                <h2 class="mt-2 text-2xl font-black">{{ $month ?? now()->format('Y-m') }}</h2>
            </div>
            <button class="rounded-2xl border border-white/10 bg-white/10 px-4 py-2 text-sm font-semibold transition hover:bg-white/20">طباعة القسائم</button>
        </div>

        <div class="mt-5 rounded-[28px] bg-slate-900/80 p-4">
            @livewire('payroll-viewer', ['month' => $month ?? now()->format('Y-m')])
        </div>
    </section>
</div>
@endsection
