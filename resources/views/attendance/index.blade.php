@extends('layouts.app')

@section('title', 'الدوام والحضور')

@section('content')
<div class="space-y-6">
    <div class="rounded-[32px] border border-slate-200/70 bg-white/90 p-6 shadow-[0_25px_90px_-35px_rgba(15,23,42,0.15)] backdrop-blur">
        <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.35em] text-slate-500">الدوام والحضور</p>
                <h1 class="mt-2 text-3xl font-black text-slate-900">سجل الدوام الشهري</h1>
                <p class="mt-2 text-sm text-slate-600">تعقب دخول وخروج الموظفين بسرعة وسهولة.</p>
            </div>
            <form action="{{ route('attendance.checkin') }}" method="POST" class="inline-flex items-center gap-3 rounded-3xl bg-slate-950 px-4 py-3 text-white shadow-xl">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-l from-cyan-500 to-blue-600 px-5 py-3 text-sm font-semibold transition hover:opacity-90">
                    <i data-lucide="log-in" class="h-4 w-4"></i>
                    تسجيل الحضور
                </button>
            </form>
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
        @php($statCards = [
            ['label' => 'أيام الحضور', 'value' => $logs->where('status', 'present')->count(), 'icon' => 'check-circle-2', 'gradient' => 'from-cyan-500 to-blue-500', 'bg' => 'bg-cyan-50'],
            ['label' => 'أيام التأخير', 'value' => $logs->where('status', 'late')->count(), 'icon' => 'clock', 'gradient' => 'from-amber-500 to-orange-500', 'bg' => 'bg-amber-50'],
            ['label' => 'أيام الغياب', 'value' => $logs->where('status', 'absent')->count(), 'icon' => 'x-circle', 'gradient' => 'from-rose-500 to-red-500', 'bg' => 'bg-rose-50'],
            ['label' => 'ساعات إضافية', 'value' => $logs->sum('overtime_minutes') . ' د', 'icon' => 'plus-circle', 'gradient' => 'from-indigo-500 to-purple-600', 'bg' => 'bg-indigo-50'],
            ['label' => 'إجمالي التأخير', 'value' => $logs->sum('late_minutes') . ' د', 'icon' => 'timer', 'gradient' => 'from-violet-500 to-fuchsia-500', 'bg' => 'bg-violet-50'],
        ])
        @foreach($statCards as $card)
        <div class="rounded-[28px] border border-slate-200/70 bg-white/90 p-5 shadow-[0_20px_60px_-35px_rgba(15,23,42,0.12)]">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-slate-500">{{ $card['label'] }}</p>
                    <p class="mt-4 text-3xl font-black text-slate-900">{{ $card['value'] }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-3xl bg-gradient-to-br {{ $card['gradient'] }} text-white shadow-lg">
                    <i data-lucide="{{ $card['icon'] }}" class="h-5 w-5"></i>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="rounded-[32px] border border-slate-200/70 bg-white/90 p-6 shadow-[0_25px_90px_-35px_rgba(15,23,42,0.12)] backdrop-blur">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-slate-500">تفاصيل الدوام</p>
                <h2 class="text-xl font-black text-slate-900">سجل الحضور الكامل</h2>
            </div>
            <span class="rounded-full bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700">{{ $logs->count() }} سجل</span>
        </div>
        <div class="mt-6 overflow-hidden rounded-[28px] border border-slate-200">
            <table class="min-w-full text-right text-sm">
                <thead class="bg-slate-100 text-slate-600">
                    <tr>
                        <th class="px-5 py-4 font-semibold">التاريخ</th>
                        <th class="px-5 py-4 font-semibold">الموظف</th>
                        <th class="px-5 py-4 font-semibold">دخول</th>
                        <th class="px-5 py-4 font-semibold">خروج</th>
                        <th class="px-5 py-4 font-semibold">تأخير</th>
                        <th class="px-5 py-4 font-semibold">إضافية</th>
                        <th class="px-5 py-4 font-semibold">الحالة</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse($logs as $log)
                        @php($statusClasses = [
                            'present' => 'bg-emerald-50 text-emerald-700',
                            'late' => 'bg-amber-50 text-amber-700',
                            'absent' => 'bg-rose-50 text-rose-700',
                        ][$log->status] ?? 'bg-slate-50 text-slate-700')
                        <tr class="transition hover:bg-slate-50">
                            <td class="px-5 py-4 text-slate-900">{{ $log->log_date }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $log->employee->user->name ?? '—' }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $log->check_in?->format('H:i') ?? '—' }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $log->check_out?->format('H:i') ?? '—' }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $log->late_minutes }} د</td>
                            <td class="px-5 py-4 text-slate-600">{{ $log->overtime_minutes }} د</td>
                            <td class="px-5 py-4"><span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses }}">{{ match($log->status) { 'present' => 'حاضر', 'late' => 'متأخر', 'absent' => 'غائب' } }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-10 text-center text-slate-500">لا توجد سجلات دوام بعد.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $logs->links() }}</div>
    </div>
</div>
@endsection