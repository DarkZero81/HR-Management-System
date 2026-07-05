@extends('layouts.app')

@section('title', 'إدارة الموظفين')

@section('content')
<div class="space-y-6" dir="rtl">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-black text-white">الملفات الوظيفية</h1>
            <p class="text-sm text-slate-400">إدارة بيانات الموظفين، الرواتب، والأقسام المسجلة في النظام.</p>
        </div>
        <a href="{{ route('employees.create') }}" class="inline-flex items-center justify-center rounded-2xl bg-blue-600 px-4 py-2.5 text-sm font-bold text-white shadow-lg shadow-blue-600/20 transition hover:bg-blue-500">
            إضافة موظف جديد +
        </a>
    </div>

    @if(session('success'))
        <div class="rounded-2xl border border-emerald-500/20 bg-emerald-500/10 p-4 text-sm font-medium text-emerald-400">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-2xl border border-rose-500/20 bg-rose-500/10 p-4 text-sm font-medium text-rose-400">
            {{ session('error') }}
        </div>
    @endif

    <div class="rounded-[24px] border border-white/10 bg-slate-900/50 p-5">
        <form action="{{ route('employees.index') }}" method="GET" class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">بحث نصي</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="الاسم، الكنية، أو الرقم الوطني..." class="w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-2.5 text-sm text-white focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">تصفية حسب القسم</label>
                <select name="department_id" class="w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-2.5 text-sm text-white focus:border-blue-500 focus:outline-none">
                    <option value="">كل الأقسام</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="w-full rounded-xl bg-slate-800 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-slate-700">
                    تطبيق الفلترة
                </button>
                @if(request()->has('search') || request()->has('department_id'))
                    <a href="{{ route('employees.index') }}" class="rounded-xl bg-slate-950 border border-white/10 px-4 py-2.5 text-sm font-bold text-slate-400 transition hover:text-white">
                        إعادة
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="overflow-hidden rounded-[24px] border border-white/10 bg-slate-950/40 shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-right text-sm">
                <thead class="bg-slate-900/80 text-xs font-bold uppercase tracking-wider text-slate-400">
                    <tr>
                        <th class="px-6 py-4">الموظف</th>
                        <th class="px-6 py-4">الرقم الوطني</th>
                        <th class="px-6 py-4">القسم والوردية</th>
                        <th class="px-6 py-4">الراتب الأساسي</th>
                        <th class="px-6 py-4">رصيد الإجازات</th>
                        <th class="px-6 py-4">تاريخ التعيين</th>
                        <th class="px-6 py-4 text-left">العمليات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 text-slate-300">
                    @forelse($employees as $employee)
                        <tr class="transition hover:bg-white/[0.02]">
                            <td class="px-6 py-4">
                                <div class="font-bold text-white">{{ $employee->full_name }}</div>
                                <div class="text-xs text-slate-500">{{ $employee->user?->email ?? 'لا يوجد حساب مرتبط' }}</div>
                            </td>
                            <td class="px-6 py-4 font-mono text-xs">{{ $employee->national_id }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-block rounded-md bg-blue-500/10 px-2 py-0.5 text-xs text-blue-400 mb-1">
                                    {{ $employee->department?->name ?? 'غير معين' }}
                                </span>
                                <div class="text-xs text-slate-500">{{ $employee->shift?->shift_name ?? 'بدون وردية' }}</div>
                            </td>
                            <td class="px-6 py-4 font-bold text-emerald-400">{{ number_format($employee->base_salary, 2) }} د.أ</td>
                            <td class="px-6 py-4 font-bold">{{ $employee->vacation_balance }} يوم</td>
                            <td class="px-6 py-4 text-xs">{{ $employee->join_date->format('Y-m-d') }}</td>
                            <td class="px-6 py-4 text-left">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('employees.edit', $employee->id) }}" class="rounded-lg bg-slate-800 px-3 py-1.5 text-xs font-bold text-slate-300 transition hover:bg-slate-700 hover:text-white">
                                        تعديل
                                    </a>
                                    <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد تماماً من رغبتك في حذف ملف هذا الموظف؟ لا يمكن التراجع!');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg bg-rose-500/10 px-3 py-1.5 text-xs font-bold text-rose-400 transition hover:bg-rose-500 hover:text-white">
                                            حذف
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-sm text-slate-500">
                                لا يوجد موظفون مطابقون لخيارات البحث حالياً.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($employees->hasPages())
            <div class="border-t border-white/5 bg-slate-900/40 p-4">
                {{ $employees->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
