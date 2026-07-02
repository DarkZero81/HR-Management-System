@extends('layouts.app')

@section('title', 'لوحة التحكم')

@section('content')
<div class="space-y-6">
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        @php($stats = [
            ['label' => 'إجمالي الموظفين', 'value' => $employeeCount, 'hint' => '+12% هذا الشهر', 'accent' => 'from-blue-500 to-cyan-500'],
            ['label' => 'الحضور اليوم', 'value' => $todayAttendance, 'hint' => 'سجلات اليوم', 'accent' => 'from-emerald-500 to-lime-500'],
            ['label' => 'إجمالي دقائق التأخير', 'value' => $lateMinutes, 'hint' => 'تتبع مباشر', 'accent' => 'from-amber-500 to-orange-500'],
            ['label' => 'الطلبات المعلقة', 'value' => $pendingRequests, 'hint' => 'مراجعة فورية', 'accent' => 'from-fuchsia-500 to-violet-500'],
        ])
        @foreach($stats as $stat)
            <div class="rounded-[24px] border border-slate-200/70 bg-white/80 p-5 shadow-[0_20px_60px_-35px_rgba(15,23,42,0.45)] backdrop-blur">
                <div class="h-2 w-16 rounded-full bg-gradient-to-r {{ $stat['accent'] }}"></div>
                <p class="mt-4 text-sm font-semibold text-slate-500">{{ $stat['label'] }}</p>
                <div class="mt-3 text-3xl font-black text-slate-900">{{ $stat['value'] }}</div>
                <p class="mt-2 text-sm text-slate-500">{{ $stat['hint'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="grid gap-6 xl:grid-cols-[1.6fr_0.9fr]">
        <div class="rounded-[28px] border border-slate-200/70 bg-white/80 p-5 shadow-[0_20px_60px_-35px_rgba(15,23,42,0.45)] backdrop-blur">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-slate-500">سجل الحضور</p>
                    <h3 class="text-xl font-black text-slate-900">أحدث الحضور اليوم</h3>
                </div>
                <a href="{{ route('attendance.index') }}" class="text-sm font-semibold text-blue-600">عرض الكل</a>
            </div>
            <div class="mt-4 overflow-hidden rounded-2xl border border-slate-200">
                <table class="min-w-full text-right text-sm">
                    <thead class="bg-slate-50 text-slate-600">
                        <tr>
                            <th class="px-4 py-3 font-semibold">الموظف</th>
                            <th class="px-4 py-3 font-semibold">الحضور</th>
                            <th class="px-4 py-3 font-semibold">التأخير</th>
                            <th class="px-4 py-3 font-semibold">الحالة</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse($recentAttendance as $log)
                            <tr class="transition hover:bg-slate-50">
                                <td class="px-4 py-3 font-semibold text-slate-900">{{ $log->employee->full_name ?? '—' }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $log->check_in ? $log->check_in->format('H:i') : '—' }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $log->late_minutes }} د</td>
                                <td class="px-4 py-3"><span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-600">{{ $log->status ?? 'مكتمل' }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-4 py-6 text-center text-slate-500">لا توجد سجلات حضور اليوم بعد.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-[28px] border border-slate-200/70 bg-gradient-to-br from-slate-950 to-slate-800 p-5 text-white shadow-[0_20px_60px_-35px_rgba(15,23,42,0.9)]">
                <p class="text-sm font-semibold text-slate-400">البوابة السريعة</p>
                <h3 class="mt-2 text-xl font-black">إجراءات يومية</h3>
                <div class="mt-5 space-y-3">
                    <a href="{{ route('employees.index') }}" class="flex items-center justify-between rounded-2xl bg-white/10 px-4 py-3 text-sm font-semibold transition hover:bg-white/20"><span>إدارة الموظفين</span><i data-lucide="arrow-left" class="h-4 w-4"></i></a>
                    <a href="{{ route('my.requests.index') }}" class="flex items-center justify-between rounded-2xl bg-white/10 px-4 py-3 text-sm font-semibold transition hover:bg-white/20"><span>إنشاء طلب جديد</span><i data-lucide="file-plus" class="h-4 w-4"></i></a>
                    <a href="{{ route('payroll.index') }}" class="flex items-center justify-between rounded-2xl bg-white/10 px-4 py-3 text-sm font-semibold transition hover:bg-white/20"><span>متابعة الرواتب</span><i data-lucide="receipt-text" class="h-4 w-4"></i></a>
                </div>
            </div>

            <div class="rounded-[28px] border border-slate-200/70 bg-white/80 p-5 shadow-[0_20px_60px_-35px_rgba(15,23,42,0.45)] backdrop-blur">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-slate-500">الطلبات</p>
                        <h3 class="text-lg font-black text-slate-900">قائمة المراجعة</h3>
                    </div>
                    <a href="{{ route('my.requests.index') }}" class="text-sm font-semibold text-blue-600">عرض</a>
                </div>
                <div class="mt-4 space-y-3">
                    @forelse($pendingTransactions as $transaction)
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-3">
                            <div class="flex items-center justify-between">
                                <p class="font-semibold text-slate-900">{{ $transaction->employee->full_name ?? '—' }}</p>
                                <span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-700">{{ $transaction->status }}</span>
                            </div>
                            <p class="mt-2 text-sm text-slate-600">{{ $transaction->description ?? 'طلب جديد' }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">لا توجد طلبات معلقة حالياً.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
