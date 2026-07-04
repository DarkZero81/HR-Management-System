@extends('layouts.app')

@section('title', 'تعديل بيانات الموظف')

@section('content')
<div class="space-y-6">
    <div class="rounded-[28px] border border-white/10 bg-slate-900/70 p-6 shadow-[0_20px_60px_-35px_rgba(15,23,42,0.45)] backdrop-blur">
        <div class="flex items-center justify-between gap-3">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.35em] text-slate-400">الموظفين</p>
                <h2 class="mt-2 text-3xl font-black text-white">تعديل بيانات الموظف</h2>
                <p class="mt-2 text-sm text-slate-400">تحديث البيانات الوظيفية للموظف.</p>
            </div>
            <a href="{{ route('employees.index') }}" class="inline-flex items-center gap-2 rounded-2xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm font-semibold text-slate-300 transition hover:bg-white/10">
                <i data-lucide="arrow-right" class="h-4 w-4"></i>
                رجوع
            </a>
        </div>

        <form method="POST" action="{{ route('employees.update', $employee) }}" class="mt-6 max-w-3xl space-y-5">
            @csrf
            @method('PUT')
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">الاسم الأول</label>
                    <input type="text" name="first_name" class="w-full rounded-2xl border border-white/10 bg-slate-950/50 px-4 py-3 text-sm text-white outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-500 @error('first_name') border-rose-400 @enderror" value="{{ old('first_name', $employee->first_name) }}" required>
                    @error('first_name')
                        <p class="mt-2 text-sm text-rose-300">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">الاسم الأخير</label>
                    <input type="text" name="last_name" class="w-full rounded-2xl border border-white/10 bg-slate-950/50 px-4 py-3 text-sm text-white outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-500 @error('last_name') border-rose-400 @enderror" value="{{ old('last_name', $employee->last_name) }}" required>
                    @error('last_name')
                        <p class="mt-2 text-sm text-rose-300">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">الرقم الوطني</label>
                    <input type="text" name="national_id" class="w-full rounded-2xl border border-white/10 bg-slate-950/50 px-4 py-3 text-sm text-white outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-500 @error('national_id') border-rose-400 @enderror" value="{{ old('national_id', $employee->national_id) }}" required>
                    @error('national_id')
                        <p class="mt-2 text-sm text-rose-300">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">الهاتف</label>
                    <input type="text" name="phone" class="w-full rounded-2xl border border-white/10 bg-slate-950/50 px-4 py-3 text-sm text-white outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-500 @error('phone') border-rose-400 @enderror" value="{{ old('phone', $employee->phone) }}">
                    @error('phone')
                        <p class="mt-2 text-sm text-rose-300">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">القسم</label>
                    <select name="department_id" class="w-full rounded-2xl border border-white/10 bg-slate-950/50 px-4 py-3 text-sm text-white outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-500 @error('department_id') border-rose-400 @enderror">
                        <option value="">بدون قسم</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ old('department_id', $employee->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                    @error('department_id')
                        <p class="mt-2 text-sm text-rose-300">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">الوردية</label>
                    <select name="shift_id" class="w-full rounded-2xl border border-white/10 bg-slate-950/50 px-4 py-3 text-sm text-white outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-500 @error('shift_id') border-rose-400 @enderror">
                        <option value="">بدون وردية</option>
                        @foreach($shifts as $shift)
                            <option value="{{ $shift->id }}" {{ old('shift_id', $employee->shift_id) == $shift->id ? 'selected' : '' }}>{{ $shift->shift_name }}</option>
                        @endforeach
                    </select>
                    @error('shift_id')
                        <p class="mt-2 text-sm text-rose-300">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">الراتب الأساسي</label>
                    <input type="number" step="0.01" name="base_salary" class="w-full rounded-2xl border border-white/10 bg-slate-950/50 px-4 py-3 text-sm text-white outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-500 @error('base_salary') border-rose-400 @enderror" value="{{ old('base_salary', $employee->base_salary) }}" required>
                    @error('base_salary')
                        <p class="mt-2 text-sm text-rose-300">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">تاريخ التعيين</label>
                    <input type="date" name="join_date" class="w-full rounded-2xl border border-white/10 bg-slate-950/50 px-4 py-3 text-sm text-white outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-500 @error('join_date') border-rose-400 @enderror" value="{{ old('join_date', $employee->join_date) }}" required>
                    @error('join_date')
                        <p class="mt-2 text-sm text-rose-300">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <button type="submit" class="rounded-2xl bg-gradient-to-l from-cyan-500 to-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-lg transition hover:opacity-90">
                تحديث البيانات
            </button>
        </form>
    </div>
</div>
@endsection