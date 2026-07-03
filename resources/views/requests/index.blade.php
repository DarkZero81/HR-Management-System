@extends('layouts.app')

@section('title', 'الإجازات والطلبات')

@section('content')
<div class="space-y-6">
    <div class="rounded-[32px] border border-slate-200/70 bg-gradient-to-br from-slate-950 to-slate-900 p-6 text-white shadow-[0_25px_90px_-35px_rgba(15,23,42,0.45)]">
        <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.35em] text-slate-400">الإجازات والطلبات</p>
                <h1 class="mt-2 text-3xl font-black">قدم طلبك بسرعة</h1>
                <p class="mt-2 max-w-2xl text-sm text-slate-300">أنشئ طلبات الإجازة، الإذن، أو النقل واطّلع على حالة الطلبات السابقة.</p>
            </div>
            <a href="{{ route('my.requests.create') }}" class="inline-flex items-center justify-center rounded-2xl bg-sky-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-sky-600">
                <i data-lucide="plus" class="ml-2 h-4 w-4"></i>
                طلب جديد
            </a>
        </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
        <div class="rounded-[32px] border border-slate-200/70 bg-white/90 p-6 shadow-[0_25px_90px_-35px_rgba(15,23,42,0.12)] backdrop-blur">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm font-semibold text-slate-500">إنشاء طلب</p>
                    <h2 class="text-2xl font-black text-slate-900">بوابة الموظف</h2>
                </div>
                <span class="rounded-full bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700">سريعة وسهلة</span>
            </div>

            <form action="{{ route('my.requests.store') }}" method="POST" class="mt-6 space-y-5">
                @csrf
                <div class="grid gap-4 md:grid-cols-2">
                    <label class="block">
                        <span class="text-sm font-semibold text-slate-700">نوع الطلب</span>
                        <select name="transaction_type" class="mt-2 w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-sky-400 focus:ring-2 focus:ring-sky-200">
                            <option value="leave">إجازة</option>
                            <option value="permission">إذن</option>
                            <option value="promotion">ترقية</option>
                            <option value="penalty">عقوبة</option>
                            <option value="transfer">نقل</option>
                        </select>
                    </label>
                    <label class="block">
                        <span class="text-sm font-semibold text-slate-700">التأثير المالي</span>
                        <input type="number" step="0.01" name="financial_impact" value="0" class="mt-2 w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-sky-400 focus:ring-2 focus:ring-sky-200" />
                    </label>
                </div>
                <div class="grid gap-4 md:grid-cols-2">
                    <label class="block">
                        <span class="text-sm font-semibold text-slate-700">تاريخ البداية</span>
                        <input type="datetime-local" name="start_date_time" class="mt-2 w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-sky-400 focus:ring-2 focus:ring-sky-200" />
                    </label>
                    <label class="block">
                        <span class="text-sm font-semibold text-slate-700">تاريخ النهاية</span>
                        <input type="datetime-local" name="end_date_time" class="mt-2 w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-sky-400 focus:ring-2 focus:ring-sky-200" />
                    </label>
                </div>
                <label class="block">
                    <span class="text-sm font-semibold text-slate-700">ملاحظات الطلب</span>
                    <textarea name="description" rows="4" class="mt-2 w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-sky-400 focus:ring-2 focus:ring-sky-200" placeholder="اكتب سبب الطلب أو التفاصيل الإضافية..."></textarea>
                </label>
                <button type="submit" class="rounded-3xl bg-slate-950 px-6 py-3 text-sm font-semibold text-white transition hover:bg-slate-900">إرسال الطلب</button>
            </form>
        </div>

        <div class="space-y-6">
            <div class="rounded-[32px] border border-slate-200/70 bg-slate-950 p-6 text-white shadow-[0_25px_90px_-35px_rgba(15,23,42,0.35)]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-slate-400">المراجعة</p>
                        <h2 class="text-2xl font-black">طلبات الانتظار</h2>
                    </div>
                    <span class="rounded-full bg-white/10 px-4 py-2 text-sm font-semibold text-white">{{ $transactions->where('status', 'pending')->count() }} معلقة</span>
                </div>
                <div class="mt-6 grid gap-3">
                    @livewire('requests-list')
                </div>
            </div>

            <div class="rounded-[32px] border border-slate-200/70 bg-white/90 p-6 shadow-[0_25px_90px_-35px_rgba(15,23,42,0.12)] backdrop-blur">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-semibold text-slate-500">سجل الطلبات</p>
                    <a href="{{ route('requests.index') }}" class="text-sm font-semibold text-blue-600">عرض الكل</a>
                </div>
                <div class="mt-5 space-y-4">
                    @forelse($transactions as $transaction)
                        <div class="rounded-[24px] border border-slate-200 bg-slate-50 p-4">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <p class="font-semibold text-slate-900">{{ $transaction->employee->full_name ?? '—' }}</p>
                                    <p class="mt-1 text-sm text-slate-500">{{ $transaction->description ?? 'طلب جديد' }}</p>
                                </div>
                                <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">{{ $transaction->status }}</span>
                            </div>
                            <div class="mt-4 flex flex-wrap gap-2 text-sm text-slate-600">
                                <span class="rounded-full bg-white px-3 py-1">النوع: {{ $transaction->transaction_type }}</span>
                                <span class="rounded-full bg-white px-3 py-1">{{ $transaction->start_date_time?->format('Y-m-d') ?? 'غير محدد' }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">لا توجد طلبات حالياً.</p>
                    @endforelse
                </div>
                <div class="mt-4">{{ $transactions->links() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
