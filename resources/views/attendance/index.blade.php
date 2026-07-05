@extends('layouts.app')

@section('title', 'الدوام والحضور')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-xs font-black uppercase tracking-[0.35em] text-slate-400">الدوام والحضور</p>
            <h1 class="text-2xl md:text-3xl font-black text-white mt-1">سجل الدوام الشهري</h1>
            <p class="text-sm text-slate-400 mt-1">راقب الحضور والانصراف من واجهة واحدة.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-white rounded-xl font-medium transition-all border border-white/10">
                <i data-lucide="user" class="w-4 h-4"></i>
                <span class="hidden sm:inline">الملف الشخصي</span>
            </a>
            <form action="{{ route('attendance.checkin') }}" method="POST">
                @csrf
                <input type="hidden" name="employee_id" value="{{ auth()->user()?->employee?->id }}">
                @if(auth()->user()?->employee?->id)
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-500 to-teal-400 hover:from-blue-600 hover:to-teal-500 text-white rounded-xl font-semibold shadow-lg transition-all">
                        <i data-lucide="log-in" class="w-4 h-4"></i>
                        <span class="hidden sm:inline">تسجيل الحضور</span>
                    </button>
                @endif
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 md:gap-6">
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100 hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-cyan-100 flex items-center justify-center">
                    <i data-lucide="check-circle" class="w-6 h-6 text-cyan-600"></i>
                </div>
                <span class="text-xs text-slate-400 font-medium">هذا الشهر</span>
            </div>
            <p class="text-3xl font-bold text-slate-800">{{ $logs->where('status', 'present')->count() }}</p>
            <p class="text-sm text-slate-500 mt-2">أيام الحضور</p>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100 hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center">
                    <i data-lucide="clock" class="w-6 h-6 text-amber-600"></i>
                </div>
                <span class="text-xs text-slate-400 font-medium">تأخير</span>
            </div>
            <p class="text-3xl font-bold text-slate-800">{{ $logs->where('status', 'late')->count() }}</p>
            <p class="text-sm text-slate-500 mt-2">أيام التأخير</p>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100 hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-rose-100 flex items-center justify-center">
                    <i data-lucide="x-circle" class="w-6 h-6 text-rose-600"></i>
                </div>
                <span class="text-xs text-slate-400 font-medium">غياب</span>
            </div>
            <p class="text-3xl font-bold text-slate-800">{{ $logs->where('status', 'absent')->count() }}</p>
            <p class="text-sm text-slate-500 mt-2">أيام الغياب</p>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100 hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-violet-100 flex items-center justify-center">
                    <i data-lucide="timer" class="w-6 h-6 text-violet-600"></i>
                </div>
                <span class="text-xs text-slate-400 font-medium">إضافي</span>
            </div>
            <p class="text-3xl font-bold text-slate-800">{{ $logs->sum('overtime_minutes') }} د</p>
            <p class="text-sm text-slate-500 mt-2">ساعات إضافية</p>
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
                        ][$log->status] ?? 'bg-slate-100 text-slate-700')
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-slate-800 font-semibold">{{ $log->log_date }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $log->check_in?->format('H:i') ?? '—' }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $log->check_out?->format('H:i') ?? '—' }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $log->late_minutes }} د</td>
                            <td class="px-6 py-4 text-slate-600">{{ $log->overtime_minutes }} د</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $statusClasses }}">
                                    {{ match($log->status) { 'present' => 'حاضر', 'late' => 'متأخر', 'absent' => 'غائب' } }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-slate-500">لا توجد سجلات دوام بعد.</td>
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
