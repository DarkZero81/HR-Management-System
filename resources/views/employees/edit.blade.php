@extends('layouts.app')

@section('title', 'تعديل بيانات الموظف')

@section('content')
<div class="max-w-3xl mx-auto space-y-6" dir="rtl">
    <div>
        <h1 class="text-2xl font-black text-white">تعديل الملف الوظيفي: {{ $employee->full_name }}</h1>
        <p class="text-sm text-slate-400">تحديث السجلات والبيانات التعاقدية أو المالية الحالية للموظف.</p>
    </div>

    @if ($errors->any())
        <div class="rounded-2xl border border-rose-500/20 bg-rose-500/10 p-4">
            <ul class="list-inside list-disc text-sm font-medium text-rose-400 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('employees.update', $employee->id) }}" method="POST" class="rounded-[28px] border border-white/10 bg-slate-900/40 p-6 space-y-6 shadow-xl">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">الاسم الأول <span class="text-rose-500">*</span></label>
                <input type="text" name="first_name" value="{{ old('first_name', $employee->first_name) }}" required class="w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-2.5 text-sm text-white focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">الاسم الأخير <span class="text-rose-500">*</span></label>
                <input type="text" name="last_name" value="{{ old('last_name', $employee->last_name) }}" required class="w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-2.5 text-sm text-white focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">الرقم الوطني / الهوية <span class="text-rose-500">*</span></label>
                <input type="text" name="national_id" value="{{ old('national_id', $employee->national_id) }}" required class="w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-2.5 text-sm font-mono text-white focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">رقم الهاتف</label>
                <input type="text" name="phone" value="{{ old('phone', $employee->phone) }}" class="w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-2.5 text-sm text-white focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">الراتب الأساسي <span class="text-rose-500">*</span></label>
                <input type="number" step="0.01" name="base_salary" value="{{ old('base_salary', $employee->base_salary) }}" required class="w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-2.5 text-sm text-white focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">رصيد الإجازات المتاح حالياً <span class="text-rose-500">*</span></label>
                <input type="number" name="vacation_balance" value="{{ old('vacation_balance', $employee->vacation_balance) }}" required class="w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-2.5 text-sm text-white focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">القسم الوظيفي</label>
                <select name="department_id" class="w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-2.5 text-sm text-white focus:outline-none">
                    <option value="">-- اختر القسم --</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ old('department_id', $employee->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">وردية الدوام</label>
                <select name="shift_id" class="w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-2.5 text-sm text-white focus:outline-none">
                    <option value="">-- اختر الوردية --</option>
                    @foreach($shifts as $shift)
                        <option value="{{ $shift->id }}" {{ old('shift_id', $employee->shift_id) == $shift->id ? 'selected' : '' }}>{{ $shift->shift_name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">تاريخ التعيين <span class="text-rose-500">*</span></label>
                <input type="date" name="join_date" value="{{ old('join_date', $employee->join_date?->format('Y-m-d')) }}" required class="w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-2.5 text-sm text-white">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">تاريخ نهاية الخدمة / الاستقالة (إن وجد)</label>
                <input type="date" name="resign_date" value="{{ old('resign_date', $employee->resign_date?->format('Y-m-d')) }}" class="w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-2.5 text-sm text-white">
            </div>

            <div class="sm:col-span-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">رقم الحساب البنكي (IBAN)</label>
                <input type="text" name="bank_account_iban" value="{{ old('bank_account_iban', $employee->bank_account_iban) }}" class="w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-2.5 text-sm font-mono text-white focus:border-blue-500 focus:outline-none">
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 border-t border-white/5 pt-4">
            <a href="{{ route('employees.index') }}" class="rounded-xl bg-slate-950 border border-white/10 px-5 py-2.5 text-sm font-bold text-slate-400 transition hover:text-white">
                عودة للخلف
            </a>
            <button type="submit" class="rounded-xl bg-amber-500 px-5 py-2.5 text-sm font-bold text-slate-950 shadow-lg shadow-amber-500/25 transition hover:bg-amber-400">
                تحديث ملف الموظف
            </button>
        </div>
    </form>
</div>
@endsection
