@extends('layouts.app')

@section('title', 'حضوري الشخصي')

@section('content')
<div class="space-y-6">
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

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:gap-6">
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center">
                    <i data-lucide="check-circle" class="w-6 h-6 text-emerald-600"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-slate-800">{{ $logs->where('status', 'present')->count() }}</p>
            <p class="text-sm text-slate-500 mt-2">أيام حضور (بهذه الصفحة)</p>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center">
                    <i data-lucide="alarm-clock" class="w-6 h-6 text-amber-600"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-slate-800">{{ $logs->where('status', 'late')->count() }}</p>
            <p class="text-sm text-slate-500 mt-2">أيام تأخير (بهذه الصفحة)</p>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-rose-100 flex items-center justify-center">
                    <i data-lucide="x-circle" class="w-6 h-6 text-rose-600"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-slate-800">{{ $logs->where('status', 'absent')->count() }}</p>
            <p class="text-sm text-slate-500 mt-2">أيام غياب (بهذه الصفحة)</p>
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
