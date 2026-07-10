@extends('layouts.app')

@section('title', 'الإجازات والطلبات')

@section('content')
<div class="space-y-6">
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

    <div class="grid grid-cols-2 md:grid-cols-12 gap-4">
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
                    <button id="listViewBtn" class="inline-flex items-center gap-2 px-3 py-2 bg-slate-800 text-white rounded-xl text-sm font-semibold transition-all">
                        <i data-lucide="list" class="w-4 h-4"></i>
                        <span class="hidden sm:inline">قائمة</span>
                    </button>
                    <button id="calendarViewBtn" class="inline-flex items-center gap-2 px-3 py-2 bg-slate-200 text-slate-700 rounded-xl text-sm font-semibold transition-all hover:bg-slate-300">
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

        <form method="GET" action="{{ route('my.requests.index') }}" class="mb-6 space-y-4" id="filterForm">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4">
                <div class="col-span-2 md:col-span-3">
                    <label class="block text-xs font-bold text-slate-600 mb-2">بحث</label>
                    <div class="relative">
                        <i data-lucide="search" class="absolute right-3 top-3 h-4 w-4 text-slate-400"></i>
                        <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="ابحث عن طلب..." class="employee-form-input w-full rounded-xl border border-slate-200 bg-slate-50 pr-10 pl-4 py-2.5 text-sm focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all">
                    </div>
                </div>
                <div class="md:col-span-1">
                    <label class="block text-xs font-bold text-slate-600 mb-2">الحالة</label>
                    <select name="status" class="employee-form-input w-full rounded-xl border border-slate-200 bg-slate-50 px-8 py-2.5 text-sm focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all">
                        <option value="">الكل</option>
                        <option value="pending" {{ ($filters['status'] ?? '') === 'pending' ? 'selected' : '' }}>معلقة</option>
                        <option value="approved" {{ ($filters['status'] ?? '') === 'approved' ? 'selected' : '' }}>موافق عليها</option>
                        <option value="rejected" {{ ($filters['status'] ?? '') === 'rejected' ? 'selected' : '' }}>مرفوضة</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-2">من تاريخ</label>
                    <input type="date" name="from_date" value="{{ $filters['from_date'] ?? '' }}" class="employee-form-input w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-2">إلى تاريخ</label>
                    <input type="date" name="to_date" value="{{ $filters['to_date'] ?? '' }}" class="employee-form-input w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all">
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl font-medium transition-all">
                        <i data-lucide="filter" class="w-4 h-4"></i>
                    </button>
                    <a href="{{ route('my.requests.index') }}" class="flex-1 px-4 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-xl font-medium transition-all flex items-center justify-center">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>
            <input type="hidden" name="sort_by" value="{{ request('sort_by') }}">
            <input type="hidden" name="sort_direction" value="{{ request('sort_direction', 'desc') }}">
        </form>

        <div id="listView">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="mb-2">
                        <tr class="border-b border-slate-200">
                            <th class="pb-4 text-right cursor-pointer hover:text-cyan-600 transition-colors" onclick="sortBy('transaction_type')">
                                <div class="flex items-center gap-1">
                                    النوع
                                    @if(request('sort_by') === 'transaction_type')
                                        <i data-lucide="{{ request('sort_direction') === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="w-3.5 h-3.5"></i>
                                    @endif
                                </div>
                            </th>
                            <th class="pb-4 text-right cursor-pointer hover:text-cyan-600 transition-colors" onclick="sortBy('status')">
                                <div class="flex items-center gap-1">
                                    الحالة
                                    @if(request('sort_by') === 'status')
                                        <i data-lucide="{{ request('sort_direction') === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="w-3.5 h-3.5"></i>
                                    @endif
                                </div>
                            </th>
                            <th class="pb-4 text-right cursor-pointer hover:text-cyan-600 transition-colors" onclick="sortBy('start_date_time')">
                                <div class="flex items-center gap-1">
                                    من تاريخ
                                    @if(request('sort_by') === 'start_date_time')
                                        <i data-lucide="{{ request('sort_direction') === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="w-3.5 h-3.5"></i>
                                    @endif
                                </div>
                            </th>
                            <th class="pb-4 text-right cursor-pointer hover:text-cyan-600 transition-colors" onclick="sortBy('end_date_time')">
                                <div class="flex items-center gap-1">
                                    إلى تاريخ
                                    @if(request('sort_by') === 'end_date_time')
                                        <i data-lucide="{{ request('sort_direction') === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="w-3.5 h-3.5"></i>
                                    @endif
                            </th>
                            <th class="pb-4 text-right">المدة</th>
                            <th class="pb-4 text-right">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
        <div id="listView">
            <div class="overflow-x-auto -mx-4 md:mx-0">
                <div class="inline-block min-w-full align-middle">
                    <table class="w-full text-sm">
                        <thead class="mb-2">
                            <tr class="border-b border-slate-200">
                                <th class="pb-4 text-right px-4 md:px-0">النوع</th>
                                <th class="pb-4 text-right px-4 md:px-0">الحالة</th>
                                <th class="pb-4 text-right cursor-pointer hover:text-cyan-600 transition-colors px-4 md:px-0" onclick="sortBy('start_date_time')">
                                    <div class="flex items-center gap-1">
                                        من تاريخ
                                        @if(request('sort_by') === 'start_date_time')
                                            <i data-lucide="{{ request('sort_direction') === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="w-3.5 h-3.5"></i>
                                        @endif
                                    </div>
                                </th>
                                <th class="pb-4 text-right cursor-pointer hover:text-cyan-600 transition-colors px-4 md:px-0" onclick="sortBy('end_date_time')">
                                    <div class="flex items-center gap-1">
                                        إلى تاريخ
                                        @if(request('sort_by') === 'end_date_time')
                                            <i data-lucide="{{ request('sort_direction') === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="w-3.5 h-3.5"></i>
                                        @endif
                                    </div>
                                </th>
                                <th class="pb-4 text-right px-4 md:px-0">المدة</th>
                                <th class="pb-4 text-right px-4 md:px-0">إجراءات</th>
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
                                        'leave' => ['label' => 'إجازة', 'icon' => 'calendar-days', 'bg' => 'bg-sky-100', 'text' => 'text-sky-700', 'border' => 'border-sky-200'],
                                        'permission' => ['label' => 'إذن', 'icon' => 'clock', 'bg' => 'bg-violet-100', 'text' => 'text-violet-700', 'border' => 'border-violet-200'],
                                        'promotion' => ['label' => 'ترقية', 'icon' => 'trending-up', 'bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200'],
                                        'penalty' => ['label' => 'عقوبة', 'icon' => 'alert-triangle', 'bg' => 'bg-rose-100', 'text' => 'text-rose-700', 'border' => 'border-rose-200'],
                                        'transfer' => ['label' => 'نقل', 'icon' => 'truck', 'bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'border' => 'border-amber-200'],
                                        default => ['label' => $transaction->transaction_type, 'icon' => 'file-text', 'bg' => 'bg-slate-100', 'text' => 'text-slate-700', 'border' => 'border-slate-200'],
                                    };
                                    $days = \Carbon\Carbon::parse($transaction->start_date_time)->diffInDays(\Carbon\Carbon::parse($transaction->end_date_time)) + 1;
                                @endphp
                                <tr class="hover:bg-slate-50 transition-colors" data-transaction-id="{{ $transaction->id }}">
                                    <td class="py-4 px-4 md:px-0">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg {{ $typeConfig['bg'] }} flex items-center justify-center flex-shrink-0">
                                                <i data-lucide="{{ $typeConfig['icon'] }}" class="w-4 h-4 {{ $typeConfig['text'] }}"></i>
                                            </div>
                                            <span class="font-semibold text-slate-800">{{ $typeConfig['label'] }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4 md:px-0">
                                        <span class="inline-flex items-center rounded-full border px-3 py-0.5 text-xs font-semibold {{ $statusColors }}">
                                            {{ match($transaction->status) { 'pending' => 'معلقة', 'approved' => 'موافق عليها', 'rejected' => 'مرفوضة', default => $transaction->status } }}
                                        </span>
                                    </td>
                                    <td class="py-4 text-slate-600 px-4 md:px-0">{{ \Carbon\Carbon::parse($transaction->start_date_time)->format('Y-m-d') }}</td>
                                    <td class="py-4 text-slate-600 px-4 md:px-0">{{ \Carbon\Carbon::parse($transaction->end_date_time)->format('Y-m-d') }}</td>
                                    <td class="py-4 text-slate-600 px-4 md:px-0">{{ (int) $days }} يوم</td>
                                    <td class="py-4 px-4 md:px-0">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('my.requests.show', $transaction) }}" class="px-3 py-1.5 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-semibold rounded-lg transition" title="عرض التفاصيل">
                                                <i data-lucide="eye" class="w-4 h-4"></i>
                                            </a>
                                            @if($isAdmin || $isManager)
                                                @if($transaction->status === 'pending')
                                                    <form action="{{ url($basePath . '/' . $transaction->id . '/status') }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من قبول هذا الطلب؟')">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="approved">
                                                        <button type="submit" class="px-3 py-1.5 bg-emerald-100 hover:bg-emerald-200 text-emerald-700 text-xs font-semibold rounded-lg transition" title="قبول">
                                                            <i data-lucide="check" class="w-4 h-4"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ url($basePath . '/' . $transaction->id . '/status') }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من رفض هذا الطلب؟')">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="rejected">
                                                        <button type="submit" class="px-3 py-1.5 bg-rose-100 hover:bg-rose-200 text-rose-700 text-xs font-semibold rounded-lg transition" title="رفض">
                                                            <i data-lucide="x" class="w-4 h-4"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            @endif
                                            @if($transaction->status === 'pending' && !$isAdmin && !$isManager && $transaction->employee_id === auth()->user()?->employee?->id)
                                                <form action="{{ route('my.requests.destroy', $transaction) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من إلغاء هذا الطلب؟')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="px-3 py-1.5 bg-slate-200 hover:bg-red-100 text-slate-600 hover:text-red-600 text-xs font-semibold rounded-lg transition" title="إلغاء الطلب">
                                                        <i data-lucide="x" class="w-4 h-4"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center">
                                    <div class="w-20 h-20 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                        </svg>
                                    </div>
                                    <p class="text-slate-500 font-medium text-lg">لا توجد طلبات حالياً</p>
                                    <p class="text-slate-400 text-sm mt-1 mb-4">ابدأ بإنشاء طلبك الأول وسيظهر هنا</p>
                                    <a href="{{ route('my.requests.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-500 to-teal-400 text-white rounded-xl text-sm font-semibold shadow-lg hover:shadow-xl transition-all">
                                        <i data-lucide="plus" class="w-4 h-4"></i>
                                        إنشاء طلب جديد
                                    </a>
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
