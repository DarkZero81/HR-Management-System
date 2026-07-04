@extends('layouts.app')
@section('title', 'طلب جديد')
@section('content')
<div class="space-y-6">
    <div class="rounded-[28px] border border-white/10 bg-slate-900/70 p-6 shadow-[0_20px_60px_-35px_rgba(15,23,42,0.45)] backdrop-blur">
        <div class="flex items-center justify-between gap-3">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.35em] text-slate-400">الطلبات</p>
                <h2 class="mt-2 text-3xl font-black text-white">تقديم طلب جديد</h2>
                <p class="mt-2 text-sm text-slate-400">أرسل طلب إجازة، إذن، ترقية، أو نقل.</p>
            </div>
            <a href="{{ route('my.requests.index') }}" class="inline-flex items-center gap-2 rounded-2xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm font-semibold text-slate-300 transition hover:bg-white/10">
                <i data-lucide="arrow-right" class="h-4 w-4"></i>
                رجوع
            </a>
        </div>

        <form method="POST" action="{{ route('my.requests.store') }}" class="mt-6 max-w-2xl space-y-5">
            @csrf
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">نوع الطلب</label>
                    <select name="transaction_type" class="w-full rounded-2xl border border-white/10 bg-slate-950/50 px-4 py-3 text-sm text-white outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-500 @error('transaction_type') border-rose-400 @enderror" required>
                        @foreach($transaction_types as $type)
                            <option value="{{ $type }}" {{ old('transaction_type') == $type ? 'selected' : '' }}>{{ match($type) { 'leave' => 'إجازة', 'permission' => 'إذن', 'promotion' => 'ترقية', 'penalty' => 'عقوبة', 'transfer' => 'نقل' } }}</option>
                        @endforeach
                    </select>
                    @error('transaction_type')
                        <p class="mt-2 text-sm text-rose-300">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">التأثير المالي</label>
                    <input type="number" step="0.01" name="financial_impact" class="w-full rounded-2xl border border-white/10 bg-slate-950/50 px-4 py-3 text-sm text-white outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-500 @error('financial_impact') border-rose-400 @enderror" value="{{ old('financial_impact', 0) }}">
                    @error('financial_impact')
                        <p class="mt-2 text-sm text-rose-300">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">تاريخ البداية</label>
                    <input type="datetime-local" name="start_date_time" class="w-full rounded-2xl border border-white/10 bg-slate-950/50 px-4 py-3 text-sm text-white outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-500 @error('start_date_time') border-rose-400 @enderror" value="{{ old('start_date_time') }}" required>
                    @error('start_date_time')
                        <p class="mt-2 text-sm text-rose-300">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">تاريخ النهاية</label>
                    <input type="datetime-local" name="end_date_time" class="w-full rounded-2xl border border-white/10 bg-slate-950/50 px-4 py-3 text-sm text-white outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-500 @error('end_date_time') border-rose-400 @enderror" value="{{ old('end_date_time') }}" required>
                    @error('end_date_time')
                        <p class="mt-2 text-sm text-rose-300">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-300 mb-2">ملاحظات الطلب</label>
                <textarea name="description" rows="4" class="w-full rounded-2xl border border-white/10 bg-slate-950/50 px-4 py-3 text-sm text-white outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-500 @error('description') border-rose-400 @enderror" placeholder="اكتب سبب الطلب أو التفاصيل الإضافية...">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-2 text-sm text-rose-300">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="rounded-2xl bg-gradient-to-l from-cyan-500 to-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-lg transition hover:opacity-90">
                إرسال الطلب
            </button>
        </form>
    </div>
</div>
@endsection