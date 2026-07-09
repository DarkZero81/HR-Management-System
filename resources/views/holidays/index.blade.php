@extends('layouts.app')

@section('title', 'إدارة الإجازات')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-xs font-black uppercase tracking-[0.35em] text-slate-400">الإجازات</p>
            <h1 class="text-3xl font-bold text-slate-800">قائمة الإجازات</h1>
            <p class="text-sm text-slate-400 mt-1">إدارة الإجازات الرسمية والتقويم السنوي.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('holidays.calendar') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-white rounded-xl font-medium transition-all border border-white/10">
                <i data-lucide="calendar" class="w-4 h-4"></i>
                <span class="hidden sm:inline">التقويم</span>
            </a>
            <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-white rounded-xl font-medium transition-all border border-white/10">
                <i data-lucide="user" class="w-4 h-4"></i>
                <span class="hidden sm:inline">الملف الشخصي</span>
            </a>
            <a href="{{ route('holidays.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-500 to-teal-400 hover:from-blue-600 hover:to-teal-500 text-white rounded-xl font-semibold shadow-lg transition-all">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span class="hidden sm:inline">إضافة إجازة</span>
            </a>
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

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="employee-form-card rounded-2xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                    <i data-lucide="calendar-days" class="w-5 h-5 text-blue-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-black text-slate-800">{{ $holidays->count() }}</p>
                    <p class="text-xs text-slate-500">إجازات مسجلة</p>
                </div>
            </div>
        </div>
        <div class="employee-form-card rounded-2xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                    <i data-lucide="repeat" class="w-5 h-5 text-emerald-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-black text-slate-800">{{ $holidays->where('is_recurring', true)->count() }}</p>
                    <p class="text-xs text-slate-500">متكررة سنوياً</p>
                </div>
            </div>
        </div>
        <div class="employee-form-card rounded-2xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-violet-100 flex items-center justify-center">
                    <i data-lucide="clock" class="w-5 h-5 text-violet-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-black text-slate-800">{{ \App\Models\Holiday::where('start_date', '>=', now())->count() }}</p>
                    <p class="text-xs text-slate-500">قادمة</p>
                </div>
            </div>
        </div>
        <div class="employee-form-card rounded-2xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center">
                    <i data-lucide="sun" class="w-5 h-5 text-amber-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-black text-slate-800">{{ now()->diffInDays(\Carbon\Carbon::create(now()->year, 12, 31)) }}</p>
                    <p class="text-xs text-slate-500">يوم لنهاية السنة</p>
                </div>
            </div>
        </div>
        <div class="employee-form-card rounded-2xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-cyan-100 flex items-center justify-center">
                    <i data-lucide="party-popper" class="w-5 h-5 text-cyan-600"></i>
                </div>
                <div>
                    @php
                        $nextHoliday = \App\Models\Holiday::where('start_date', '>=', now())->orderBy('start_date')->first();
                        $daysToNextHoliday = $nextHoliday ? now()->diffInDays($nextHoliday->start_date) : 0;
                    @endphp
                    <p class="text-2xl font-black text-slate-800">{{ $daysToNextHoliday }}</p>
                    <p class="text-xs text-slate-500">يوم للعطلة القادمة</p>
                </div>
            </div>
        </div>
        <div class="employee-form-card rounded-2xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-rose-100 flex items-center justify-center">
                    <i data-lucide="briefcase" class="w-5 h-5 text-rose-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-black text-slate-800">{{ now()->diffInDays(\Carbon\Carbon::create(now()->year, 12, 31)) - $holidays->count() }}</p>
                    <p class="text-xs text-slate-500">أيام دوام متبقية</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">اسم الإجازة</th>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">تاريخ البداية</th>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">تاريخ النهاية</th>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">متكررة</th>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($holidays as $holiday)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-slate-800 font-semibold">{{ $holiday->holiday_name }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $holiday->start_date?->format('Y-m-d') ?? '—' }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $holiday->end_date?->format('Y-m-d') ?? '—' }}</td>
                            <td class="px-6 py-4">
                                @if($holiday->is_recurring)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">نعم</span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600">لا</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('holidays.edit', $holiday) }}" class="p-2 rounded-xl bg-slate-100 hover:bg-blue-100 text-slate-600 hover:text-blue-600 transition-colors" title="تعديل">
                                        <i data-lucide="edit" class="w-4 h-4"></i>
                                    </a>
                                    <form action="{{ route('holidays.destroy', $holiday) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه الإجازة؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-xl bg-slate-100 hover:bg-red-100 text-slate-600 hover:text-red-600 transition-colors" title="حذف">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-slate-500">لا توجد إجازات مسجلة حتى الآن.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-100 bg-slate-50 px-6 py-4">
            {{ $holidays->links() }}
        </div>
    </div>
</div>
@endsection
