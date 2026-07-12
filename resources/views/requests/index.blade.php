@extends('layouts.app')

@section('title', 'الإجازات والطلبات')

@section('content')
<div class="space-y-6 mb-4">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-xs font-black uppercase tracking-[0.35em] text-slate-400">الإجازات والطلبات</p>
            <h1 class="text-3xl font-bold text-slate-800">الإجازات والطلبات</h1>
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
            @if($isAdmin || $isManager)
                <a href="{{ route($exportRoute) . '?' . http_build_query(request()->only(['status', 'transaction_type', 'from_date', 'to_date', 'search'])) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-semibold shadow-lg transition-all">
                    <i data-lucide="download" class="w-4 h-4"></i>
                    <span class="hidden sm:inline">تصدير CSV</span>
                </a>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-4 py-3 text-sm font-semibold text-emerald-200 flex items-center gap-2">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-2xl border border-rose-400/20 bg-rose-500/10 px-4 py-3 text-sm font-semibold text-rose-200 flex items-center gap-2">
            <i data-lucide="alert-circle" class="w-5 h-5"></i>
            {{ session('error') }}
        </div>
    @endif

    <div id="statusNotification" class="hidden fixed top-4 left-4 right-4 md:left-auto md:right-4 md:w-96 z-50 rounded-2xl border border-blue-400/20 bg-blue-500/10 px-4 py-3 text-sm font-semibold text-blue-200 shadow-xl backdrop-blur-sm">
        <div class="flex items-center gap-2">
            <i data-lucide="refresh-cw" class="w-5 h-5"></i>
            <span id="notificationText">تم تحديث حالة طلب</span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="employee-form-card rounded-2xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                    <i data-lucide="file-text" class="w-5 h-5 text-blue-600"></i>
                </div>
                <div class="flex-1">
                    <p class="text-2xl font-black text-slate-800">{{ $stats['total'] }}</p>
                    <p class="text-xs text-slate-500">إجمالي الطلبات</p>
                    @php $totalPercent = $stats['total'] > 0 ? 100 : 0; @endphp
                    <div class="mt-2 h-1.5 bg-slate-200 rounded-full overflow-hidden">
                        <div class="h-full bg-blue-500 rounded-full transition-all duration-1000" style="width: {{ $totalPercent }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="employee-form-card rounded-2xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center">
                    <i data-lucide="clock" class="w-5 h-5 text-amber-600"></i>
                </div>
                <div class="flex-1">
                    <p class="text-2xl font-black text-slate-800">{{ $stats['pending'] }}</p>
                    <p class="text-xs text-slate-500">معلقة</p>
                    @php $pendingPercent = $stats['total'] > 0 ? round(($stats['pending'] / $stats['total']) * 100) : 0; @endphp
                    <div class="mt-2 h-1.5 bg-slate-200 rounded-full overflow-hidden">
                        <div class="h-full bg-amber-500 rounded-full transition-all duration-1000" style="width: {{ $pendingPercent }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="employee-form-card rounded-2xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                    <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i>
                </div>
                <div class="flex-1">
                    <p class="text-2xl font-black text-slate-800">{{ $stats['approved'] }}</p>
                    <p class="text-xs text-slate-500">موافق عليها</p>
                    @php $approvedPercent = $stats['total'] > 0 ? round(($stats['approved'] / $stats['total']) * 100) : 0; @endphp
                    <div class="mt-2 h-1.5 bg-slate-200 rounded-full overflow-hidden">
                        <div class="h-full bg-emerald-500 rounded-full transition-all duration-1000" style="width: {{ $approvedPercent }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="employee-form-card rounded-2xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-rose-100 flex items-center justify-center">
                    <i data-lucide="x-circle" class="w-5 h-5 text-rose-600"></i>
                </div>
                <div class="flex-1">
                    <p class="text-2xl font-black text-slate-800">{{ $stats['rejected'] }}</p>
                    <p class="text-xs text-slate-500">مرفوضة</p>
                    @php $rejectedPercent = $stats['total'] > 0 ? round(($stats['rejected'] / $stats['total']) * 100) : 0; @endphp
                    <div class="mt-2 h-1.5 bg-slate-200 rounded-full overflow-hidden">
                        <div class="h-full bg-rose-500 rounded-full transition-all duration-1000" style="width: {{ $rejectedPercent }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-4 md:p-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-4 gap-3">
                <div class="flex items-center gap-3">
                    <h2 class="text-xl font-bold text-slate-800">سجل الطلبات</h2>
                    <span class="text-xs font-bold text-slate-500 bg-slate-100 px-4 py-1 rounded-full">{{ $stats['total'] }} طلب</span>
                </div>
                <div class="flex items-center gap-2">
                    <button id="listViewBtn" class="inline-flex items-center gap-2 px-3 py-2 bg-slate-800 text-slate-500 rounded-xl text-sm font-semibold transition-all">
                        <i data-lucide="list" class="w-4 h-4"></i>
                        <span class="hidden sm:inline">قائمة</span>
                    </button>
                    <button id="calendarViewBtn" class="inline-flex items-center gap-2 px-3 py-2 bg-slate-200 text-slate-500 rounded-xl text-sm font-semibold transition-all hover:bg-slate-300">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                        <span class="hidden sm:inline">تقويم</span>
                    </button>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2 mb-4">
                <a href="{{ route('my.requests.index', array_merge(request()->query(), ['status' => ''])) }}" class="inline-flex items-center gap-2 px-3 py-2 md:px-4 md:py-2.5 rounded-xl text-xs md:text-sm font-semibold transition-all {{ !($filters['status'] ?? '') ? 'bg-slate-800 text-white' : 'bg-slate-200 text-slate-700 hover:bg-slate-300' }}">
                    الكل
                </a>
                <a href="{{ route('my.requests.index', array_merge(request()->query(), ['status' => 'pending'])) }}" class="inline-flex items-center gap-2 px-3 py-2 md:px-4 md:py-2.5 rounded-xl text-xs md:text-sm font-semibold transition-all {{ ($filters['status'] ?? '') === 'pending' ? 'bg-amber-500 text-white' : 'bg-amber-100 text-amber-700 hover:bg-amber-200' }}">
                    <i data-lucide="clock" class="w-3.5 h-3.5 md:w-4 md:h-4"></i>
                    <span class="hidden sm:inline">معلقة</span>
                </a>
                <a href="{{ route('my.requests.index', array_merge(request()->query(), ['status' => 'approved'])) }}" class="inline-flex items-center gap-2 px-3 py-2 md:px-4 md:py-2.5 rounded-xl text-xs md:text-sm font-semibold transition-all {{ ($filters['status'] ?? '') === 'approved' ? 'bg-emerald-500 text-white' : 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' }}">
                    <i data-lucide="check-circle" class="w-3.5 h-3.5 md:w-4 md:h-4"></i>
                    <span class="hidden sm:inline">موافق عليها</span>
                </a>
                <a href="{{ route('my.requests.index', array_merge(request()->query(), ['status' => 'rejected'])) }}" class="inline-flex items-center gap-2 px-3 py-2 md:px-4 md:py-2.5 rounded-xl text-xs md:text-sm font-semibold transition-all {{ ($filters['status'] ?? '') === 'rejected' ? 'bg-rose-500 text-white' : 'bg-rose-100 text-rose-700 hover:bg-rose-200' }}">
                    <i data-lucide="x-circle" class="w-3.5 h-3.5 md:w-4 md:h-4"></i>
                    <span class="hidden sm:inline">مرفوضة</span>
                </a>
            </div>

        <form method="GET" action="{{ route('my.requests.index') }}" class="mb-6" id="filterForm">
            <div class="employee-form-card rounded-2xl border border-slate-200 p-4 md:p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i data-lucide="filter" class="w-5 h-5 text-cyan-600"></i>
                        <span class="text-sm font-bold text-slate-700">فلترة الطلبات</span>
                    </div>
                    <span class="text-xs text-slate-400">استخدم الفلاتر لتضييق البحث</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4">
                    <div class="relative">
                        <label class="block text-xs font-bold text-slate-600 mb-1.5">بحث</label>
                        <i data-lucide="search" class="absolute right-3 top-9 h-4 w-4 text-slate-400 pointer-events-none"></i>
                        <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="ابحث عن طلب..." class="employee-form-input w-full rounded-xl border border-slate-200 bg-slate-50 pr-10 pl-4 py-2.5 text-sm focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1.5">الحالة</label>
                        <div class="relative">
                            <i data-lucide="chevron-down" class="absolute left-3 top-2.5 h-4 w-4 text-slate-400 pointer-events-none"></i>
                            <select name="status" class="employee-form-input w-full rounded-xl border border-slate-200 bg-slate-50 pl-10 pr-8 py-2.5 text-sm focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all appearance-none">
                                <option value="">الكل</option>
                                <option value="pending" {{ ($filters['status'] ?? '') === 'pending' ? 'selected' : '' }}>معلقة</option>
                                <option value="approved" {{ ($filters['status'] ?? '') === 'approved' ? 'selected' : '' }}>موافق عليها</option>
                                <option value="rejected" {{ ($filters['status'] ?? '') === 'rejected' ? 'selected' : '' }}>مرفوضة</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1.5">من تاريخ</label>
                        <div class="relative">
                            <input type="date" name="from_date" value="{{ $filters['from_date'] ?? '' }}" class="employee-form-input w-full rounded-xl border border-slate-200 bg-slate-50 pr-2 pl-4 py-2.5 text-sm focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1.5">إلى تاريخ</label>
                        <div class="relative">
                            <input type="date" name="to_date" value="{{ $filters['to_date'] ?? '' }}" class="employee-form-input w-full rounded-xl border border-slate-200 bg-slate-50 pr-2 pl-4 py-2.5 text-sm focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all">
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2 pt-1">
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-sm font-semibold transition-all shadow-sm">
                        <i data-lucide="filter" class="w-4 h-4"></i>
                        تطبيق
                    </button>
                    <a href="{{ route('my.requests.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-sm font-semibold transition-all">
                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                        إعادة تعيين
                    </a>
                </div>
            </div>
            <input type="hidden" name="sort_by" value="{{ request('sort_by') }}">
            <input type="hidden" name="sort_direction" value="{{ request('sort_direction', 'desc') }}">
        </form>

        <div id="listView">
            <div class="overflow-x-auto">
                <div class="inline-block min-w-full align-middle">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-4 text-slate-600 text-right font-medium w-12">#</th>
                                <th class="px-4 py-4 text-slate-600 text-right font-medium min-w-[160px]">الموظف</th>
                                <th class="px-4 py-4 text-slate-600 text-right font-medium">النوع</th>
                                <th class="px-4 py-4 text-slate-600 text-right font-medium">الحالة</th>
                                <th class="px-4 py-4 text-slate-600 text-right font-medium cursor-pointer hover:text-cyan-600 transition-colors" onclick="sortBy('start_date_time')">
                                    <div class="flex items-center gap-1">
                                        من تاريخ
                                        @if(request('sort_by') === 'start_date_time')
                                            <i data-lucide="{{ request('sort_direction') === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="w-3.5 h-3.5"></i>
                                        @endif
                                    </div>
                                </th>
                                <th class="px-4 py-4 text-slate-600 text-right font-medium cursor-pointer hover:text-cyan-600 transition-colors" onclick="sortBy('end_date_time')">
                                    <div class="flex items-center gap-1">
                                        إلى تاريخ
                                        @if(request('sort_by') === 'end_date_time')
                                            <i data-lucide="{{ request('sort_direction') === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="w-3.5 h-3.5"></i>
                                        @endif
                                    </div>
                                </th>
                                <th class="px-4 py-4 text-slate-600 text-center font-medium w-24">المدة</th>
                                <th class="px-4 py-4 text-slate-600 text-center font-medium w-28">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($transactions as $transaction)
                                @php
                                    $statusColors = match($transaction->status) {
                                        'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
                                        'approved' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                        'rejected' => 'bg-rose-100 text-rose-700 border-rose-200',
                                        default => 'bg-slate-100 text-slate-700 border-slate-200',
                                    };
                                    $typeConfig = match($transaction->transaction_type) {
                                        'leave' => ['label' => 'إجازة', 'icon' => 'calendar-days', 'bg' => 'bg-sky-100', 'text' => 'text-sky-700'],
                                        'permission' => ['label' => 'إذن', 'icon' => 'clock', 'bg' => 'bg-violet-100', 'text' => 'text-violet-700'],
                                        'promotion' => ['label' => 'ترقية', 'icon' => 'trending-up', 'bg' => 'bg-emerald-100', 'text' => 'text-emerald-700'],
                                        'penalty' => ['label' => 'عقوبة', 'icon' => 'alert-triangle', 'bg' => 'bg-rose-100', 'text' => 'text-rose-700'],
                                        'transfer' => ['label' => 'نقل', 'icon' => 'truck', 'bg' => 'bg-amber-100', 'text' => 'text-amber-700'],
                                        default => ['label' => $transaction->transaction_type, 'icon' => 'file-text', 'bg' => 'bg-slate-100', 'text' => 'text-slate-700'],
                                    };
                                    $days = \Carbon\Carbon::parse($transaction->start_date_time)->diffInDays(\Carbon\Carbon::parse($transaction->end_date_time)) + 1;
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition-colors cursor-pointer" data-transaction-id="{{ $transaction->id }}" onclick="openRequestModal({{ $transaction->id }})">
                                    <td class="px-4 py-4 text-sm text-slate-500">{{ $transaction->id }}</td>
                                    <td class="px-4 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center text-white font-bold text-xs shadow-sm flex-shrink-0">
                                                {{ strtoupper(substr($transaction->employee?->first_name ?? 'U', 0, 1)) }}
                                            </div>
                                            <span class="font-semibold text-slate-800 text-sm">{{ $transaction->employee?->full_name ?? '—' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 rounded-lg {{ $typeConfig['bg'] }} flex items-center justify-center flex-shrink-0">
                                                <i data-lucide="{{ $typeConfig['icon'] }}" class="w-4 h-4 {{ $typeConfig['text'] }}"></i>
                                            </div>
                                            <span class="font-semibold text-slate-800 text-sm">{{ $typeConfig['label'] }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="inline-flex items-center rounded-full border px-4 py-0.5 text-xs font-bold {{ $statusColors }}">
                                            {{ match($transaction->status) { 'pending' => 'معلقة', 'approved' => 'موافق عليها', 'rejected' => 'مرفوضة', default => $transaction->status } }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-slate-600">{{ \Carbon\Carbon::parse($transaction->start_date_time)->format('Y-m-d') }}</td>
                                    <td class="px-4 py-4 text-sm text-slate-600">{{ \Carbon\Carbon::parse($transaction->end_date_time)->format('Y-m-d') }}</td>
                                    <td class="px-4 py-4 text-center text-sm font-semibold text-slate-700">{{ (int) $days }} <span class="text-xs text-slate-400">يوم</span></td>
                                    <td class="px-4 py-4">
                                    <div class="flex items-center justify-center gap-1">
                                        <button type="button" onclick="openRequestModal({{ $transaction->id }})" class="p-2 rounded-lg bg-slate-100 hover:bg-blue-100 text-slate-600 hover:text-blue-600 transition-all" title="عرض">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </button>
                                        @if($isAdmin || $isManager)
                                            @if($transaction->status === 'pending')
                                                <form action="{{ url($basePath . '/' . $transaction->id . '/status') }}" method="POST" class="inline" onclick="event.stopPropagation()" onsubmit="return confirm('هل أنت متأكد من قبول هذا الطلب؟')">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="approved">
                                                    <button type="submit" class="p-2 rounded-lg bg-slate-100 hover:bg-emerald-100 text-slate-600 hover:text-emerald-600 transition-all" title="قبول">
                                                        <i data-lucide="check" class="w-4 h-4"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ url($basePath . '/' . $transaction->id . '/status') }}" method="POST" class="inline" onclick="event.stopPropagation()" onsubmit="return confirm('هل أنت متأكد من رفض هذا الطلب؟')">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="rejected">
                                                    <button type="submit" class="p-2 rounded-lg bg-slate-100 hover:bg-rose-100 text-slate-600 hover:text-rose-600 transition-all" title="رفض">
                                                        <i data-lucide="x" class="w-4 h-4"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                        @if($transaction->status === 'pending' && !$isAdmin && !$isManager && $transaction->employee_id === auth()->user()?->employee?->id)
                                            <form action="{{ route('my.requests.destroy', $transaction) }}" method="POST" class="inline" onclick="event.stopPropagation()" onsubmit="return confirm('هل أنت متأكد من إلغاء هذا الطلب؟')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 rounded-lg bg-slate-100 hover:bg-red-100 text-slate-600 hover:text-red-600 transition-all" title="إلغاء">
                                                    <i data-lucide="x" class="w-4 h-4"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                    </td>
                                </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center">
                                            <i data-lucide="inbox" class="w-8 h-8 text-slate-400"></i>
                                        </div>
                                        <p class="text-sm font-semibold text-slate-500">لا توجد طلبات حالياً</p>
                                        <p class="text-xs text-slate-400">ابدأ بإنشاء طلبك الأول وسيظهر هنا</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div id="calendarView" class="hidden">
            <div id="requestsCalendar" style="min-height: 500px;"></div>
        </div>

        <div class="border-t border-slate-100 bg-slate-50 px-6 py-4 flex items-center justify-between">
            <p class="text-xs text-slate-500">يتم تحديث القائمة تلقائياً كل 30 ثانية</p>
            {{ $transactions->links() }}
        </div>
        </div>
    </div>

    <!-- Request Detail Modal -->
    <div id="requestModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity opacity-0" id="modalBackdrop"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div class="relative transform overflow-hidden rounded-[28px] border border-slate-200 bg-white px-4 py-5 text-right shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl opacity-0 scale-95 dark:bg-slate-900 dark:border-slate-700" id="modalPanel">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-5">
                        <div>
                            <h3 class="text-xl font-bold text-slate-900" id="modalTitle">تفاصيل الطلب</h3>
                            <p class="text-xs text-slate-500 mt-1">طلب رقم <span id="modalRequestId" class="font-bold text-slate-700"></span></p>
                        </div>
                        <button type="button" onclick="closeRequestModal()" class="rounded-xl bg-slate-100 p-2 text-slate-500 hover:bg-slate-200 hover:text-slate-700 transition-all">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div class="rounded-2xl border border-slate-100 bg-slate-50/80 p-4">
                            <p class="text-xs font-bold text-slate-500 mb-1">نوع الطلب</p>
                            <p class="text-sm font-semibold text-slate-800" id="modalType">—</p>
                        </div>
                        <div class="rounded-2xl border border-slate-100 bg-slate-50/80 p-4">
                            <p class="text-xs font-bold text-slate-500 mb-1">الحالة</p>
                            <span class="inline-flex items-center rounded-full border px-3 py-0.5 text-xs font-bold" id="modalStatus">—</span>
                        </div>
                        <div class="rounded-2xl border border-slate-100 bg-slate-50/80 p-4">
                            <p class="text-xs font-bold text-slate-500 mb-1">من تاريخ</p>
                            <p class="text-sm font-semibold text-slate-800" id="modalStart">—</p>
                        </div>
                        <div class="rounded-2xl border border-slate-100 bg-slate-50/80 p-4">
                            <p class="text-xs font-bold text-slate-500 mb-1">إلى تاريخ</p>
                            <p class="text-sm font-semibold text-slate-800" id="modalEnd">—</p>
                        </div>
                        <div class="rounded-2xl border border-slate-100 bg-slate-50/80 p-4">
                            <p class="text-xs font-bold text-slate-500 mb-1">المدة</p>
                            <p class="text-sm font-semibold text-slate-800" id="modalDuration">—</p>
                        </div>
                        <div class="rounded-2xl border border-slate-100 bg-slate-50/80 p-4">
                            <p class="text-xs font-bold text-slate-500 mb-1">التأثير المالي</p>
                            <p class="text-sm font-semibold text-slate-800" id="modalFinancial">—</p>
                        </div>
                    </div>
                    <div class="mt-5 rounded-2xl border border-slate-100 bg-slate-50/80 p-4">
                        <p class="text-xs font-bold text-slate-500 mb-1">الوصف / الملاحظات</p>
                        <p class="text-sm text-slate-800 leading-relaxed" id="modalDescription">—</p>
                    </div>
                    <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="rounded-2xl border border-slate-100 bg-slate-50/80 p-4">
                            <p class="text-xs font-bold text-slate-500 mb-2">مقدم الطلب</p>
                            <p class="text-sm font-semibold text-slate-800" id="modalEmployee">—</p>
                            <p class="text-xs text-slate-500 mt-1" id="modalEmployeeEmail"></p>
                            <p class="text-xs text-slate-500" id="modalEmployeeDepartment"></p>
                        </div>
                        <div class="rounded-2xl border border-slate-100 bg-slate-50/80 p-4">
                            <p class="text-xs font-bold text-slate-500 mb-2">المراجع</p>
                            <p class="text-sm font-semibold text-slate-800" id="modalApprover">—</p>
                            <p class="text-xs text-slate-500 mt-1" id="modalApproverEmail"></p>
                        </div>
                    </div>
                    <div class="mt-5 rounded-2xl border border-slate-100 bg-slate-50/80 p-4">
                        <p class="text-xs font-bold text-slate-500 mb-2">معلومات النظام</p>
                        <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                            <div>
                                <p class="text-xs text-slate-500">تاريخ الإنشاء</p>
                                <p class="text-sm font-semibold text-slate-800" id="modalCreatedAt">—</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500">آخر تحديث</p>
                                <p class="text-sm font-semibold text-slate-800" id="modalUpdatedAt">—</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const requestModal = document.getElementById('requestModal');
        const modalBackdrop = document.getElementById('modalBackdrop');
        const modalPanel = document.getElementById('modalPanel');
        const requestTransactions = <?php echo json_encode($transactions->map(function ($t) {
            return [
                'id' => (int) $t->id,
                'type' => (string) $t->transaction_type,
                'status' => (string) $t->status,
                'start' => \Carbon\Carbon::parse($t->start_date_time)->format('Y-m-d H:i'),
                'end' => \Carbon\Carbon::parse($t->end_date_time)->format('Y-m-d H:i'),
                'duration' => (int) (\Carbon\Carbon::parse($t->start_date_time)->diffInDays(\Carbon\Carbon::parse($t->end_date_time)) + 1),
                'financial' => (float) $t->financial_impact,
                'description' => (string) ($t->description ?? 'لا توجد ملاحظات.'),
                'employee_name' => (string) ($t->employee->full_name ?? '—'),
                'employee_email' => (string) ($t->employee->user->email ?? '—'),
                'employee_department' => (string) ($t->employee->department->name ?? 'غير معين'),
                'approver_name' => (string) ($t->approver->name ?? '—'),
                'approver_email' => (string) ($t->approver->email ?? '—'),
                'created_at' => $t->created_at?->format('Y-m-d H:i') ?? '—',
                'updated_at' => $t->updated_at?->format('Y-m-d H:i') ?? '—',
            ];
        })->all(), JSON_UNESCAPED_UNICODE); ?>

        const statusConfig = {
            pending: { label: 'معلقة', class: 'bg-amber-100 text-amber-700 border-amber-200' },
            approved: { label: 'موافق عليها', class: 'bg-emerald-100 text-emerald-700 border-emerald-200' },
            rejected: { label: 'مرفوضة', class: 'bg-rose-100 text-rose-700 border-rose-200' },
        };

        const typeConfig = {
            leave: 'إجازة',
            permission: 'إذن',
            promotion: 'ترقية',
            penalty: 'عقوبة',
            transfer: 'نقل',
        };

        function openRequestModal(requestId) {
            const data = requestTransactions.find(item => String(item.id) === String(requestId));
            if (!data) return;

            document.getElementById('modalRequestId').textContent = data.id;
            document.getElementById('modalType').textContent = typeConfig[data.type] ?? data.type;

            const statusBadge = document.getElementById('modalStatus');
            const status = statusConfig[data.status] ?? { label: data.status, class: 'bg-slate-100 text-slate-700 border-slate-200' };
            statusBadge.textContent = status.label;
            statusBadge.className = 'inline-flex items-center rounded-full border px-3 py-0.5 text-xs font-bold ' + status.class;

            document.getElementById('modalStart').textContent = data.start;
            document.getElementById('modalEnd').textContent = data.end;
            document.getElementById('modalDuration').innerHTML = parseInt(data.duration, 10) + ' <span class="text-xs text-slate-400">يوم</span>';
            document.getElementById('modalFinancial').textContent = Number(data.financial).toFixed(2) + ' ل.س';
            document.getElementById('modalDescription').textContent = data.description;

            document.getElementById('modalEmployee').textContent = data.employee_name;
            document.getElementById('modalEmployeeEmail').textContent = data.employee_email;
            const deptText = data.employee_department === 'غير معين' ? 'غير معين' : 'القسم: ' + data.employee_department;
            document.getElementById('modalEmployeeDepartment').textContent = deptText;

            document.getElementById('modalApprover').textContent = data.approver_name;
            document.getElementById('modalApproverEmail').textContent = data.approver_email;

            document.getElementById('modalCreatedAt').textContent = data.created_at;
            document.getElementById('modalUpdatedAt').textContent = data.updated_at;

            requestModal.classList.remove('hidden');
            requestModal.classList.add('flex');
            document.body.style.overflow = 'hidden';

            requestAnimationFrame(() => {
                modalBackdrop.classList.remove('opacity-0');
                modalPanel.classList.remove('opacity-0', 'scale-95');
                modalPanel.classList.add('opacity-100', 'scale-100');
            });

            if (window.lucide) lucide.createIcons();
        }

        function closeRequestModal() {
            modalBackdrop.classList.add('opacity-0');
            modalPanel.classList.remove('opacity-100', 'scale-100');
            modalPanel.classList.add('opacity-0', 'scale-95');

            setTimeout(() => {
                requestModal.classList.add('hidden');
                requestModal.classList.remove('flex');
                document.body.style.overflow = '';
            }, 200);
        }

        modalBackdrop.addEventListener('click', closeRequestModal);
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && !requestModal.classList.contains('hidden')) {
                closeRequestModal();
            }
        });
    </script>


