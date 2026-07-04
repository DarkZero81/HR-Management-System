@extends('layouts.app')

@section('title', 'إضافة وردية جديدة')

@section('content')
<div class="space-y-6">
    <div class="rounded-[28px] border border-white/10 bg-slate-900/70 p-6 shadow-[0_20px_60px_-35px_rgba(15,23,42,0.45)] backdrop-blur">
        <div class="flex items-center justify-between gap-3">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.35em] text-slate-400">الورديات</p>
                <h2 class="mt-2 text-3xl font-black text-white">إضافة وردية جديدة</h2>
                <p class="mt-2 text-sm text-slate-400">حدد مواعيد العمل وفترة السماح للتأخير.</p>
            </div>
            <a href="{{ route('shifts.index') }}" class="inline-flex items-center gap-2 rounded-2xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm font-semibold text-slate-300 transition hover:bg-white/10">
                <i data-lucide="arrow-right" class="h-4 w-4"></i>
                رجوع
            </a>
        </div>

        <form method="POST" action="{{ route('shifts.store') }}" class="mt-6 max-w-2xl space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-slate-300 mb-2">اسم الوردية</label>
                <input type="text" name="shift_name" class="w-full rounded-2xl border border-white/10 bg-slate-950/50 px-4 py-3 text-sm text-white outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-500 @error('shift_name') border-rose-400 @enderror" value="{{ old('shift_name') }}" required>
                @error('shift_name')
                    <p class="mt-2 text-sm text-rose-300">{{ $message }}</p>
                @enderror
            </div>
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">وقت البداية</label>
                    <input type="time" name="start_time" class="w-full rounded-2xl border border-white/10 bg-slate-950/50 px-4 py-3 text-sm text-white outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-500 @error('start_time') border-rose-400 @enderror" value="{{ old('start_time') }}" required>
                    @error('start_time')
                        <p class="mt-2 text-sm text-rose-300">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">وقت النهاية</label>
                    <input type="time" name="end_time" class="w-full rounded-2xl border border-white/10 bg-slate-950/50 px-4 py-3 text-sm text-white outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-500 @error('end_time') border-rose-400 @enderror" value="{{ old('end_time') }}" required>
                    @error('end_time')
                        <p class="mt-2 text-sm text-rose-300">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-300 mb-2">فترة السماح للتأخير (دقيقة)</label>
                <input type="number" name="grace_period_minutes" class="w-full rounded-2xl border border-white/10 bg-slate-950/50 px-4 py-3 text-sm text-white outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-500 @error('grace_period_minutes') border-rose-400 @enderror" value="{{ old('grace_period_minutes', 0) }}" min="0">
                @error('grace_period_minutes')
                    <p class="mt-2 text-sm text-rose-300">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="rounded-2xl bg-gradient-to-l from-cyan-500 to-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-lg transition hover:opacity-90">
                حفظ الوردية
            </button>
        </form>
    </div>
</div>
@endsection