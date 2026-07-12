@extends('layouts.app')

@section('title', 'إدارة الورديات')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-xs font-black uppercase tracking-[0.35em] text-slate-400">الورديات</p>
            <h1 class="text-3xl font-bold text-slate-800">إدارة الورديات ومواعيد العمل</h1>
            <p class="text-sm text-slate-400 mt-1">تنظيم جداول العمل والورديات للموظفين.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-white rounded-xl font-medium transition-all border border-white/10">
                <i data-lucide="user" class="w-4 h-4"></i>
                <span class="hidden sm:inline">الملف الشخصي</span>
            </a>
            <a href="{{ route('shifts.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-500 to-teal-400 hover:from-blue-600 hover:to-teal-500 text-white rounded-xl font-semibold shadow-lg transition-all">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span class="hidden sm:inline">إضافة وردية</span>
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

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">
        <div class="employee-form-card rounded-2xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                    <i data-lucide="clock" class="w-5 h-5 text-blue-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-black text-slate-800">{{ $stats['total'] }}</p>
                    <p class="text-xs text-slate-500">عدد الورديات</p>
                </div>
            </div>
        </div>

        <div class="employee-form-card rounded-2xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                    <i data-lucide="users" class="w-5 h-5 text-emerald-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-black text-slate-800">{{ $stats['total_employees'] }}</p>
                    <p class="text-xs text-slate-500">إجمالي الموظفين</p>
                </div>
            </div>
        </div>

        <div class="employee-form-card rounded-2xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-violet-100 flex items-center justify-center">
                    <i data-lucide="trending-up" class="w-5 h-5 text-violet-600"></i>
                </div>
                <div>
                    @php
                        $avg = $stats['avg_employees'] ?? 0;
                    @endphp
                    <p class="text-2xl font-black text-slate-800">{{ $avg > 0 ? round($avg, 1) : '-' }}</p>
                    <p class="text-xs text-slate-500">متوسط الموظفين/وردية</p>
                </div>
            </div>
        </div>

        <div class="employee-form-card rounded-2xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center">
                    <i data-lucide="timer" class="w-5 h-5 text-amber-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-black text-slate-800">{{ $stats['max_employees_shift'] > 0 ? $stats['max_employees_shift'] : '-' }}</p>
                    <p class="text-xs text-slate-500">{{ $stats['max_employees_shift_name'] ?? 'أكبر وردية موظفين' }}</p>
                </div>
            </div>
        </div>

        <div class="employee-form-card rounded-2xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-rose-100 flex items-center justify-center">
                    <i data-lucide="user-x" class="w-5 h-5 text-rose-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-black text-slate-800">{{ $stats['empty_shifts'] }}</p>
                    <p class="text-xs text-slate-500">ورديات بدون موظفين</p>
                </div>
            </div>
        </div>

        <div class="employee-form-card rounded-2xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-cyan-100 flex items-center justify-center">
                    <i data-lucide="moon" class="w-5 h-5 text-cyan-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-black text-slate-800">{{ $stats['overnight_shifts'] }}</p>
                    <p class="text-xs text-slate-500">ورديات ليلية</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-slate-800">قائمة الورديات</h2>
                <p class="text-sm text-slate-500 mt-1">عرض وإدارة جميع الورديات</p>
            </div>
            <form method="GET" action="{{ route('shifts.index') }}" class="relative">
                <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute right-3 top-1/2 -translate-y-1/2"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="بحث عن وردية..."
                    class="employees-card-input pr-10 pl-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all w-full sm:w-64">
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">#</th>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">اسم الوردية</th>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">وقت البداية</th>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">وقت النهاية</th>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">فترة السماح</th>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">عدد الموظفين</th>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($shifts as $shift)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-slate-600">{{ $loop->iteration + ($shifts->currentPage() - 1) * $shifts->perPage() }}</td>
                            <td class="px-6 py-4 text-slate-800 font-semibold">{{ $shift->shift_name }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $shift->start_time }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $shift->end_time }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $shift->grace_period_minutes }} دقيقة</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                    {{ $shift->employees_count ?? 0 }} موظف
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('shifts.show', $shift) }}" class="p-2 rounded-xl bg-slate-100 hover:bg-blue-100 text-slate-600 hover:text-blue-600 transition-colors" title="عرض التفاصيل">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    <a href="{{ route('shifts.edit', $shift) }}" class="p-2 rounded-xl bg-slate-100 hover:bg-blue-100 text-slate-600 hover:text-blue-600 transition-colors" title="تعديل">
                                        <i data-lucide="edit" class="w-4 h-4"></i>
                                    </a>
                                    <form action="{{ route('shifts.destroy', $shift) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه الوردية؟{{ $shift->employees_count > 0 ? ' سيؤدي إلى إزالة ارتباط ' . $shift->employees_count . ' موظف.' : '' }}')">
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
                            <td colspan="7" class="px-6 py-10 text-center text-slate-500">لا توجد ورديات مسجلة حتى الآن.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $shifts->links() }}
        </div>
    </div>
</div>
@endsection
