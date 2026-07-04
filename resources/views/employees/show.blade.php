@extends('layouts.app')

@section('title', 'بيانات الموظف')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 rounded-[28px] border border-white/10 bg-slate-900/70 p-6 shadow-[0_20px_60px_-35px_rgba(15,23,42,0.45)] backdrop-blur lg:flex-row lg:items-center lg:justify-between">
        <div class="flex items-center gap-4">
            <div class="flex h-16 w-16 items-center justify-center rounded-3xl bg-gradient-to-br from-cyan-500 to-blue-600 text-xl font-black text-white">
                {{ strtoupper(substr($employee->first_name ?? 'E', 0, 1)) }}{{ strtoupper(substr($employee->last_name ?? 'M', 0, 1)) }}
            </div>
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.35em] text-slate-400">الموظف</p>
                <h2 class="mt-1 text-2xl font-black text-white">{{ $employee->full_name }}</h2>
                <p class="mt-1 text-sm text-slate-400">الرقم الوطني: {{ $employee->national_id }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('employees.edit', $employee) }}" class="inline-flex items-center gap-2 rounded-2xl bg-cyan-500/10 px-4 py-2.5 text-sm font-semibold text-cyan-200 transition hover:bg-cyan-500/20">
                <i data-lucide="edit" class="h-4 w-4"></i>
                تعديل
            </a>
            <a href="{{ route('employees.index') }}" class="inline-flex items-center gap-2 rounded-2xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm font-semibold text-slate-300 transition hover:bg-white/10">
                <i data-lucide="arrow-right" class="h-4 w-4"></i>
                رجوع
            </a>
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-[28px] bg-gradient-to-r from-blue-600 to-cyan-500 p-5 text-white shadow-lg shadow-cyan-500/10">
            <p class="text-xs uppercase tracking-[0.35em] text-cyan-100">الراتب الأساسي</p>
            <p class="mt-4 text-3xl font-black">{{ number_format((float) $employee->base_salary, 2) }}</p>
            <p class="mt-3 text-sm text-cyan-100/80">ريال سعودي</p>
        </div>
        <div class="rounded-[28px] bg-gradient-to-r from-emerald-500 to-teal-500 p-5 text-white shadow-lg shadow-emerald-500/10">
            <p class="text-xs uppercase tracking-[0.35em] text-emerald-100">رصيد الإجازات</p>
            <p class="mt-4 text-3xl font-black">{{ $employee->vacation_balance }}</p>
            <p class="mt-3 text-sm text-emerald-100/80">يوم</p>
        </div>
        <div class="rounded-[28px] bg-gradient-to-r from-violet-500 to-fuchsia-500 p-5 text-white shadow-lg shadow-fuchsia-500/10">
            <p class="text-xs uppercase tracking-[0.35em] text-violet-100">تقييم الأداء</p>
            <p class="mt-4 text-3xl font-black">{{ number_format((float) $employee->performance_score, 2) }}</p>
            <p class="mt-3 text-sm text-violet-100/80">من 5.00</p>
        </div>
        <div class="rounded-[28px] bg-gradient-to-r from-slate-950 to-slate-700 p-5 text-white shadow-lg shadow-slate-900/10">
            <p class="text-xs uppercase tracking-[0.35em] text-slate-300">الوردية</p>
            <p class="mt-4 text-3xl font-black">{{ $employee->shift->shift_name ?? '—' }}</p>
            <p class="mt-3 text-sm text-slate-300/80">خطة الدوام</p>
        </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-2">
        <div class="rounded-[28px] border border-white/10 bg-slate-900/70 p-6 shadow-sm">
            <h3 class="text-xl font-black text-white mb-4">البيانات الشخصية</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between rounded-2xl border border-white/10 bg-white/5 px-4 py-3">
                    <span class="text-slate-400">الاسم الكامل</span>
                    <span class="font-semibold text-white">{{ $employee->full_name }}</span>
                </div>
                <div class="flex justify-between rounded-2xl border border-white/10 bg-white/5 px-4 py-3">
                    <span class="text-slate-400">الرقم الوطني</span>
                    <span class="font-semibold text-white">{{ $employee->national_id }}</span>
                </div>
                <div class="flex justify-between rounded-2xl border border-white/10 bg-white/5 px-4 py-3">
                    <span class="text-slate-400">الهاتف</span>
                    <span class="font-semibold text-white">{{ $employee->phone ?? '—' }}</span>
                </div>
                <div class="flex justify-between rounded-2xl border border-white/10 bg-white/5 px-4 py-3">
                    <span class="text-slate-400">الحساب البنكي (IBAN)</span>
                    <span class="font-semibold text-white">{{ $employee->bank_account_iban ?? '—' }}</span>
                </div>
            </div>
        </div>
        <div class="rounded-[28px] border border-white/10 bg-slate-900/70 p-6 shadow-sm">
            <h3 class="text-xl font-black text-white mb-4">البيانات الوظيفية</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between rounded-2xl border border-white/10 bg-white/5 px-4 py-3">
                    <span class="text-slate-400">البريد الإلكتروني</span>
                    <span class="font-semibold text-white">{{ $employee->user->email ?? '—' }}</span>
                </div>
                <div class="flex justify-between rounded-2xl border border-white/10 bg-white/5 px-4 py-3">
                    <span class="text-slate-400">تاريخ التعيين</span>
                    <span class="font-semibold text-white">{{ $employee->join_date?->format('d F Y') ?? '—' }}</span>
                </div>
                <div class="flex justify-between rounded-2xl border border-white/10 bg-white/5 px-4 py-3">
                    <span class="text-slate-400">تاريخ الاستقالة</span>
                    <span class="font-semibold text-white">{{ $employee->resign_date?->format('d F Y') ?? '—' }}</span>
                </div>
                <div class="flex justify-between rounded-2xl border border-white/10 bg-white/5 px-4 py-3">
                    <span class="text-slate-400">القسم</span>
                    <span class="font-semibold text-white">{{ $employee->department->name ?? '—' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection