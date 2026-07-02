@extends('layouts.app')

@section('title', 'إدارة الموظفين')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 rounded-[28px] border border-slate-200/70 bg-white/80 p-6 shadow-[0_20px_60px_-35px_rgba(15,23,42,0.45)] backdrop-blur lg:flex-row lg:items-center lg:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.35em] text-slate-500">دليل الموظفين</p>
            <h2 class="mt-2 text-3xl font-black text-slate-900">قائمة شاملة للموظفين</h2>
            <p class="mt-2 text-sm text-slate-600">تصفح الملف الشخصي، الحالة الوظيفية، والراتب الأساسي بكل وضوح وسلاسة.</p>
        </div>
        <a href="{{ route('employees.create') }}" class="inline-flex items-center justify-center rounded-2xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">إضافة موظف جديد</a>
    </div>

    <div class="overflow-hidden rounded-[28px] border border-slate-200/70 bg-white/80 shadow-[0_20px_60px_-35px_rgba(15,23,42,0.45)] backdrop-blur">
        <div class="flex flex-col gap-4 border-b border-slate-200 px-6 py-5 md:flex-row md:items-center md:justify-between">
            <div>
                <h3 class="text-xl font-black text-slate-900">الأقسام التشغيلية</h3>
                <p class="text-sm text-slate-500">تصفّح الموظفين حسب الدور أو الوردية أو الراتب.</p>
            </div>
            <form method="GET" class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-3 py-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="بحث بالاسم أو الهوية" class="w-56 bg-transparent px-2 py-1 text-sm outline-none placeholder:text-slate-400 focus:ring-2 focus:ring-blue-500" />
                <button class="rounded-xl bg-blue-500 px-3 py-2 text-sm font-semibold text-white">بحث</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-right text-sm">
                <thead class="bg-slate-50 text-slate-600">
                    <tr>
                        <th class="px-5 py-4 font-semibold">الموظف</th>
                        <th class="px-5 py-4 font-semibold">الوردية</th>
                        <th class="px-5 py-4 font-semibold">الراتب الأساسي</th>
                        <th class="px-5 py-4 font-semibold">الحالة</th>
                        <th class="px-5 py-4 font-semibold">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($employees as $employee)
                        <tr class="transition hover:bg-slate-50">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-11 w-11 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 text-sm font-bold text-white">{{ strtoupper(substr($employee->first_name ?? 'E', 0, 1)) }}{{ strtoupper(substr($employee->last_name ?? 'M', 0, 1)) }}</div>
                                    <div>
                                        <p class="font-semibold text-slate-900">{{ $employee->full_name }}</p>
                                        <p class="text-xs text-slate-500">{{ $employee->national_id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-slate-600">{{ $employee->shift->shift_name ?? '—' }}</td>
                            <td class="px-5 py-4 font-semibold text-slate-900">{{ number_format((float) $employee->base_salary, 2) }} د.ع</td>
                            <td class="px-5 py-4"><span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">نشط</span></td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('employees.show', $employee) }}" class="rounded-full bg-slate-100 px-3 py-1.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-200">عرض</a>
                                    <a href="{{ route('employees.edit', $employee) }}" class="rounded-full bg-blue-50 px-3 py-1.5 text-sm font-semibold text-blue-700 transition hover:bg-blue-100">تعديل</a>
                                    <form action="{{ route('employees.destroy', $employee) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا الموظف؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-full bg-rose-50 px-3 py-1.5 text-sm font-semibold text-rose-700 transition hover:bg-rose-100">حذف</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-8 text-center text-slate-500">لا توجد موظفين مسجلين حتى الآن.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-200 px-6 py-4">{{ $employees->links() }}</div>
    </div>
</div>
@endsection
