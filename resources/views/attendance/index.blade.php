@extends('layouts.app')

@section('title', 'الدوام والحضور')

@section('content')
<div class="space-y-6">
    <section class="rounded-[32px] bg-gradient-to-r from-slate-900 to-blue-700 p-6 shadow-2xl shadow-blue-700/10 text-white">
        <div class="flex flex-col gap-6 xl:flex-row xl:items-center xl:justify-between">
            <div class="space-y-3 text-right">
                <p class="text-sm uppercase tracking-[0.35em] text-slate-300">الدوام والحضور</p>
                <h1 class="text-3xl font-black">سجل الدوام الشهري</h1>
                <p class="max-w-2xl text-sm text-slate-200">راقب الحضور وانطلق بسهولة من لوحة تحكم بصرية وحديثة تناسب بيئة العمل العربية.</p>
            </div>
            <div class="rounded-[24px] bg-white/10 p-5 shadow-lg shadow-slate-950/20">
                <div class="flex items-center gap-4">
                    <div class="flex h-16 w-16 items-center justify-center rounded-3xl bg-white/10 text-white">
                        <i data-lucide="clock" class="h-7 w-7"></i>
                    </div>
                    <div class="text-right">
                        <p class="text-xs uppercase tracking-[0.35em] text-slate-300">الوقت الحالي</p>
                        <p class="mt-2 text-3xl font-black">{{ now()->format('H:i') }}</p>
                        <p class="text-sm text-slate-300 mt-1">{{ now()->format('d F Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="grid gap-4 xl:grid-cols-[1.45fr_0.95fr]">
        <div class="rounded-[32px] bg-white border border-slate-200/70 p-6 shadow-sm">
            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-[28px] bg-gradient-to-r from-cyan-500 to-blue-600 p-5 text-white shadow-lg">
                    <p class="text-sm uppercase tracking-[0.35em] text-cyan-100">أيام الحضور</p>
                    <p class="mt-4 text-3xl font-black">{{ $logs->where('status', 'present')->count() }}</p>
                    <p class="text-sm text-cyan-100/80 mt-2">هذا الشهر</p>
                </div>
                <div class="rounded-[28px] bg-gradient-to-r from-amber-500 to-orange-500 p-5 text-white shadow-lg">
                    <p class="text-sm uppercase tracking-[0.35em] text-amber-100">أيام التأخير</p>
                    <p class="mt-4 text-3xl font-black">{{ $logs->where('status', 'late')->count() }}</p>
                    <p class="text-sm text-amber-100/80 mt-2">تم احتسابها آلياً</p>
                </div>
                <div class="rounded-[28px] bg-gradient-to-r from-rose-500 to-pink-500 p-5 text-white shadow-lg">
                    <p class="text-sm uppercase tracking-[0.35em] text-rose-100">أيام الغياب</p>
                    <p class="mt-4 text-3xl font-black">{{ $logs->where('status', 'absent')->count() }}</p>
                    <p class="text-sm text-rose-100/80 mt-2">سجلات رسمية</p>
                </div>
                <div class="rounded-[28px] bg-gradient-to-r from-slate-900 to-slate-700 p-5 text-white shadow-lg">
                    <p class="text-sm uppercase tracking-[0.35em] text-slate-300">ساعات إضافية</p>
                    <p class="mt-4 text-3xl font-black">{{ $logs->sum('overtime_minutes') }} د</p>
                    <p class="text-sm text-slate-300/80 mt-2">اليوم</p>
                </div>
            </div>

            <div class="mt-6 rounded-[28px] border border-slate-200/70 bg-slate-50 p-5">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-sm font-semibold text-slate-500">تفاصيل الدوام</p>
                        <h2 class="mt-2 text-xl font-black text-slate-900">سجل الحضور الكامل</h2>
                    </div>
                    <span class="rounded-full bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700">{{ $logs->count() }} سجل</span>
                </div>
                <div class="mt-5 overflow-hidden rounded-[24px] border border-slate-200 bg-white">
                    <table class="min-w-full text-right text-sm text-slate-700">
                        <thead class="bg-slate-100 text-slate-500">
                            <tr>
                                <th class="px-4 py-4 font-semibold">التاريخ</th>
                                <th class="px-4 py-4 font-semibold">دخول</th>
                                <th class="px-4 py-4 font-semibold">خروج</th>
                                <th class="px-4 py-4 font-semibold">تأخير</th>
                                <th class="px-4 py-4 font-semibold">إضافية</th>
                                <th class="px-4 py-4 font-semibold">الحالة</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @forelse($logs as $log)
                                @php($statusClasses = [
                                    'present' => 'bg-emerald-50 text-emerald-700',
                                    'late' => 'bg-amber-50 text-amber-700',
                                    'absent' => 'bg-rose-50 text-rose-700',
                                ][$log->status] ?? 'bg-slate-50 text-slate-700')
                                <tr class="transition hover:bg-slate-50">
                                    <td class="px-4 py-4 font-semibold text-slate-900">{{ $log->log_date }}</td>
                                    <td class="px-4 py-4">{{ $log->check_in?->format('H:i') ?? '—' }}</td>
                                    <td class="px-4 py-4">{{ $log->check_out?->format('H:i') ?? '—' }}</td>
                                    <td class="px-4 py-4">{{ $log->late_minutes }} د</td>
                                    <td class="px-4 py-4">{{ $log->overtime_minutes }} د</td>
                                    <td class="px-4 py-4">
                                        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses }}">
                                            {{ match($log->status) { 'present' => 'حاضر', 'late' => 'متأخر', 'absent' => 'غائب' } }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-10 text-center text-slate-500">لا توجد سجلات دوام بعد.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $logs->links() }}</div>
            </div>
        </div>

        <aside class="space-y-4 rounded-[32px] bg-white border border-slate-200/70 p-6 shadow-sm">
            <div class="rounded-[28px] border border-slate-200/70 bg-slate-50 p-5">
                <p class="text-sm font-semibold text-slate-500">تسجيل حضور سريع</p>
                <p class="mt-3 text-sm text-slate-600">اضغط على الزر لتسجيل الحضور مباشرةً.</p>
                <form action="{{ route('attendance.checkin') }}" method="POST" class="mt-5">
                    @csrf
                    <button type="submit" class="w-full rounded-3xl bg-gradient-to-r from-cyan-500 to-blue-600 px-4 py-3 text-sm font-semibold text-white shadow-lg transition hover:opacity-95">
                        <i data-lucide="log-in" class="inline-block h-4 w-4 align-middle"></i>
                        <span class="mr-2">تسجيل الحضور</span>
                    </button>
                </form>
            </div>
            <div class="rounded-[28px] border border-slate-200/70 bg-slate-50 p-5">
                <p class="text-sm font-semibold text-slate-500">مرحلة الحضور اليوم</p>
                <div class="mt-4 space-y-3 text-sm text-slate-700">
                    <div class="flex items-center justify-between rounded-2xl bg-white p-3">
                        <span>حالة التسجيل</span>
                        <span class="font-bold">{{ $logs->count() > 0 ? 'مسجل' : 'غير مسجل' }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-2xl bg-white p-3">
                        <span>الموظف الحالي</span>
                        <span class="font-bold">{{ auth()->user()->name }}</span>
                    </div>
                </div>
            </div>
        </aside>
    </section>
</div>
@endsection
