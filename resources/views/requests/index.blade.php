@extends('layouts.app')

@section('title', 'الطلبات والحركات')

@section('content')
<div class="grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
    <div class="rounded-[28px] border border-slate-200/70 bg-white/80 p-6 shadow-[0_20px_60px_-35px_rgba(15,23,42,0.45)] backdrop-blur">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-slate-500">بوابة الموظف</p>
                <h2 class="text-2xl font-black text-slate-900">إنشاء طلب جديد</h2>
            </div>
            <span class="rounded-full bg-blue-100 px-3 py-1 text-sm font-semibold text-blue-700">3 خطوات</span>
        </div>

        <form action="{{ route('my.requests.store') }}" method="POST" class="mt-6 space-y-4">
            @csrf
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">نوع الطلب</label>
                    <select name="transaction_type" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="leave">إجازة</option>
                        <option value="permission">إذن</option>
                        <option value="promotion">ترقية</option>
                        <option value="penalty">عقوبة</option>
                        <option value="transfer">نقل</option>
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">التأثير المالي</label>
                    <input type="number" step="0.01" name="financial_impact" value="0" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-blue-500" />
                </div>
            </div>
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">تاريخ البداية</label>
                    <input type="datetime-local" name="start_date_time" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-blue-500" />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">تاريخ النهاية</label>
                    <input type="datetime-local" name="end_date_time" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-blue-500" />
                </div>
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">ملاحظات الطلب</label>
                <textarea name="description" rows="4" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-blue-500" placeholder="اكتب سبب الطلب أو التفاصيل الإضافية..."></textarea>
            </div>
            <button type="submit" class="rounded-2xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">إرسال الطلب</button>
        </form>
    </div>

    <div class="rounded-[28px] border border-slate-200/70 bg-white/80 p-6 shadow-[0_20px_60px_-35px_rgba(15,23,42,0.45)] backdrop-blur">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-slate-500">مجموعة الإدارة</p>
                <h2 class="text-2xl font-black text-slate-900">قائمة المراجعة</h2>
            </div>
            <span class="rounded-full bg-amber-100 px-3 py-1 text-sm font-semibold text-amber-700">تحتاج موافقة</span>
        </div>
        <div class="mt-5 space-y-4">
            @forelse($transactions as $transaction)
                <div class="rounded-[24px] border border-slate-200 bg-slate-50 p-4">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="font-semibold text-slate-900">{{ $transaction->employee->full_name ?? '—' }}</p>
                            <p class="mt-1 text-sm text-slate-500">{{ $transaction->description ?? 'طلب جديد' }}</p>
                        </div>
                        <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">{{ $transaction->status }}</span>
                    </div>
                    <div class="mt-4 flex flex-wrap gap-3 text-sm text-slate-600">
                        <span class="rounded-full bg-white px-3 py-1">النوع: {{ $transaction->transaction_type }}</span>
                        <span class="rounded-full bg-white px-3 py-1">التأثير المالي: {{ number_format((float) $transaction->financial_impact, 2) }} د.ع</span>
                        <span class="rounded-full bg-white px-3 py-1">{{ $transaction->start_date_time?->format('Y-m-d') }}</span>
                    </div>
                    <div class="mt-4 flex gap-3">
                        <form action="{{ route('requests.update_status', $transaction) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="approved">
                            <button class="rounded-full bg-emerald-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-700">قبول</button>
                        </form>
                        <form action="{{ route('requests.update_status', $transaction) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="rejected">
                            <button class="rounded-full bg-rose-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-rose-700">رفض</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-sm text-slate-500">لا توجد طلبات حالياً.</p>
            @endforelse
        </div>
        <div class="mt-4">{{ $transactions->links() }}</div>
    </div>
</div>
@endsection
