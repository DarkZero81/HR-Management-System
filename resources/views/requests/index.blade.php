@extends('layouts.app')

@section('title', 'الإجازات والطلبات')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-xs font-black uppercase tracking-[0.35em] text-slate-400">الإجازات والطلبات</p>
            <h1 class="text-3xl font-bold text-slate-800">قدم طلبك بسهولة</h1>
            <p class="text-sm text-slate-400 mt-1">أنشئ طلبات الإجازة، الإذن، أو النقل وتابع حالتها.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-white rounded-xl font-medium transition-all border border-white/10">
                <i data-lucide="user" class="w-4 h-4"></i>
                <span class="hidden sm:inline">الملف الشخصي</span>
            </a>
            <a href="{{ route('my.requests.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-500 to-teal-400 hover:from-blue-600 hover:to-teal-500 text-white rounded-xl font-semibold shadow-lg transition-all">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span class="hidden sm:inline">طلب جديد</span>
            </a>
        </div>
    </div>

    @if(session('error'))
        <div class="rounded-2xl border border-rose-400/20 bg-rose-500/10 px-4 py-3 text-sm font-semibold text-rose-200 flex items-center gap-2">
            <i data-lucide="alert-circle" class="w-5 h-5"></i>
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="employee-form-card rounded-2xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                    <i data-lucide="file-text" class="w-5 h-5 text-blue-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-black text-slate-800">{{ $stats['total'] }}</p>
                    <p class="text-xs text-slate-500">إجمالي الطلبات</p>
                </div>
            </div>
        </div>
        <div class="employee-form-card rounded-2xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center">
                    <i data-lucide="clock" class="w-5 h-5 text-amber-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-black text-slate-800">{{ $stats['pending'] }}</p>
                    <p class="text-xs text-slate-500">معلقة</p>
                </div>
            </div>
        </div>
        <div class="employee-form-card rounded-2xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                    <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-black text-slate-800">{{ $stats['approved'] }}</p>
                    <p class="text-xs text-slate-500">موافق عليها</p>
                </div>
            </div>
        </div>
        <div class="employee-form-card rounded-2xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-rose-100 flex items-center justify-center">
                    <i data-lucide="x-circle" class="w-5 h-5 text-rose-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-black text-slate-800">{{ $stats['rejected'] }}</p>
                    <p class="text-xs text-slate-500">مرفوضة</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-sm font-semibold text-slate-500">بوابة الطلبات</p>
                    <h2 class="text-xl font-bold text-slate-800 mt-1">أنشئ طلبك الآن</h2>
                </div>
                <span class="rounded-full bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-900">سهل وسريع</span>
            </div>

            <form action="{{ route('my.requests.store') }}" method="POST" class="mt-6 space-y-5">
                @csrf
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">نوع الطلب</label>
                        <select style="color:black" name="transaction_type" class="  w-full px-7 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all cursor-pointer" required>
                            @foreach($transaction_types as $type)
                                <option style="color:black"  value="{{ $type }}" {{ old('transaction_type') == $type ? 'selected' : '' }}>{{ match($type) { 'leave' => 'إجازة', 'permission' => 'إذن', 'promotion' => 'ترقية', 'penalty' => 'عقوبة', 'transfer' => 'نقل' } }}</option>
                            @endforeach
                        </select>
                        @error('transaction_type')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">التأثير المالي</label>
                        <input type="number" step="0.01" name="financial_impact" value="{{ old('financial_impact', 0) }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        @error('financial_impact')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">تاريخ البداية</label>
                        <input style="color:black" type="datetime-local" name="start_date_time" value="{{ old('start_date_time') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" required>
                        @error('start_date_time')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">تاريخ النهاية</label>
                        <input style="color:black" type="datetime-local" name="end_date_time" value="{{ old('end_date_time') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" required>
                        @error('end_date_time')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">ملاحظات الطلب</label>
                    <textarea name="description" rows="4" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all resize-none" placeholder="اكتب سبب الطلب أو التفاصيل الإضافية...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="w-full py-3 bg-gradient-to-r from-blue-500 to-teal-400 hover:from-blue-600 hover:to-teal-500 text-white font-semibold rounded-xl shadow-lg transition-all">
                    إرسال الطلب
                </button>
            </form>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-sm font-semibold text-slate-500">طلبات الانتظار</p>
                    <h2 class="text-xl font-bold text-slate-800 mt-1">المراجعة السريعة</h2>
                </div>
                <span class="rounded-full bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-900">{{ $stats['pending'] }} معلقة</span>
            </div>
    <div class="mt-6 space-y-3">
        @forelse($pendingTransactions as $transaction)
            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                <div class="flex items-center justify-between gap-3">
                    <span class="font-semibold text-slate-800">{{ $transaction->employee?->full_name ?? '—' }}</span>
                    <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">{{ match($transaction->status) { 'pending' => 'معلقة', 'approved' => 'موافق عليها', 'rejected' => 'مرفوضة', default => $transaction->status } }}</span>
                </div>
                <p class="mt-2 text-sm text-slate-600">{{ $transaction->description ?? 'طلب جديد' }}</p>
                <div class="my-4 flex flex-wrap gap-2">
                    <form action="{{ route('requests.update_status', $transaction) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="approved">
                        <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg transition">قبول</button>
                    </form>
                    <form action="{{ route('requests.update_status', $transaction) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="rejected">
                        <button type="submit" class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-lg transition">رفض</button>
                    </form>
                </div>
            </div>
        @empty
                    <p class="text-sm text-slate-500 text-center py-4">لا توجد طلبات معلقة حالياً.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
        <div class="flex items-center justify-between mb-4">
            <p class="text-sm font-semibold text-slate-500">سجل الطلبات</p>
        </div>
        <div class="mt-5 space-y-4">
            @forelse($transactions as $transaction)
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="font-semibold text-slate-800">{{ $transaction->employee->full_name ?? '—' }}</p>
                            <p class="mt-1 text-sm text-slate-600">{{ $transaction->description ?? 'طلب جديد' }}</p>
                        </div>
                        <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">{{ match($transaction->status) { 'pending' => 'معلقة', 'approved' => 'موافق عليها', 'rejected' => 'مرفوضة', default => $transaction->status } }}</span>
                    </div>
                    <div class="mt-4 flex flex-wrap gap-2 text-sm text-slate-600">
                        <span class="rounded-full bg-white px-3 py-1 border border-slate-200">النوع: {{ match($transaction->transaction_type) { 'leave' => 'إجازة', 'permission' => 'إذن', 'promotion' => 'ترقية', 'penalty' => 'عقوبة', 'transfer' => 'نقل', default => $transaction->transaction_type } }}</span>
                        <span class="rounded-full bg-white px-3 py-1 border border-slate-200">{{ $transaction->start_date_time?->format('Y-m-d') ?? 'غير محدد' }}</span>
                    </div>
                </div>
            @empty
                <p class="text-sm text-slate-500 text-center py-4">لم يتم تقديم أي طلبات بعد.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
