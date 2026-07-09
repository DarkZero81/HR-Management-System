@extends('layouts.app')
@section('title', 'تعديل الإجازة')
@section('content')
<div class="max-w-3xl mx-auto space-y-6 px-4 py-4" dir="rtl">
    <div class="border-b border-white/5 pb-4">
        <p class="text-xs font-black uppercase tracking-[0.35em] text-blue-400 dark:text-cyan-400">الإجازات</p>
        <h1 class="text-2xl md:text-3xl font-black text-white dark:text-slate-900 mt-0.5">تعديل الإجازة</h1>
        <p class="text-sm text-slate-400 dark:text-slate-500 mt-1">تحديث تفاصيل فترة الإجازة.</p>
    </div>
    @if ($errors->any())
        <div class="rounded-2xl border border-rose-500/20 bg-rose-500/10 p-4">
            <ul class="list-inside list-disc text-xs font-medium text-rose-400 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('holidays.update', $holiday) }}" class="employee-form-card rounded-[28px] border border-white/10 dark:border-white/5 p-6 space-y-5 shadow-2xl backdrop-blur-md">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <div>
                <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">اسم الإجازة <span class="text-rose-500">*</span></label>
                <input type="text" name="holiday_name" value="{{ old('holiday_name', $holiday->holiday_name) }}" required class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 px-3 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all">
                @error('holiday_name')
                    <p class="text-rose-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">تاريخ البداية <span class="text-rose-500">*</span></label>
                <input type="date" name="start_date" value="{{ old('start_date', $holiday->start_date) }}" required class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 px-3 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all">
                @error('start_date')
                    <p class="text-rose-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">تاريخ النهاية <span class="text-rose-500">*</span></label>
                <input type="date" name="end_date" value="{{ old('end_date', $holiday->end_date) }}" required class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 px-3 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all">
                @error('end_date')
                    <p class="text-rose-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_recurring" value="1" id="is_recurring_edit" class="h-4 w-4 rounded border-slate-300 text-cyan-500 focus:ring-cyan-500" {{ old('is_recurring', $holiday->is_recurring) ? 'checked' : '' }}>
                <label for="is_recurring_edit" class="text-sm font-semibold text-slate-700 dark:text-slate-300">إجازة متكررة سنوياً</label>
            </div>
        </div>

        <div class="flex items-center justify-end gap-2.5 border-t border-slate-200 dark:border-white/5 pt-4">
            <a href="{{ route('holidays.index') }}" class="form-cancel-btn rounded-xl bg-slate-200 hover:bg-slate-300 dark:bg-slate-800 dark:hover:bg-slate-700 border border-slate-300 dark:border-white/5 px-4 py-2.5 text-xs font-bold text-slate-700 dark:text-slate-300 transition active:scale-95">إلغاء والعودة</a>
            <button type="submit" class="rounded-xl bg-gradient-to-l from-cyan-500 to-blue-600 hover:opacity-95 px-4 py-2.5 text-xs font-bold text-white shadow-lg shadow-blue-600/10 transition active:scale-95 cursor-pointer">تحديث الإجازة</button>
        </div>
    </form>
</div>
@endsection
