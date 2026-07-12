@extends('layouts.app')
@section('title', isset($shift) ? 'تعديل الوردية' : 'إضافة وردية جديدة')
@section('content')
<div class="max-w-3xl mx-auto space-y-6 px-4 py-4" dir="rtl">
    <div class="border-b border-white/5 pb-4">
        <p class="text-xs font-black uppercase tracking-[0.35em] text-blue-400 dark:text-cyan-400">الورديات</p>
        <h1 class="text-3xl font-bold text-slate-800 mt-0.5">{{ isset($shift) ? 'تعديل الوردية: ' . $shift->shift_name : 'إضافة وردية جديدة' }}</h1>
        <p class="text-sm text-slate-400 dark:text-slate-500 mt-1">{{ isset($shift) ? 'تحديث مواعيد العمل وفترة السماح.' : 'حدد مواعيد العمل وفترة السماح للتأخير.' }}</p>
    </div>
    @if(session('success'))
        <div class="rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-4 py-3 text-sm font-semibold text-emerald-200 flex items-center gap-2">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="rounded-2xl border border-rose-400/20 bg-rose-500/10 px-4 py-3 text-sm font-semibold text-rose-200 flex items-center gap-2">
            <i data-lucide="alert-circle" class="w-5 h-5"></i>
            {{ session('error') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="rounded-2xl border border-rose-500/20 bg-rose-500/10 p-4">
            <ul class="list-inside list-disc text-xs font-medium text-rose-400 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ isset($shift) ? route('shifts.update', $shift) : route('shifts.store') }}" class="employee-form-card rounded-[28px] border border-white/10 dark:border-white/5 p-6 space-y-5 shadow-2xl backdrop-blur-md">
        @csrf
        @isset($shift)
            @method('PUT')
        @endisset

        <div>
            <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">اسم الوردية <span class="text-rose-500">*</span></label>
            <input type="text" name="shift_name" value="{{ old('shift_name', $shift->shift_name ?? '') }}" required class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 px-3 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all">
            @error('shift_name')
                <p class="text-rose-500 text-sm mt-1.5 flex items-center gap-1.5">
                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <div>
                <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">وقت البداية <span class="text-rose-500">*</span></label>
                <input type="time" name="start_time" value="{{ old('start_time', isset($shift) ? \Carbon\Carbon::parse($shift->start_time)->format('H:i') : '') }}" required class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 px-3 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all">
                @error('start_time')
                    <p class="text-rose-500 text-sm mt-1.5 flex items-center gap-1.5">
                        <i data-lucide="alert-circle" class="w-4 h-4"></i>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">وقت النهاية <span class="text-rose-500">*</span></label>
                <input type="time" name="end_time" value="{{ old('end_time', isset($shift) ? \Carbon\Carbon::parse($shift->end_time)->format('H:i') : '') }}" required class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 px-3 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all">
                @error('end_time')
                    <p class="text-rose-500 text-sm mt-1.5 flex items-center gap-1.5">
                        <i data-lucide="alert-circle" class="w-4 h-4"></i>
                        {{ $message }}
                    </p>
                @enderror
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">فترة السماح للتأخير (دقيقة)</label>
            <input type="number" name="grace_period_minutes" value="{{ old('grace_period_minutes', $shift->grace_period_minutes ?? 0) }}" min="0" class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 px-3 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all">
            @error('grace_period_minutes')
                <p class="text-rose-500 text-sm mt-1.5 flex items-center gap-1.5">
                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div class="flex items-center gap-3">
            <input type="checkbox" name="is_overnight" id="is_overnight" value="1" {{ old('is_overnight', isset($shift) && $shift->is_overnight ? 'checked' : '') ? 'checked' : '' }} class="w-4 h-4 rounded border-slate-300 text-cyan-600 focus:ring-cyan-500">
            <label for="is_overnight" class="text-sm font-medium text-slate-700 dark:text-slate-300">وردية تمتد عبر منتصف الليل (Overnight)</label>
        </div>

        <div class="flex items-center justify-end gap-2.5 border-t border-slate-200 dark:border-white/5 pt-4">
            <a href="{{ route('shifts.index') }}" class="form-cancel-btn rounded-xl bg-slate-200 hover:bg-slate-300 dark:bg-slate-800 dark:hover:bg-slate-700 border border-slate-300 dark:border-white/5 px-4 py-2.5 text-xs font-bold text-slate-700 dark:text-slate-300 transition active:scale-95">إلغاء والعودة</a>
            <button type="submit" class="rounded-xl bg-gradient-to-l from-cyan-500 to-blue-600 hover:opacity-95 px-4 py-2.5 text-xs font-bold text-white shadow-lg shadow-blue-600/10 transition active:scale-95 cursor-pointer">{{ isset($shift) ? 'تحديث الوردية' : 'حفظ الوردية' }}</button>
        </div>
    </form>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (window.lucide) lucide.createIcons();
    });
</script>
@endsection
