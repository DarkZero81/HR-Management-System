@extends('layouts.app')

@section('title', 'تعديل الإجازة')

@section('content')
<div class="max-w-3xl mx-auto space-y-6" dir="rtl">
    <div>
        <p class="text-xs font-black uppercase tracking-[0.35em] text-slate-400">الإجازات</p>
        <h1 class="text-2xl md:text-3xl font-black text-white mt-1">تعديل الإجازة</h1>
        <p class="text-sm text-slate-400 mt-1">تحديث تفاصيل فترة الإجازة.</p>
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

    <form method="POST" action="{{ route('holidays.update', $holiday) }}" class="rounded-[28px] border border-white/10 bg-slate-900/40 p-6 space-y-6 shadow-xl">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">اسم الإجازة <span class="text-rose-500">*</span></label>
                <input type="text" name="holiday_name" value="{{ old('holiday_name', $holiday->holiday_name) }}" required class="w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-2.5 text-sm text-white focus:border-blue-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">تاريخ البداية <span class="text-rose-500">*</span></label>
                <input type="date" name="start_date" value="{{ old('start_date', $holiday->start_date) }}" required class="w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-2.5 text-sm text-white focus:border-blue-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">تاريخ النهاية <span class="text-rose-500">*</span></label>
                <input type="date" name="end_date" value="{{ old('end_date', $holiday->end_date) }}" required class="w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-2.5 text-sm text-white focus:border-blue-500 focus:outline-none">
            </div>

            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_recurring" value="1" id="is_recurring_edit" class="h-4 w-4 rounded border-white/10 bg-slate-950 text-cyan-500 focus:ring-cyan-500" {{ old('is_recurring', $holiday->is_recurring) ? 'checked' : '' }}>
                <label for="is_recurring_edit" class="text-sm font-semibold text-slate-300">إجازة متكررة سنوياً</label>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 border-t border-white/5 pt-4">
            <a href="{{ route('holidays.index') }}" class="rounded-xl bg-slate-950 border border-white/10 px-5 py-2.5 text-sm font-bold text-slate-400 transition hover:text-white">
                إلغاء العودة
            </a>
            <button type="submit" class="rounded-xl bg-amber-500 px-5 py-2.5 text-sm font-bold text-slate-950 shadow-lg shadow-amber-500/25 transition hover:bg-amber-400">
                تحديث الإجازة
            </button>
        </div>
    </form>
</div>
@endsection