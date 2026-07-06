@extends('layouts.app')

@section('title', 'تسجيل الحضور')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-xs font-black uppercase tracking-[0.35em] text-slate-400">الدوام والحضور</p>
            <h1 class="text-2xl md:text-3xl font-black text-white mt-1">تسجيل الحضور والانصراف</h1>
            <p class="text-sm text-slate-400 mt-1">اضغط على الزر لتسجيل دخولك أو خروجك اليوم.</p>
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
        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center">
                    <i data-lucide="log-in" class="w-6 h-6 text-blue-600"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-900">تسجيل الدخول</h3>
                    <p class="text-sm text-slate-500">تسجيل حضورك عند بداية الدوام</p>
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
                        <label for="device_id" class="block text-sm font-semibold text-slate-700 mb-1.5">جهاز البصمة (اختياري)</label>
                        <select name="device_id" id="device_id"
                            class="w-full rounded-xl border-slate-200 bg-white px-4 py-2.5 text-slate-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all">
                            <option value="">— بدون جهاز —</option>
                            @foreach($devices as $device)
                                <option value="{{ $device->id }}">{{ $device->device_name }} ({{ $device->ip_address }})</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" @if($hasCheckedIn) disabled @endif
                        class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-bold shadow-lg transition-all @if(!$hasCheckedIn) bg-gradient-to-r from-blue-500 to-teal-400 hover:from-blue-600 hover:to-teal-500 text-white @else bg-slate-100 text-slate-500 cursor-not-allowed @endif">
                        <i data-lucide="log-in" class="w-5 h-5"></i>
                        {{ $hasCheckedIn ? 'تم تسجيل الدخول (' . $todayLog->check_in?->format('H:i') . ')' : 'تسجيل الدخول الآن' }}
                    </button>
                </form>
            @endif
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center">
                    <i data-lucide="log-out" class="w-6 h-6 text-amber-600"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-900">تسجيل الخروج</h3>
                    <p class="text-sm text-slate-500">تسجيل انصرافك عند نهاية الدوام</p>
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
                                    {{ match($todayLog->status) { 'present' => 'حاضر', 'late' => 'متأخر', 'absent' => 'غائب', default => $todayLog->status } }}
                                @else
                                    لم يسجل بعد
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">وقت الدخول</span>
                            <span class="font-semibold text-slate-700">{{ $todayLog?->check_in?->format('H:i') ?? '—' }}</span>
                        </div>
                    </div>
                    <button type="submit" @if(!$hasCheckedIn || $hasCheckedOut) disabled @endif
                        class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-bold shadow-lg transition-all @if($hasCheckedIn && !$hasCheckedOut) bg-gradient-to-r from-amber-500 to-orange-400 hover:from-amber-600 hover:to-orange-500 text-white @else bg-slate-100 text-slate-500 cursor-not-allowed @endif">
                        <i data-lucide="log-out" class="w-5 h-5"></i>
                        @if($hasCheckedOut)
                            تم تسجيل الخروج ({{ $todayLog->check_out?->format('H:i') }})
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

    <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.35em] text-slate-400">تفاصيل الدوام</p>
                    <h2 class="text-xl font-black text-slate-900 mt-1">سجل الحضور الكامل</h2>
                </div>
                <span class="rounded-full bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700">{{ $logs->count() }} سجل</span>
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
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($logs as $log)
                        @php($statusClasses = [
                            'present' => 'bg-emerald-100 text-emerald-700',
                            'late' => 'bg-amber-100 text-amber-700',
                            'absent' => 'bg-rose-100 text-rose-700',
                            'holiday' => 'bg-blue-100 text-blue-700',
                        ][$log->status] ?? 'bg-slate-100 text-slate-700')
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 font-semibold text-slate-900">{{ $log->employee?->full_name ?? $log->employee?->first_name . ' ' . $log->employee?->last_name ?? '—' }}</td>
                            <td class="px-6 py-4 text-slate-800 font-semibold">{{ $log->log_date }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $log->check_in?->format('H:i') ?? '—' }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $log->check_out?->format('H:i') ?? '—' }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $log->late_minutes }} د</td>
                            <td class="px-6 py-4 text-slate-600">{{ $log->overtime_minutes }} د</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $statusClasses }}">
                                    {{ match($log->status) { 'present' => 'حاضر', 'late' => 'متأخر', 'absent' => 'غائب', 'holiday' => 'إجازة', default => $log->status } }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-slate-500">لا توجد سجلات دوام بعد.</td>
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
