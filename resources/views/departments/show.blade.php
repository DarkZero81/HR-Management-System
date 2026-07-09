@extends('layouts.app')
@section('title', $department->name)
@section('content')
<div class="space-y-6" dir="rtl">
    {{-- Header --}}
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-xs font-black uppercase tracking-[0.35em] text-slate-400">الأقسام</p>
            <h1 class="text-3xl font-bold text-slate-800">{{ $department->name }}</h1>
            @if($department->description)
                <p class="text-sm text-slate-500 mt-1 max-w-2xl">{{ $department->description }}</p>
            @endif
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('departments.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-white rounded-xl font-medium transition-all border border-white/10">
                <i data-lucide="arrow-right" class="w-4 h-4"></i>
                <span class="hidden sm:inline">العودة</span>
            </a>
            <a href="{{ route('departments.edit', $department->id) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-500 to-teal-400 hover:from-blue-600 hover:to-teal-500 text-white rounded-xl font-semibold shadow-lg transition-all">
                <i data-lucide="edit" class="w-4 h-4"></i>
                <span class="hidden sm:inline">تعديل القسم</span>
            </a>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-3 md:grid-cols-5 gap-4">
        <div class="employees-card rounded-2xl p-5">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center">
                    <i data-lucide="users" class="w-6 h-6 text-blue-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-black text-slate-800">{{ $stats['total'] }}</p>
                    <p class="text-xs text-slate-500">إجمالي الموظفين</p>
                </div>
            </div>
        </div>
        <div class="employees-card rounded-2xl p-5">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center">
                    <i data-lucide="user-check" class="w-6 h-6 text-emerald-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-black text-slate-800">{{ $stats['active'] }}</p>
                    <p class="text-xs text-slate-500">نشط</p>
                </div>
            </div>
        </div>
        <div class="employees-card rounded-2xl p-5">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-rose-100 flex items-center justify-center">
                    <i data-lucide="user-x" class="w-6 h-6 text-rose-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-black text-slate-800">{{ $stats['resigned'] }}</p>
                    <p class="text-xs text-slate-500">منتهي الخدمة</p>
                </div>
            </div>
        </div>
        <div class="employees-card rounded-2xl p-5">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center">
                    <i data-lucide="wallet" class="w-6 h-6 text-amber-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-black text-slate-800">{{ number_format($stats['total_salary'], 2) }}</p>
                    <p class="text-xs text-slate-500">إجمالي الرواتب (ل.س)</p>
                </div>
            </div>
        </div>
        <div class="employees-card rounded-2xl p-5">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-violet-100 flex items-center justify-center">
                    <i data-lucide="trending-up" class="w-6 h-6 text-violet-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-black text-slate-800">{{ number_format($stats['avg_performance'] ?? 0, 1) }}</p>
                    <p class="text-xs text-slate-500">متوسط الأداء</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Employees Table --}}
    <div class="employees-card rounded-2xl shadow-lg border overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-slate-800">الموظفون في القسم</h2>
                <p class="text-sm text-slate-500 mt-1">قائمة بجميع الموظفين التابعين لهذا القسم</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="relative">
                    <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute right-3 top-1/2 -translate-y-1/2"></i>
                    <input type="text" id="employeeSearch" placeholder="بحث عن موظف..."
                        class="employees-card-input pr-10 pl-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all w-full sm:w-64">
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full" id="employeesTable">
                <thead class="employees-table-header">
                    <tr>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">الموظف</th>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">المسمى الوظيفي</th>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">الهاتف</th>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">الراتب</th>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">تاريخ التعيين</th>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">الحالة</th>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($employees as $employee)
                        <tr class="employees-table-row hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($employee->avatar)
                                        <img src="{{ asset('storage/' . $employee->avatar) }}"
                                            class="w-10 h-10 rounded-full object-cover border border-white/10 shadow-sm">
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
                            <td class="px-6 py-4 text-slate-600 text-sm">{{ $employee->job_title ?? '-' }}</td>
                            <td class="px-6 py-4 text-slate-600 text-sm" dir="ltr">{{ $employee->phone ?? '-' }}</td>
                            <td class="px-6 py-4 text-slate-800 font-semibold">{{ number_format($employee->base_salary, 2) }} ل.س</td>
                            <td class="px-6 py-4 text-slate-600 text-sm">{{ $employee->join_date?->format('Y-m-d') ?? '-' }}</td>
                            <td class="px-6 py-4">
                                @if($employee->resign_date)
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-rose-100 text-rose-700">منتهي الخدمة</span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">نشط</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('employees.show', $employee->id) }}"
                                        class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-cyan-50 hover:bg-cyan-100 text-cyan-700 text-xs font-medium transition-colors"
                                        title="عرض">
                                        <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                    </a>
                                    <a href="{{ route('employees.edit', $employee->id) }}"
                                        class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-medium transition-colors"
                                        title="تعديل">
                                        <i data-lucide="edit" class="w-3.5 h-3.5"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center">
                                        <i data-lucide="users" class="w-8 h-8 text-slate-400"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-700">لا يوجد موظفون في هذا القسم</p>
                                        <p class="text-sm text-slate-500 mt-1">يمكنك إضافة موظفين جدد من صفحة إدارة الموظفين</p>
                                    </div>
                                    <a href="{{ route('employees.create') }}" class="mt-2 inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-500 to-teal-400 hover:from-blue-600 hover:to-teal-500 text-white rounded-xl text-sm font-semibold shadow-lg transition-all">
                                        <i data-lucide="plus" class="w-4 h-4"></i>
                                        إضافة موظف
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('employeeSearch');
    const table = document.getElementById('employeesTable');

    if (searchInput && table) {
        searchInput.addEventListener('input', function() {
            const term = this.value.trim().toLowerCase();
            const rows = table.querySelectorAll('tbody tr');

            rows.forEach(function(row) {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(term) ? '' : 'none';
            });
        });
    }
});
</script>
@endsection