<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const listViewBtn = document.getElementById('listViewBtn');
    const calendarViewBtn = document.getElementById('calendarViewBtn');
    const listView = document.getElementById('listView');
    const calendarView = document.getElementById('calendarView');
    let calendar = null;

    function updateViewButtons() {
        if (listView.classList.contains('hidden')) {
            listViewBtn.classList.remove('bg-slate-800', 'text-white');
            listViewBtn.classList.add('bg-slate-200', 'text-slate-700');
            calendarViewBtn.classList.remove('bg-slate-200', 'text-slate-700');
            calendarViewBtn.classList.add('bg-slate-800', 'text-white');
        } else {
            calendarViewBtn.classList.remove('bg-slate-800', 'text-white');
            calendarViewBtn.classList.add('bg-slate-200', 'text-slate-700');
            listViewBtn.classList.remove('bg-slate-200', 'text-slate-700');
            listViewBtn.classList.add('bg-slate-800', 'text-white');
        }
    }

    listViewBtn.addEventListener('click', function() {
        listView.classList.remove('hidden');
        calendarView.classList.add('hidden');
        updateViewButtons();
    });

    calendarViewBtn.addEventListener('click', function() {
        listView.classList.add('hidden');
        calendarView.classList.remove('hidden');
        updateViewButtons();

        if (!calendar) {
            const calendarEl = document.getElementById('requestsCalendar');
            const events = <?php echo json_encode($calendarEvents ?? [], JSON_UNESCAPED_UNICODE); ?>;

            calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'ar',
                direction: 'rtl',
                firstDay: 6,
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,listWeek'
                },
                buttonText: {
                    today: 'اليوم',
                    month: 'شهر',
                    week: 'أسبوع',
                    day: 'يوم',
                    list: 'قائمة'
                },
                events: events,
                eventDisplay: 'block',
                eventTextColor: '#ffffff',
                dayMaxEvents: true,
                height: 'auto',
                editable: false
            });

            calendar.render();
        }
    });

    updateViewButtons();

    setInterval(function() {
        if (!listView.classList.contains('hidden')) {
            location.reload();
        }
    }, 30000);

    setInterval(function() {
        fetch('{{ route('my.requests.index') }}', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.text())
        .then(html => {
            const temp = document.createElement('div');
            temp.innerHTML = html;
            const newFirstId = temp.querySelector('tbody tr')?.dataset?.transactionId;
            const currentFirstId = document.querySelector('tbody tr')?.dataset?.transactionId;
            if (newFirstId && currentFirstId && newFirstId !== currentFirstId) {
                const notif = document.getElementById('statusNotification');
                const notifText = document.getElementById('notificationText');
                notifText.textContent = 'تم تحديث حالة طلب';
                notif.classList.remove('hidden');
                setTimeout(() => notif.classList.add('hidden'), 4000);
            }
        })
        .catch(() => {});
    }, 15000);
});

function sortBy(field) {
    const form = document.getElementById('filterForm');
    const currentSort = form.querySelector('input[name="sort_by"]').value;
    const currentDirection = form.querySelector('input[name="sort_direction"]').value;

    if (currentSort === field) {
        form.querySelector('input[name="sort_direction"]').value = currentDirection === 'asc' ? 'desc' : 'asc';
    } else {
        form.querySelector('input[name="sort_by"]').value = field;
        form.querySelector('input[name="sort_direction"]').value = 'desc';
    }

    form.submit();
}
</script>
@endsection
