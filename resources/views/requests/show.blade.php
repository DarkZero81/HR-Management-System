@extends('layouts.app')

@section('title', 'تفاصيل الطلب #' . $transaction->id)

@section('content')
    @php
        $isAdmin =
            auth()->user()?->role && in_array(strtolower(auth()->user()->role->role_name), ['admin', 'manager']);
        $isPending = $transaction->status === 'pending';
        $basePath = $isAdmin ? '/requests' : '/my/requests';
        $csvUrl = url(
            $basePath .
                '/export-csv?' .
                http_build_query(request()->only(['status', 'transaction_type', 'from_date', 'to_date', 'search'])),
        );

        $statusConfig = match ($transaction->status) {
            'pending' => [
                'bg' => 'bg-amber-100',
                'text' => 'text-amber-700',
                'border' => 'border-amber-200',
                'label' => 'معلقة',
            ],
            'approved' => [
                'bg' => 'bg-emerald-100',
                'text' => 'text-emerald-700',
                'border' => 'border-emerald-200',
                'label' => 'موافق عليها',
            ],
            'rejected' => [
                'bg' => 'bg-rose-100',
                'text' => 'text-rose-700',
                'border' => 'border-rose-200',
                'label' => 'مرفوضة',
            ],
            default => [
                'bg' => 'bg-slate-100',
                'text' => 'text-slate-700',
                'border' => 'border-slate-200',
                'label' => $transaction->status,
            ],
        };

        $typeLabels = match ($transaction->transaction_type) {
            'leave' => 'إجازة',
            'permission' => 'إذن',
            'promotion' => 'ترقية',
            'penalty' => 'عقوبة',
            'transfer' => 'نقل',
            default => $transaction->transaction_type,
        };
        $typeIcons = match ($transaction->transaction_type) {
            'leave' => 'calendar-days',
            'permission' => 'clock',
            'promotion' => 'trending-up',
            'penalty' => 'alert-triangle',
            'transfer' => 'truck',
            default => 'file-text',
        };
        $days =
            \Carbon\Carbon::parse($transaction->start_date_time)->diffInDays(
                \Carbon\Carbon::parse($transaction->end_date_time),
            ) + 1;
    @endphp

    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs font-black uppercase tracking-[0.35em] text-slate-400">تفاصيل الطلب</p>
                <h1 class="text-3xl font-bold text-slate-800">طلب #{{ $transaction->id }}</h1>
                <p class="text-sm text-slate-400 mt-1">عرض كافة التفاصيل والمعلومات الخاصة بالطلب.</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ url($basePath) }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-white rounded-xl font-medium transition-all border border-white/10">
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    <span class="hidden sm:inline">العودة للقائمة</span>
                </a>
                <a href="{{ $pdfUrl }}"
                    class="rounded-2xl bg-blue-600 px-5 py-3 text-l p-3 font-semibold text-white transition hover:bg-blue-700 inline-flex items-center gap-2">
                    <i data-lucide="download" class="w-5 h-5"></i>
                    تصدير PDF
                </a>
                @if ($isAdmin || $isManager)
                    <a href="{{ $csvUrl }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-semibold shadow-lg transition-all">
                        <i data-lucide="download" class="w-4 h-4"></i>
                        <span class="hidden sm:inline">تصدير CSV</span>
                    </a>
                @endif
            </div>
        </div>

        {{-- Alerts --}}
        @if (session('success'))
            <div
                class="rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-4 py-3 text-sm font-semibold text-emerald-200 flex items-center gap-2">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div
                class="rounded-2xl border border-rose-400/20 bg-rose-500/10 px-4 py-3 text-sm font-semibold text-rose-200 flex items-center gap-2">
                <i data-lucide="alert-circle" class="w-5 h-5"></i>
                {{ session('error') }}
            </div>
        @endif

        <div id="statusNotification"
            class="hidden fixed top-4 left-4 right-4 md:left-auto md:right-4 md:w-96 z-50 rounded-2xl border border-blue-400/20 bg-blue-500/10 px-4 py-3 text-sm font-semibold text-blue-200 shadow-xl backdrop-blur-sm">
            <div class="flex items-center gap-2">
                <i data-lucide="refresh-cw" class="w-5 h-5"></i>
                <span id="notificationText">تم تحديث حالة طلب</span>
            </div>
        </div>

        {{-- Actions --}}
        @if ($isPending && $isAdmin)
            <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center">
                        <i data-lucide="git-pull-request" class="w-5 h-5 text-amber-600"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-slate-800">إجراءات الطلب</h2>
                        <p class="text-sm text-slate-500">يمكنك اعتماد أو رفض هذا الطلب. سيتم تطبيق التأثيرات تلقائياً.</p>
                    </div>
                </div>
                <form action="{{ url($basePath . '/' . $transaction->id . '/status') }}" method="POST"
                    class="flex flex-wrap items-center gap-3">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="approved" id="approveStatus">
                    <button type="button"
                        onclick="document.getElementById('approveStatus').value='approved'; this.form.submit();"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-semibold shadow-lg transition-all">
                        <i data-lucide="check-circle" class="w-4 h-4"></i>
                        قبول الطلب
                    </button>
                    <button type="button"
                        onclick="document.getElementById('approveStatus').value='rejected'; this.form.submit();"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-semibold shadow-lg transition-all">
                        <i data-lucide="x-circle" class="w-4 h-4"></i>
                        رفض الطلب
                    </button>
                </form>
            </div>
        @endif

        @if ($isPending && !$isAdmin && $transaction->employee_id === auth()->user()?->employee?->id)
            <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center">
                        <i data-lucide="alert-circle" class="w-5 h-5 text-amber-600"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-slate-800">إجراءات الطلب</h2>
                        <p class="text-sm text-slate-500">يمكنك إلغاء هذا الطلب قبل اعتماده.</p>
                    </div>
                </div>
                <form action="{{ url($basePath . '/' . $transaction->id) }}" method="POST"
                    onsubmit="return confirm('هل أنت متأكد من إلغاء هذا الطلب؟')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-200 hover:bg-red-100 text-slate-600 hover:text-red-600 rounded-xl font-semibold transition-all">
                        <i data-lucide="x" class="w-4 h-4"></i>
                        إلغاء الطلب
                    </button>
                </form>
            </div>
        @endif

        {{-- Main Content --}}
        <div class="grid grid-cols-1 lg:grid-cols-1 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                            <i data-lucide="{{ $typeIcons }}" class="w-5 h-5 text-blue-600"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-slate-800">معلومات الطلب</h2>
                            <p class="text-sm text-slate-500">{{ $typeLabels }} - {{ $statusConfig['label'] }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                            <label class="block text-xs font-bold text-slate-500 mb-2">تاريخ البداية</label>
                            <div class="flex items-center gap-2 text-slate-800">
                                <i data-lucide="calendar" class="w-4 h-4 text-slate-400"></i>
                                {{ \Carbon\Carbon::parse($transaction->start_date_time)->format('Y-m-d H:i') }}
                            </div>
                        </div>
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                            <label class="block text-xs font-bold text-slate-500 mb-2">تاريخ النهاية</label>
                            <div class="flex items-center gap-2 text-slate-800">
                                <i data-lucide="calendar-check" class="w-4 h-4 text-slate-400"></i>
                                {{ \Carbon\Carbon::parse($transaction->end_date_time)->format('Y-m-d H:i') }}
                            </div>
                        </div>
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                            <label class="block text-xs font-bold text-slate-500 mb-2">مدة الطلب</label>
                            <div class="flex items-center gap-2 text-slate-800">
                                <i data-lucide="clock" class="w-4 h-4 text-slate-400"></i>
                                {{ (int) $days }} يوم
                            </div>
                        </div>
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                            <label class="block text-xs font-bold text-slate-500 mb-2">التأثير المالي</label>
                            <div class="flex items-center gap-2 text-slate-800">
                                <i data-lucide="banknote" class="w-4 h-4 text-slate-400"></i>
                                {{ number_format($transaction->financial_impact, 2) }} ل.س
                            </div>
                        </div>
                    </div>
                    <div class="mt-6">
                        <label class="block text-xs font-bold text-slate-500 mb-2">ملاحظات الطلب</label>
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-700">
                            {{ $transaction->description ?? 'لا توجد ملاحظات.' }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6" data-current-status="{{ $transaction->status }}">
                <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
                    <h2 class="text-xl font-bold text-slate-800 mb-4">مقدم الطلب</h2>
                    <div class="flex items-center gap-3 mb-4">
                        <div
                            class="w-12 h-12 rounded-full bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center text-white font-bold text-lg">
                            {{ strtoupper(substr($transaction->employee?->first_name ?? 'U', 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-bold text-slate-800">{{ $transaction->employee?->full_name ?? '—' }}</p>
                            <p class="text-xs text-slate-500">{{ $transaction->employee?->email ?? '' }}</p>
                        </div>
                    </div>
                    <div class="space-y-3 text-sm">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">القسم</span>
                            <span
                                class="font-semibold text-slate-800">{{ $transaction->employee?->department?->name ?? 'غير معين' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">الوردية</span>
                            <span
                                class="font-semibold text-slate-800">{{ $transaction->employee?->shift?->shift_name ?? 'بدون وردية' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">تاريخ التعيين</span>
                            <span
                                class="font-semibold text-slate-800">{{ $transaction->employee?->join_date?->format('Y-m-d') ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                @if ($transaction->approver)
                    <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
                        <h2 class="text-xl font-bold text-slate-800 mb-4">معلومات المراجعة</h2>
                        <div class="flex items-center gap-3 mb-4">
                            <div
                                class="w-12 h-12 rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white font-bold text-lg">
                                {{ strtoupper(substr($transaction->approver->name ?? 'A', 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-bold text-slate-800">{{ $transaction->approver->name ?? '—' }}</p>
                                <p class="text-xs text-slate-500">{{ $transaction->approver->email ?? '' }}</p>
                            </div>
                        </div>
                        <div class="space-y-3 text-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">الحالة النهائية</span>
                                <span
                                    class="font-semibold {{ match ($transaction->status) {'approved' => 'text-emerald-600','rejected' => 'text-rose-600',default => 'text-slate-800'} }}">
                                    {{ $statusConfig['label'] }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($timeline->isNotEmpty())
                    <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
                        <h2 class="text-xl font-bold text-slate-800 mb-4">خط زمني للطلب</h2>
                        <div class="space-y-6">
                            @foreach ($timeline as $item)
                                <div class="flex items-start gap-4">
                                    <div class="flex flex-col items-center">
                                        <div
                                            class="w-10 h-10 rounded-full flex items-center justify-center {{ match ($item['color']) {'blue' => 'bg-blue-100 text-blue-600','emerald' => 'bg-emerald-100 text-emerald-600','rose' => 'bg-rose-100 text-rose-600',default => 'bg-slate-100 text-slate-600'} }}">
                                            <i data-lucide="{{ $item['icon'] }}" class="w-5 h-5"></i>
                                        </div>
                                        @if (!$loop->last)
                                            <div class="w-px h-8 bg-slate-200 mt-2"></div>
                                        @endif
                                    </div>
                                    <div class="flex-1 pt-1">
                                        <p class="text-sm font-semibold text-slate-800">{{ $item['label'] }}</p>
                                        <p class="text-xs text-slate-500 mt-1">{{ $item['description'] }}</p>
                                        <p class="text-xs text-slate-400 mt-1">
                                            {{ $item['date']?->format('Y-m-d H:i') ?? '—' }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($previousRequests->isNotEmpty())
                    <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
                        <h2 class="text-xl font-bold text-slate-800 mb-4">طلبات سابقة</h2>
                        <div class="space-y-3">
                            @foreach ($previousRequests as $prev)
                                <a href="{{ url($basePath . '/' . $prev->id) }}"
                                    class="flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 p-4 hover:bg-slate-100 transition">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-800">طلب #{{ $prev->id }}</p>
                                        <p class="text-xs text-slate-500">
                                            {{ match ($prev->transaction_type) {'leave' => 'إجازة','permission' => 'إذن','promotion' => 'ترقية','penalty' => 'عقوبة','transfer' => 'نقل',default => $prev->transaction_type} }}
                                        </p>
                                    </div>
                                    <span
                                        class="text-xs font-semibold {{ match ($prev->status) {'pending' => 'text-amber-600','approved' => 'text-emerald-600','rejected' => 'text-rose-600',default => 'text-slate-600'} }}">
                                        {{ match ($prev->status) {'pending' => 'معلقة','approved' => 'موافق عليها','rejected' => 'مرفوضة',default => $prev->status} }}
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
                    <h2 class="text-xl font-bold text-slate-800 mb-4">مشاركة الطلب</h2>
                    <div class="flex items-center gap-3">
                        <input type="text" readonly value="{{ $shareUrl }}"
                            class="flex-1 rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-700">
                        <button type="button"
                            onclick="navigator.clipboard.writeText('{{ $shareUrl }}').then(() => alert('تم نسخ الرابط'))"
                            class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-white rounded-xl text-sm font-semibold">
                            نسخ
                        </button>
                    </div>
                </div>

                @if ($editHistory->isNotEmpty())
                    <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
                        <h2 class="text-xl font-bold text-slate-800 mb-4">سجل التعديلات</h2>
                        <div class="space-y-4">
                            @foreach ($editHistory as $log)
                                <div class="flex items-start gap-3">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center flex-shrink-0">
                                        <i data-lucide="file-text" class="w-4 h-4 text-slate-600"></i>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-semibold text-slate-800">
                                                {{ $log->performed_at?->format('Y-m-d H:i') ?? '—' }}
                                            </p>
                                        </div>
                                        <p class="text-xs text-slate-500 mt-1">
                                            تم التعديل على الطلب
                                            @if ($log->user)
                                                بواسطة {{ $log->user->name ?? 'النظام' }}
                                            @endif
                                        </p>
                                        @if ($log->new_values)
                                            <div class="mt-2 rounded-lg bg-slate-50 p-3 text-xs text-slate-600">
                                                @foreach ($log->new_values as $key => $value)
                                                    <div class="flex justify-between">
                                                        <span class="font-medium">{{ $key }}</span>
                                                        <span>{{ is_array($value) ? json_encode($value) : $value }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
                    <h2 class="text-xl font-bold text-slate-800 mb-4">معلومات النظام</h2>
                    <div class="space-y-3 text-sm">
                        <div class="flex items-center justify-between my-4">
                            <span class="text-slate-500">تاريخ الإنشاء</span>
                            <span
                                class="inline-flex items-center gap-1 rounded-lg bg-white px-4 py-1.5 border border-slate-200">
                                <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                                {{ \Carbon\Carbon::parse($transaction->created_at)->format('Y-m-d H:i') }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between my-4">
                            <span class="text-slate-500">آخر تحديث</span>
                            <span
                                class="inline-flex items-center gap-1 rounded-lg bg-white px-4 py-1.5 border border-slate-200">
                                <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                                {{ \Carbon\Carbon::parse($transaction->updated_at)->format('Y-m-d H:i') }}
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between my-4">
                        <span class="text-slate-500">تاريخ الإنشاء</span>
                        <span
                            class="inline-flex items-center gap-1 rounded-lg bg-white px-6 py-1.5 border border-slate-200">
                            <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                            {{ \Carbon\Carbon::parse($transaction->start_date_time)->format('Y-m-d') }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between my-4">
                        <span class="text-slate-500">آخر تحديث</span>
                        <span
                            class="inline-flex items-center gap-1 rounded-lg bg-white px-4 py-1.5 border border-slate-200">
                            <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                            {{ (int) $days }} يوم
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function downloadPdf() {
            const url = '{{ $pdfUrl }}';
            if (url === '#') return;
            window.location.href = url;
        }

        setInterval(function() {
            fetch('{{ url($basePath . '/' . $transaction->id) }}', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(r => r.text())
                .then(html => {
                    const temp = document.createElement('div');
                    temp.innerHTML = html;
                    const newStatus = temp.querySelector('[data-current-status]')?.dataset?.currentStatus;
                    const currentStatus = document.querySelector('[data-current-status]')?.dataset
                        ?.currentStatus;
                    if (newStatus && currentStatus && newStatus !== currentStatus) {
                        const notif = document.getElementById('statusNotification');
                        const notifText = document.getElementById('notificationText');
                        const statusLabel = newStatus === 'approved' ? 'موافق عليها' : newStatus ===
                            'rejected' ? 'مرفوضة' : newStatus;
                        notifText.textContent = 'تم تحديث حالة الطلب إلى: ' + statusLabel;
                        notif.classList.remove('hidden');
                        setTimeout(() => notif.classList.add('hidden'), 5000);
                    }
                })
                .catch(() => {});
        }, 10000);
    </script>
    </div>
@endsection
