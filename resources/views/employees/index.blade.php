@extends('layouts.app')

@section('title', 'إدارة الموظفين')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 rounded-[28px] border border-white/10 bg-slate-900/70 p-6 shadow-[0_20px_60px_-35px_rgba(15,23,42,0.45)] backdrop-blur lg:flex-row lg:items-center lg:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.35em] text-slate-400">دليل الموظفين</p>
            <h2 class="mt-2 text-3xl font-black text-white">قائمة شاملة للموظفين</h2>
            <p class="mt-2 text-sm text-slate-400">تصفح الملف الشخصي، الحالة الوظيفية، والراتب الأساسي بكل وضوح وسلاسة.</p>
        </div>
        <a href="{{ route('employees.create') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-l from-cyan-500 to-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-lg transition hover:opacity-90">
            <i data-lucide="user-plus" class="h-4 w-4"></i>
            إضافة موظف جديد
        </a>
    </div>

    <div class="overflow-hidden rounded-[28px] border border-white/10 bg-slate-900/60 shadow-[0_20px_60px_-35px_rgba(15,23,42,0.45)] backdrop-blur">
        <div class="flex flex-col gap-4 border-b border-white/10 px-6 py-5 md:flex-row md:items-center md:justify-between">
            <div>
                <h3 class="text-xl font-black text-white">الأقسام التشغيلية</h3>
                <p class="text-sm text-slate-400">تصفّح الموظفين حسب الدور أو الوردية أو الراتب.</p>
            </div>
            <form method="GET" class="flex items-center gap-3 rounded-2xl border border-white/10 bg-slate-950/50 px-3 py-2">
                <i data-lucide="search" class="h-4 w-4 text-slate-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="بحث بالاسم أو الهوية" class="w-56 bg-transparent px-2 py-1 text-sm outline-none placeholder:text-slate-500 focus:ring-2 focus:ring-cyan-500" />
                <button class="rounded-xl bg-gradient-to-l from-cyan-500 to-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-md">بحث</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-white/10 text-right text-sm">
                <thead class="bg-slate-950/50 text-slate-300">
                    <tr>
                        <th class="px-5 py-4 font-semibold">الموظف</th>
                        <th class="px-5 py-4 font-semibold">البريد</th>
                        <th class="px-5 py-4 font-semibold">الهاتف</th>
                        <th class="px-5 py-4 font-semibold">الوردية</th>
                        <th class="px-5 py-4 font-semibold">الراتب الأساسي</th>
                        <th class="px-5 py-4 font-semibold">الحالة</th>
                        <th class="px-5 py-4 font-semibold">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10 bg-slate-900/70">
                    @forelse($employees as $employee)
                        <tr class="transition hover:bg-slate-800/70">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-11 w-11 items-center justify-center rounded-full bg-gradient-to-br from-cyan-500 to-blue-600 text-sm font-bold text-white">{{ strtoupper(substr($employee->first_name ?? 'E', 0, 1)) }}{{ strtoupper(substr($employee->last_name ?? 'M', 0, 1)) }}</div>
                                    <div>
                                        <p class="font-semibold text-white">{{ $employee->full_name }}</p>
                                        <p class="text-xs text-slate-400">{{ $employee->national_id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-slate-300">{{ $employee->user->email ?? '—' }}</td>
                            <td class="px-5 py-4 text-slate-300">{{ $employee->phone ?? '—' }}</td>
                            <td class="px-5 py-4 text-slate-300">{{ $employee->shift->shift_name ?? '—' }}</td>
                            <td class="px-5 py-4 font-semibold text-white">{{ number_format((float) $employee->base_salary, 2) }} د.ع</td>
                            <td class="px-5 py-4"><span class="rounded-full bg-emerald-500/15 px-3 py-1 text-xs font-semibold text-emerald-300">نشط</span></td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('employees.show', $employee) }}" class="inline-flex items-center gap-1 rounded-full bg-white/10 px-3 py-1.5 text-sm font-semibold text-slate-200 transition hover:bg-white/20">
                                        <i data-lucide="eye" class="h-3 w-3"></i>عرض
                                    </a>
                                    <a href="{{ route('employees.edit', $employee) }}" class="inline-flex items-center gap-1 rounded-full bg-cyan-500/10 px-3 py-1.5 text-sm font-semibold text-cyan-200 transition hover:bg-cyan-500/20">
                                        <i data-lucide="edit" class="h-3 w-3"></i>تعديل
                                    </a>
                                    <form action="{{ route('employees.destroy', $employee) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا الموظف؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-1 rounded-full bg-rose-500/10 px-3 py-1.5 text-sm font-semibold text-rose-200 transition hover:bg-rose-500/20">
                                            <i data-lucide="trash-2" class="h-3 w-3"></i>حذف
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-5 py-8 text-center text-slate-400">لا توجد موظفين مسجلين حتى الآن.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-white/10 px-6 py-4">{{ $employees->links() }}</div>
    </div>
</div>
@endsection
