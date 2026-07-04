@extends('layouts.app')

@section('title', 'تعديل الإجازة')

@section('content')
<div class="space-y-6">
    <div class="rounded-[28px] border border-white/10 bg-slate-900/70 p-6 shadow-[0_20px_60px_-35px_rgba(15,23,42,0.45)] backdrop-blur">
        <div class="flex items-center justify-between gap-3">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.35em] text-slate-400">الإجازات</p>
                <h2 class="mt-2 text-3xl font-black text-white">تعديل الإجازة</h2>
                <p class="mt-2 text-sm text-slate-400">تحديث تفاصيل فترة الإجازة.</p>
            </div>
            <a href="{{ route('holidays.index') }}" class="inline-flex items-center gap-2 rounded-2xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm font-semibold text-slate-300 transition hover:bg-white/10">
                <i data-lucide="arrow-right" class="h-4 w-4"></i>
                رجوع
            </a>
        </div>

        <form method="POST" action="{{ route('holidays.update', $holiday) }}" class="mt-6 max-w-2xl space-y-5">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-semibold text-slate-300 mb-2">اسم الإجازة</label>
                <input type="text" name="holiday_name" class="w-full rounded-2xl border border-white/10 bg-slate-950/50 px-4 py-3 text-sm text-white outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-500 @error('holiday_name') border-rose-400 @enderror" value="{{ old('holiday_name', $holiday->holiday_name) }}" required>
                @error('holiday_name')
                    <p class="mt-2 text-sm text-rose-300">{{ $message }}</p>
                @enderror
            </div>
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">تاريخ البداية</label>
                    <input type="date" name="start_date" class="w-full rounded-2xl border border-white/10 bg-slate-950/50 px-4 py-3 text-sm text-white outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-500 @error('start_date') border-rose-400 @enderror" value="{{ old('start_date', $holiday->start_date) }}" required>
                    @error('start_date')
                        <p class="mt-2 text-sm text-rose-300">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">تاريخ النهاية</label>
                    <input type="date" name="end_date" class="w-full rounded-2xl border border-white/10 bg-slate-950/50 px-4 py-3 text-sm text-white outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-500 @error('end_date') border-rose-400 @enderror" value="{{ old('end_date', $holiday->end_date) }}" required>
                    @error('end_date')
                        <p class="mt-2 text-sm text-rose-300">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_recurring" value="1" id="is_recurring_edit" class="h-4 w-4 rounded border-white/10 bg-slate-950/50 text-cyan-500 focus:ring-cyan-500" {{ old('is_recurring', $holiday->is_recurring) ? 'checked' : '' }}>
                <label for="is_recurring_edit" class="text-sm font-semibold text-slate-300">إجازة متكررة سنوياً</label>
            </div>
            <button type="submit" class="rounded-2xl bg-gradient-to-l from-cyan-500 to-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-lg transition hover:opacity-90">
                تحديث الإجازة
            </button>
        </form>
    </div>
</div>
@endsection