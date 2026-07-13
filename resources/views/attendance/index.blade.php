{{-- Attendance management page - admin view with filters, stats cards, and attendance logs table --}}

@extends('layouts.app')

@section('title', 'تسجيل الحضور')

@section('content')
<div class="space-y-6 my-4">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-xs font-black uppercase tracking-[0.35em] text-slate-400">الدوام والحضور</p>
            <h1 class="text-3xl font-bold text-slate-800">تسجيل الحضور والانصراف</h1>
            <p class="text-sm text-slate-400 mt-1">سجّل دخولك وخروجك اليومي بضغطة زر واحدة.</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-700">
                <i data-lucide="calendar" class="w-4 h-4 text-slate-400"></i>
                {{ \Carbon\Carbon::today()->format('Y-m-d') }}
            </span>
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

    @php
        $employee = auth()->user()?->employee;
        $hasEmployee = (bool) $employee;
        $today = \Carbon\Carbon::today()->format('Y-m-d');
        $todayLog = $hasEmployee ? \App\Models\AttendanceLog::where('employee_id', $employee->id)->where('log_date', $today)->first() : null;
        $hasCheckedIn = $todayLog?->check_in !== null;
        $hasCheckedOut = $todayLog?->check_out !== null;
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6 hover:shadow-xl transition-shadow">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white shadow-lg shadow-blue-500/25">
                    <i data-lucide="log-in" class="w-6 h-6"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-800">تسجيل الدخول</h3>
                    <p class="text-xs text-slate-500">بداية الدوام الرسمي</p>
                </div>
            </div>

            @if(!$hasEmployee)
                <div class="rounded-xl bg-slate-50 border border-slate-200 p-4 text-center">
                    <p class="text-sm text-slate-600">لا يوجد ملف موظف مرتبط بهذا الحساب.</p>
                </div>
            @else
                <form action="{{ route('attendance.checkin') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                    <div>
                        <label for="device_id" class="block text-sm font-semibold text-slate-700 mb-3">جهاز البصمة (اختياري)</label>
                        <select name="device_id" id="device_id"
                            class="w-full rounded-xl border-slate-200 bg-white px-8 py-2.5 mb-8 text-slate-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all">
                            <option value="">— بدون جهاز —</option>
                            @foreach($devices as $device)
                                <option value="{{ $device->id }}">{{ $device->device_name }} ({{ $device->ip_address }})</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" @if($hasCheckedIn) disabled @endif
                        class="w-full inline-flex items-center justify-center mt-6 gap-2 px-6 py-3 rounded-xl font-bold shadow-lg transition-all @if(!$hasCheckedIn) bg-gradient-to-r from-blue-500 to-teal-400 hover:from-blue-600 hover:to-teal-500 text-white shadow-blue-500/25 @else bg-slate-100 text-slate-500 cursor-not-allowed @endif">
                        <i data-lucide="log-in" class="w-5 h-5"></i>
                        {{ $hasCheckedIn ? 'تم تسجيل الدخول ' . $todayLog->check_in?->format('H:i') : 'تسجيل الدخول الآن' }}
                    </button>
                </form>
            @endif
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

            @if(!$hasEmployee)
                <div class="rounded-xl bg-slate-50 border border-slate-200 p-4 text-center">
                    <p class="text-sm text-slate-600">لا يوجد ملف موظف مرتبط بهذا الحساب.</p>
                </div>
            @else
                <form action="{{ route('attendance.checkout') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="employee_id" value="{{ $employee->id }}">
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
            @endif
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
        <form method="GET" action="{{ route('attendance.index') }}" class="flex flex-col gap-4 sm:flex-row sm:items-end">
            <div class="flex-1">
                <label class="block text-sm font-semibold text-slate-700 mb-2">من تاريخ</label>
                <input type="date" name="from_date" value="{{ request('from_date') }}"
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-slate-800">
            </div>
            <div class="flex-1">
                <label class="block text-sm font-semibold text-slate-700 mb-2">إلى تاريخ</label>
                <input type="date" name="to_date" value="{{ request('to_date') }}"
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-slate-800">
            </div>
            <div class="flex-1">
                <label class="block text-sm font-semibold text-slate-700 mb-2">الحالة</label>
                <select name="status" class="w-full px-8 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-slate-800">
                    <option value="">كل الحالات</option>
                    <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>حاضر</option>
                    <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>متأخر</option>
                    <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>غائب</option>
                    <option value="holiday" {{ request('status') == 'holiday' ? 'selected' : '' }}>إجازة</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-500 to-teal-400 hover:to-teal-500 text-white font-semibold rounded-xl shadow-lg transition-all whitespace-nowrap">
                    <i data-lucide="search" class="w-4 h-4 inline-block ml-1"></i>
                    بحث
                </button>
                <a href="{{ route('attendance.index') }}" class="px-6 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-500 font-semibold rounded-xl transition-all whitespace-nowrap">
                    <i data-lucide="rotate-ccw" class="w-4 h-4 inline-block ml-1"></i>
                    إعادة تعيين
                </a>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100 hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm font-semibold text-slate-500">إجمالي السجلات</p>
                <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center">
                    <i data-lucide="database" class="w-5 h-5 text-slate-600"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-slate-800">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100 hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm font-semibold text-slate-500">حاضر</p>
                <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                    <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-emerald-600">{{ $stats['present'] }}</p>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100 hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm font-semibold text-slate-500">متأخر</p>
                <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center">
                    <i data-lucide="alarm-clock" class="w-5 h-5 text-amber-600"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-amber-600">{{ $stats['late'] }}</p>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100 hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm font-semibold text-slate-500">غائب</p>
                <div class="w-10 h-10 rounded-xl bg-rose-100 flex items-center justify-center">
                    <i data-lucide="x-circle" class="w-5 h-5 text-rose-600"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-rose-600">{{ $stats['absent'] }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 bg-gradient-to-l from-slate-50 to-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.35em] text-slate-400">تفاصيل الدوام</p>
                    <h2 class="text-xl font-bold text-slate-800 mt-1">سجل الحضور الكامل</h2>
                </div>
                <span class="rounded-full bg-blue-50 px-4 py-2 text-sm font-bold text-blue-700 border border-blue-100">{{ $logs->total() }} سجل</span>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">الموظف</th>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">التاريخ</th>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">دخول</th>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">خروج</th>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">تأخير</th>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">إضافية</th>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">الحالة</th>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-50/80 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center text-white font-bold text-xs shadow-md">
                                        {{ strtoupper(substr($log->employee?->first_name ?? 'U', 0, 1)) }}
                                    </div>
                                    <span class="font-semibold text-slate-800">{{ $log->employee?->full_name ?? '—' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-lg bg-slate-100 text-slate-500 text-sm font-medium">
                                    {{ $log->log_date }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-600">{{ $log->check_in?->format('H:i') ?? '—' }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $log->check_out?->format('H:i') ?? '—' }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $log->late_minutes }} د</td>
                            <td class="px-6 py-4 text-slate-600">{{ $log->overtime_minutes }} د</td>
                            <td class="px-6 py-4">
                                @php
                                    $statusClasses = match($log->status) {
                                        'present' => 'bg-emerald-100 text-emerald-700 border border-emerald-200',
                                        'late' => 'bg-amber-100 text-amber-700 border border-amber-200',
                                        'absent' => 'bg-rose-100 text-rose-700 border border-rose-200',
                                        'holiday' => 'bg-blue-100 text-blue-700 border border-blue-200',
                                        default => 'bg-slate-100 text-slate-700 border border-slate-200'
                                    };
                                    $statusLabel = match($log->status) {
                                        'present' => 'حاضر',
                                        'late' => 'متأخر',
                                        'absent' => 'غائب',
                                        'holiday' => 'إجازة',
                                        default => $log->status
                                    };
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $statusClasses }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $userRole = optional(auth()->user()->role)->role_name;
                                    $canManage = in_array(strtolower($userRole), ['admin', 'manager'], true);
                                @endphp
                                @if($canManage)
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('attendance.edit', $log) }}" class="p-2 rounded-xl bg-slate-100 hover:bg-blue-100 text-slate-600 hover:text-blue-600 transition-all" title="تعديل">
                                        <i data-lucide="pencil" class="w-4 h-4"></i>
                                    </a>
                                    <form action="{{ route('attendance.destroy', $log) }}" method="POST" class="inline" onsubmit="return confirm('حذف هذا السجل؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-xl bg-slate-100 hover:bg-red-100 text-slate-600 hover:text-red-600 transition-all" title="حذف">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center">
                                        <i data-lucide="clock" class="w-8 h-8 text-slate-400"></i>
                                    </div>
                                    <p class="text-sm font-semibold text-slate-500">لا توجد سجلات دوام بعد.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-100 bg-slate-50 px-6 py-4">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection
