@extends('layouts.app')

@section('title', 'حضوري الشخصي')

@section('content')
    <div class="space-y-6 my-4">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs font-black uppercase tracking-[0.35em] text-slate-400">الدوام والحضور</p>
                <h1 class="text-3xl font-bold text-slate-800">سجل حضوري الشخصي</h1>
                <p class="text-sm text-slate-400 mt-1">عرض سجل حضورك وانصرافك المسجل عبر أجهزة البصمة.</p>
            </div>
            <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-white rounded-xl font-medium transition-all border border-white/10">
                <i data-lucide="user" class="w-4 h-4"></i>
                <span class="hidden sm:inline">الملف الشخصي</span>
            </a>
        </div>

        @php
            $hasCheckedIn = $todayLog?->check_in !== null;
            $hasCheckedOut = $todayLog?->check_out !== null;
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6 hover:shadow-xl transition-shadow">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br mb-4 from-blue-500 to-blue-600 flex items-center justify-center text-white shadow-lg shadow-blue-500/25">
                        <i data-lucide="log-in" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-slate-800">تسجيل الدخول</h3>
                        <p class="text-xs text-slate-500">بداية الدوام الرسمي</p>
                    </div>
                </div>

                <form action="{{ route('my.attendance.checkin') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="employee_id" value="{{ auth()->user()?->employee?->id }}">
                    <div>
                        <label for="device_id" class="block text-sm font-semibold text-slate-700 mb-6">جهاز البصمة (اختياري)</label>
                        <select name="device_id" id="device_id"
                            class="w-full rounded-xl border-slate-200 bg-white px-8 py-2.5 text-slate-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all">
                            <option value="">— بدون جهاز —</option>
                            @foreach($devices as $device)
                                <option value="{{ $device->id }}">{{ $device->device_name }} ({{ $device->ip_address }})</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" @if($hasCheckedIn) disabled @endif
                        class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-bold shadow-lg transition-all @if(!$hasCheckedIn) bg-gradient-to-r from-blue-500 to-teal-400 hover:from-blue-600 hover:to-teal-500 text-white shadow-blue-500/25 @else bg-slate-100 text-slate-500 cursor-not-allowed @endif">
                        <i data-lucide="log-in" class="w-5 h-5"></i>
                        {{ $hasCheckedIn ? 'تم تسجيل الدخول ' . $todayLog->check_in?->format('H:i') : 'تسجيل الدخول الآن' }}
                    </button>
                </form>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6 hover:shadow-xl transition-shadow">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center text-white shadow-lg shadow-amber-500/25">
                        <i data-lucide="log-out" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-slate-800">تسجيل الخروج</h3>
                        <p class="text-xs text-slate-500">نهاية الدوام الرسمي</p>
                    </div>
                </div>

                <form action="{{ route('my.attendance.checkout') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="employee_id" value="{{ auth()->user()?->employee?->id }}">
                    <div class="rounded-xl bg-slate-50 border border-slate-200 p-4 space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">حالة اليوم</span>
                            <span class="font-semibold text-slate-700">
                                @if($todayLog)
                                    @php
                                        $statusLabel = match($todayLog->status) {
                                            'present' => 'حاضر',
                                            'late' => 'متأخر',
                                            'absent' => 'غائب',
                                            'holiday' => 'إجازة',
                                            default => $todayLog->status
                                        };
                                        $statusColor = match($todayLog->status) {
                                            'present' => 'text-emerald-600 bg-emerald-100',
                                            'late' => 'text-amber-600 bg-amber-100',
                                            'absent' => 'text-rose-600 bg-rose-100',
                                            'holiday' => 'text-blue-600 bg-blue-100',
                                            default => 'text-slate-600 bg-slate-100'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-4 py-0.5 rounded-full text-xs font-semibold {{ $statusColor }}">
                                        {{ $statusLabel }}
                                    </span>
                                @else
                                    <span class="text-slate-400">لم يسجل بعد</span>
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">وقت الدخول</span>
                            <span class="font-semibold text-slate-700">{{ $todayLog?->check_in?->format('H:i') ?? '—' }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">وقت الخروج</span>
                            <span class="font-semibold text-slate-700">{{ $todayLog?->check_out?->format('H:i') ?? '—' }}</span>
                        </div>
                    </div>
                    <button type="submit" @if(!$hasCheckedIn || $hasCheckedOut) disabled @endif
                        class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-bold shadow-lg transition-all @if($hasCheckedIn && !$hasCheckedOut) bg-gradient-to-r from-amber-500 to-orange-400 hover:from-amber-600 hover:to-orange-500 text-white shadow-amber-500/25 @else bg-slate-100 text-slate-500 cursor-not-allowed @endif">
                        <i data-lucide="log-out" class="w-5 h-5"></i>
                        @if($hasCheckedOut)
                            تم تسجيل الخروج {{ $todayLog->check_out?->format('H:i') }}
                        @elseif(!$hasCheckedIn)
                            سجل الدخول أولاً
                        @else
                            تسجيل الخروج الآن
                        @endif
                    </button>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
            <form method="GET" action="{{ route('my.attendance') }}" class="flex flex-col gap-3 sm:flex-row sm:items-end">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-slate-700 mb-2">الشهر</label>
                    <input type="month" name="month" value="{{ request('month', now()->format('Y-m')) }}"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-slate-800">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-500 to-teal-400 hover:to-teal-500 text-white font-semibold rounded-xl shadow-lg transition-all whitespace-nowrap">
                        <i data-lucide="search" class="w-4 h-4 inline-block ml-1"></i>
                        عرض
                    </button>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center">
                        <i data-lucide="calendar" class="w-6 h-6 text-blue-600"></i>
                    </div>
                </div>
                <p class="text-3xl font-bold text-slate-800">{{ $stats['total'] }}</p>
                <p class="text-sm text-slate-500 mt-2">إجمالي السجلات</p>
            </div>
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center">
                        <i data-lucide="check-circle" class="w-6 h-6 text-emerald-600"></i>
                    </div>
                </div>
                <p class="text-2xl font-bold text-emerald-600">{{ $stats['present'] }}</p>
                <p class="text-sm text-slate-500 mt-2">أيام حضور</p>
            </div>
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center">
                        <i data-lucide="alarm-clock" class="w-6 h-6 text-amber-600"></i>
                    </div>
                </div>
                <p class="text-2xl font-bold text-amber-600">{{ $stats['late'] }}</p>
                <p class="text-sm text-slate-500 mt-2">أيام تأخير</p>
            </div>
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-rose-100 flex items-center justify-center">
                        <i data-lucide="x-circle" class="w-6 h-6 text-rose-600"></i>
                    </div>
                </div>
                <p class="text-2xl font-bold text-rose-600">{{ $stats['absent'] }}</p>
                <p class="text-sm text-slate-500 mt-2">أيام غياب</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-right">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-4">التاريخ</th>
                            <th class="px-6 py-4">وقت الحضور</th>
                            <th class="px-6 py-4">وقت الانصراف</th>
                            <th class="px-6 py-4">الجهاز</th>
                            <th class="px-6 py-4">دقائق التأخير</th>
                            <th class="px-6 py-4">دقائق إضافي</th>
                            <th class="px-6 py-4">الحالة</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($logs as $log)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 font-medium text-slate-800">{{ \Illuminate\Support\Carbon::parse($log->log_date)->format('Y-m-d') }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $log->check_in ? \Illuminate\Support\Carbon::parse($log->check_in)->format('H:i') : '-' }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $log->check_out ? \Illuminate\Support\Carbon::parse($log->check_out)->format('H:i') : '-' }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $log->device->device_name ?? '-' }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $log->late_minutes ?? 0 }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $log->overtime_minutes ?? 0 }}</td>
                                <td class="px-6 py-4">
                                    @php($statusClasses = [
                                        'present' => 'bg-emerald-100 text-emerald-700',
                                        'late' => 'bg-amber-100 text-amber-700',
                                        'absent' => 'bg-rose-100 text-rose-700',
                                        'holiday' => 'bg-blue-100 text-blue-700',
                                    ][$log->status] ?? 'bg-slate-100 text-slate-700')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $statusClasses }}">
                                        {{ match($log->status) { 'present' => 'حاضر', 'late' => 'متأخر', 'absent' => 'غائب', 'holiday' => 'إجازة', default => $log->status } }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-slate-400">لا يوجد سجل حضور بعد.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($logs->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
