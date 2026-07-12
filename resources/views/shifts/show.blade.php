@extends('layouts.app')
@section('title', $shift->shift_name)
@section('content')
<div class="space-y-6" dir="rtl">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-xs font-black uppercase tracking-[0.35em] text-slate-400">الورديات</p>
            <h1 class="text-3xl font-bold text-slate-800">{{ $shift->shift_name }}</h1>
            <div class="flex items-center gap-3 mt-2">
                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                    {{ $shift->start_time }} - {{ $shift->end_time }}
                </span>
                @if($shift->is_overnight)
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-violet-100 text-violet-700">تمتد عبر منتصف الليل</span>
                @endif
            </div>
            <p class="text-sm text-slate-500 mt-1">فترة السماح للتأخير: {{ $shift->grace_period_minutes }} دقيقة</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('shifts.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-white rounded-xl font-medium transition-all border border-white/10">
                <i data-lucide="arrow-right" class="w-4 h-4"></i>
                <span class="hidden sm:inline">العودة</span>
            </a>
            <a href="{{ route('shifts.edit', $shift) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-500 to-teal-400 hover:from-blue-600 hover:to-teal-500 text-white rounded-xl font-semibold shadow-lg transition-all">
                <i data-lucide="edit" class="w-4 h-4"></i>
                <span class="hidden sm:inline">تعديل الوردية</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-3 md:grid-cols-5 gap-4">
        <div class="employees-card rounded-2xl p-5">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center">
                    <i data-lucide="clock" class="w-6 h-6 text-blue-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-black text-slate-800">{{ $shift->employees_count }}</p>
                    <p class="text-xs text-slate-500">عدد الموظفين</p>
                </div>
            </div>
        </div>
        <div class="employees-card rounded-2xl p-5">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center">
                    <i data-lucide="timer" class="w-6 h-6 text-emerald-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-black text-slate-800">{{ $shift->grace_period_minutes }}</p>
                    <p class="text-xs text-slate-500">دقيقة سماح</p>
                </div>
            </div>
        </div>
    </div>

    <div class="employees-card rounded-2xl shadow-lg border overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h2 class="text-lg font-bold text-slate-800">الموظفون في هذه الوردية</h2>
            <p class="text-sm text-slate-500 mt-1">قائمة بجميع الموظفين المسجلين في هذه الوردية</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="employees-table-header">
                    <tr>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">الموظف</th>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">القسم</th>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">المسمى الوظيفي</th>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">تاريخ التعيين</th>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">الحالة</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($shift->employees as $employee)
                        <tr class="employees-table-row hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($employee->avatar)
                                        <img src="{{ asset('storage/' . $employee->avatar) }}" class="w-10 h-10 rounded-full object-cover border border-white/10 shadow-sm">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                                            {{ strtoupper(substr($employee->first_name ?? 'U', 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-semibold text-slate-800">{{ $employee->full_name }}</div>
                                        <div class="text-xs text-slate-500">{{ $employee->user?->email ?? 'لا يوجد حساب مرتبط' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-600 text-sm">{{ $employee->department?->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-slate-600 text-sm">{{ $employee->job_title ?? '-' }}</td>
                            <td class="px-6 py-4 text-slate-600 text-sm">{{ $employee->join_date?->format('Y-m-d') ?? '-' }}</td>
                            <td class="px-6 py-4">
                                @if($employee->resign_date)
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-rose-100 text-rose-700">منتهي الخدمة</span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">نشط</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center">
                                        <i data-lucide="users" class="w-8 h-8 text-slate-400"></i>
                                    </div>
                                    <p class="font-semibold text-slate-700">لا يوجد موظفون في هذه الوردية</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
