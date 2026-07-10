@extends('layouts.app')
@section('title', 'طلب جديد')
@section('content')
@php
    $typeFields = [
        'leave' => ['label' => 'إجازة', 'icon' => 'calendar-days', 'show_duration' => true, 'show_transfer' => false],
        'permission' => ['label' => 'إذن', 'icon' => 'clock', 'show_duration' => true, 'show_transfer' => false],
        'promotion' => ['label' => 'ترقية', 'icon' => 'trending-up', 'show_duration' => false, 'show_transfer' => false],
        'penalty' => ['label' => 'عقوبة', 'icon' => 'alert-triangle', 'show_duration' => false, 'show_transfer' => false],
        'transfer' => ['label' => 'نقل', 'icon' => 'truck', 'show_duration' => false, 'show_transfer' => true],
    ];
@endphp
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

    <form method="POST" action="{{ route('my.requests.store') }}" class="employee-form-card rounded-[28px] border border-white/10 dark:border-white/5 p-6 space-y-5 shadow-2xl backdrop-blur-md" id="requestForm">
        @csrf
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <div>
                <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">نوع الطلب <span class="text-rose-500">*</span></label>
                <select name="transaction_type" id="transaction_type" required class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 px-8 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all cursor-pointer">
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
                <input type="number" step="0.01" name="financial_impact" id="financial_impact" value="0" class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-white px-3 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all">
                @error('financial_impact')
                    <p class="text-rose-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">تاريخ البداية <span class="text-rose-500">*</span></label>
                <input type="datetime-local" name="start_date_time" id="start_date_time" value="{{ old('start_date_time') }}" required class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 px-3 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all">
                @error('start_date_time')
                    <p class="text-rose-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">تاريخ النهاية <span class="text-rose-500">*</span></label>
                <input type="datetime-local" name="end_date_time" id="end_date_time" value="{{ old('end_date_time') }}" required class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 px-3 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all">
                @error('end_date_time')
                    <p class="text-rose-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div id="durationField" class="sm:col-span-2 rounded-xl border border-blue-200 bg-blue-50 p-4">
                <div class="flex items-center gap-2">
                    <i data-lucide="clock" class="w-5 h-5 text-blue-600"></i>
                    <span class="text-sm font-bold text-blue-800">مدة الطلب: <span id="durationValue">0</span> يوم</span>
                </div>
            </div>

            <div id="transferFields" class="sm:col-span-2 hidden space-y-4 rounded-xl border border-slate-200 bg-slate-50 p-4">
                <h3 class="text-sm font-bold text-slate-700">بيانات النقل</h3>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-2">القسم الجديد</label>
                    <input type="text" name="new_department" class="employee-form-input w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm" placeholder="اسم القسم الجديد">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-2">الوردية الجديدة</label>
                    <input type="text" name="new_shift" class="employee-form-input w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm" placeholder="اسم الوردية الجديدة">
                </div>
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
            <button type="button" id="saveDraftBtn" class="rounded-xl bg-slate-800 hover:bg-slate-700 px-4 py-2.5 text-xs font-bold text-white shadow-lg transition active:scale-95">حفظ كمسودة</button>
            <button type="button" id="submitBtn" class="rounded-xl bg-gradient-to-l from-cyan-500 to-blue-600 hover:opacity-95 px-4 py-2.5 text-xs font-bold text-white shadow-lg shadow-blue-600/10 transition active:scale-95 cursor-pointer">إرسال الطلب</button>
        </div>
    </form>
</div>

