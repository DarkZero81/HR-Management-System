@extends('layouts.app')
@section('title', 'طلب جديد')
@section('content')
<div class="max-w-3xl mx-auto space-y-6 px-4 py-4" dir="rtl">
    <div class="border-b border-white/5 dark:border-white/10 pb-4">
        <p class="text-xs font-black uppercase tracking-[0.35em] text-blue-400 dark:text-cyan-400">الطلبات</p>
        <h1 class="text-3xl font-bold text-slate-800">تقديم طلب جديد</h1>
        <p class="text-sm text-slate-400 dark:text-slate-500 mt-1">أرسل طلب إجازة، إذن، ترقية، أو نقل.</p>
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

    <form method="POST" action="{{ route('my.requests.store') }}" class="employee-form-card rounded-[28px] border border-white/10 dark:border-white/5 p-6 space-y-5 shadow-2xl backdrop-blur-md">
        @csrf
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <div>
                <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">نوع الطلب <span class="text-rose-500">*</span></label>
                <select name="transaction_type" required class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 px-8 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all cursor-pointer">
                    @foreach($transaction_types as $type)
                        <option value="{{ $type }}" {{ old('transaction_type') == $type ? 'selected' : '' }} class="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200">{{ match($type) { 'leave' => 'إجازة', 'permission' => 'إذن', 'promotion' => 'ترقية', 'penalty' => 'عقوبة', 'transfer' => 'نقل' } }}</option>
                    @endforeach
                </select>
                @error('transaction_type')
                    <p class="text-rose-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">التأثير المالي</label>
                <input type="number" step="0.01" name="financial_impact" value="{{ old('financial_impact', 0) }}" class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-white px-3 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all">
                @error('financial_impact')
                    <p class="text-rose-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">تاريخ البداية <span class="text-rose-500">*</span></label>
                <input type="datetime-local" name="start_date_time" value="{{ old('start_date_time') }}" required class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 px-3 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all">
                @error('start_date_time')
                    <p class="text-rose-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">تاريخ النهاية <span class="text-rose-500">*</span></label>
                <input type="datetime-local" name="end_date_time" value="{{ old('end_date_time') }}" required class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 px-3 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all">
                @error('end_date_time')
                    <p class="text-rose-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-2">
                <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">ملاحظات الطلب</label>
                <textarea name="description" rows="4" class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 px-3 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all resize-none" placeholder="اكتب سبب الطلب أو التفاصيل الإضافية...">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-rose-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex items-center justify-end gap-2.5 border-t border-slate-200 dark:border-white/5 pt-4">
            <a href="{{ route('my.requests.index') }}" class="rounded-xl bg-slate-200 hover:bg-slate-300 dark:bg-slate-800 dark:hover:bg-slate-700 border border-slate-300 dark:border-white/5 px-4 py-2.5 text-xs font-bold text-slate-700 dark:text-slate-300 transition active:scale-95">إلغاء والعودة</a>
            <button type="submit" class="rounded-xl bg-gradient-to-l from-cyan-500 to-blue-600 hover:opacity-95 px-4 py-2.5 text-xs font-bold text-white shadow-lg shadow-blue-600/10 transition active:scale-95 cursor-pointer">إرسال الطلب</button>
        </div>
    </form>
</div>
@endsection
