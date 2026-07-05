@extends('layouts.app')

@section('title', 'الإجازات والطلبات')

@section('content')
<div class="space-y-6">
    <section class="rounded-[32px] bg-gradient-to-r from-slate-950 to-slate-900 p-6 text-white shadow-2xl shadow-slate-950/20">
        <div class="flex flex-col gap-5 xl:flex-row xl:items-center xl:justify-between">
            <div class="space-y-3 text-right">
                <p class="text-sm uppercase tracking-[0.35em] text-slate-400">الإجازات والطلبات</p>
                <h1 class="text-3xl font-black">قدم طلبك بسهولة</h1>
                <p class="text-sm text-slate-300">أنشئ طلبات الإجازة، الإذن، أو النقل وتابع حالتها من لوحة واحدة.</p>
            </div>
            <a href="{{ route('my.requests.create') }}" class="inline-flex items-center rounded-3xl bg-cyan-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-cyan-600">
                <i data-lucide="plus" class="ml-2 h-4 w-4"></i>
                طلب جديد
            </a>
        </div>
    </section>

    <section class="grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
        <div class="rounded-[32px] border border-white/10 bg-slate-900/70 p-6 shadow-sm">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm font-semibold text-slate-400">بوابة الطلبات</p>
                    <h2 class="text-2xl font-black text-white">أنشئ طلبك الآن</h2>
                </div>
                <span class="rounded-full bg-white/10 px-4 py-2 text-sm font-semibold text-slate-300">سهل وسريع</span>
            </div>

            <form action="{{ route('my.requests.store') }}" method="POST" class="mt-6 space-y-5">
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

        <div class="space-y-6">
            <div class="rounded-[32px] bg-slate-950 p-6 text-white shadow-xl shadow-slate-950/20">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-sm uppercase tracking-[0.35em] text-slate-400">طلبات الانتظار</p>
                        <h2 class="text-2xl font-black">المراجعة السريعة</h2>
                    </div>
                    <span class="rounded-full bg-white/10 px-4 py-2 text-sm font-semibold text-white">{{ $transactions->where('status', 'pending')->count() }} معلقة</span>
                </div>
                <div class="mt-6 space-y-3">
                    @forelse($transactions->where('status', 'pending')->take(5) as $transaction)
                        <div class="rounded-[24px] border border-white/10 bg-white/5 p-4 text-sm text-slate-300">
                            <div class="flex items-center justify-between gap-3">
                                <span class="font-semibold text-white">{{ $transaction->employee?->full_name ?? '—' }}</span>
                                <span class="rounded-full bg-amber-500/20 px-3 py-1 text-xs font-semibold text-amber-300">{{ $transaction->status }}</span>
                            </div>
                            <p class="mt-2 text-slate-400">{{ $transaction->description ?? 'طلب جديد' }}</p>
                            <form action="{{ route('requests.update_status', $transaction) }}" method="POST" class="mt-3 flex flex-wrap gap-2">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" class="rounded-full bg-emerald-500/15 px-3 py-1.5 text-xs font-semibold text-emerald-300 hover:bg-emerald-500/25 transition">قبول</button>
                            </form>
                            <form action="{{ route('requests.update_status', $transaction) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="rounded-full bg-rose-500/15 px-3 py-1.5 text-xs font-semibold text-rose-300 hover:bg-rose-500/25 transition">رفض</button>
                            </form>
                        </div>
                    @empty
                        <p class="text-sm text-slate-400">لا توجد طلبات معلقة حالياً.</p>
                    @endforelse
                </div>
            </div>

            <div class="rounded-[32px] border border-white/10 bg-slate-900/70 p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-semibold text-slate-400">سجل الطلبات</p>
                    @if(in_array(optional(auth()->user()->role)->role_name, ['admin', 'hr', 'manager'], true))
                        <a href="{{ route('requests.index') }}" class="text-sm font-semibold text-cyan-300 hover:text-cyan-200 transition">عرض الكل</a>
                    @endif
                </div>
                <div class="mt-5 space-y-4">
                    @forelse($transactions as $transaction)
                        <div class="rounded-[24px] border border-white/10 bg-white/5 p-4">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <p class="font-semibold text-white">{{ $transaction->employee->full_name ?? '—' }}</p>
                                    <p class="mt-1 text-sm text-slate-400">{{ $transaction->description ?? 'طلب جديد' }}</p>
                                </div>
                                <span class="rounded-full bg-amber-500/15 px-3 py-1 text-xs font-semibold text-amber-300">{{ $transaction->status }}</span>
                            </div>
                            <div class="mt-4 flex flex-wrap gap-2 text-sm text-slate-300">
                                <span class="rounded-full bg-white/5 px-3 py-1">النوع: {{ $transaction->transaction_type }}</span>
                                <span class="rounded-full bg-white/5 px-3 py-1">{{ $transaction->start_date_time?->format('Y-m-d') ?? 'غير محدد' }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-400">لا توجد طلبات حالياً.</p>
                    @endforelse
                </div>
                <div class="mt-4">{{ $transactions->links() }}</div>
            </div>
        </div>
    </section>
</div>
@endsection