{{-- Confirmation Modal --}}
<div id="confirmModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" id="modalOverlay"></div>
    <div class="relative z-10 flex items-center justify-center min-h-screen p-4">
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl p-6 max-w-md w-full">
            <h3 class="text-xl font-bold text-slate-800 mb-2">تأكيد إرسال الطلب</h3>
            <p class="text-sm text-slate-600 mb-4">يرجى مراجعة بيانات الطلب قبل الإرسال:</p>
            <div class="space-y-2 text-sm bg-slate-50 rounded-xl p-4 mb-6">
                <div class="flex justify-between"><span class="text-slate-500">النوع:</span><span id="confirmType" class="font-semibold"></span></div>
                <div class="flex justify-between"><span class="text-slate-500">من:</span><span id="confirmStart" class="font-semibold"></span></div>
                <div class="flex justify-between"><span class="text-slate-500">إلى:</span><span id="confirmEnd" class="font-semibold"></span></div>
                <div class="flex justify-between"><span class="text-slate-500">المدة:</span><span id="confirmDuration" class="font-semibold"></span></div>
                <div class="flex justify-between"><span class="text-slate-500">التأثير المالي:</span><span id="confirmFinancial" class="font-semibold"></span></div>
            </div>
            <div class="flex items-center gap-3">
                <button type="button" id="confirmSubmit" class="flex-1 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 transition">تأكيد وإرسال</button>
                <button type="button" id="cancelSubmit" class="flex-1 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold py-2.5 transition">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const startInput = document.getElementById('start_date_time');
    const endInput = document.getElementById('end_date_time');
    const durationValue = document.getElementById('durationValue');
    const transactionType = document.getElementById('transaction_type');
    const transferFields = document.getElementById('transferFields');
    const submitBtn = document.getElementById('submitBtn');
    const saveDraftBtn = document.getElementById('saveDraftBtn');
    const confirmModal = document.getElementById('confirmModal');
    const confirmSubmit = document.getElementById('confirmSubmit');
    const cancelSubmit = document.getElementById('cancelSubmit');
    const modalOverlay = document.getElementById('modalOverlay');

    function calculateDuration() {
        if (startInput.value && endInput.value) {
            const start = new Date(startInput.value);
            const end = new Date(endInput.value);
            if (end >= start) {
                const diffTime = Math.abs(end - start);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                durationValue.textContent = diffDays;
            } else {
                durationValue.textContent = '0';
            }
        } else {
            durationValue.textContent = '0';
        }
    }

    function updateTransferFields() {
        if (transactionType.value === 'transfer') {
            transferFields.classList.remove('hidden');
        } else {
            transferFields.classList.add('hidden');
        }
    }

    startInput.addEventListener('change', calculateDuration);
    endInput.addEventListener('change', calculateDuration);
    transactionType.addEventListener('change', updateTransferFields);

    submitBtn.addEventListener('click', function() {
        if (!document.getElementById('requestForm').checkValidity()) {
            document.getElementById('requestForm').reportValidity();
            return;
        }
        document.getElementById('confirmType').textContent = transactionType.options[transactionType.selectedIndex].text;
        document.getElementById('confirmStart').textContent = startInput.value ? new Date(startInput.value).toLocaleString('ar-SA') : '—';
        document.getElementById('confirmEnd').textContent = endInput.value ? new Date(endInput.value).toLocaleString('ar-SA') : '—';
        document.getElementById('confirmDuration').textContent = durationValue.textContent + ' يوم';
        document.getElementById('confirmFinancial').textContent = document.getElementById('financial_impact').value + ' ل.س';
        confirmModal.classList.remove('hidden');
    });

    confirmSubmit.addEventListener('click', function() {
        confirmModal.classList.add('hidden');
        const form = document.getElementById('requestForm');
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'status';
        input.value = 'pending';
        form.appendChild(input);
        form.submit();
    });

    cancelSubmit.addEventListener('click', function() {
        confirmModal.classList.add('hidden');
    });

    modalOverlay.addEventListener('click', function() {
        confirmModal.classList.add('hidden');
    });

    saveDraftBtn.addEventListener('click', function() {
        const form = document.getElementById('requestForm');
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'status';
        input.value = 'draft';
        form.appendChild(input);
        form.submit();
    });

    updateTransferFields();
    calculateDuration();
});
</script>
@endsection
