@extends('layouts.app')

@section('title', 'إدارة الورديات')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-xs font-black uppercase tracking-[0.35em] text-slate-400">الورديات</p>
            <h1 class="text-3xl font-bold text-slate-800">قائمة الورديات</h1>
            <p class="text-sm text-slate-400 mt-1">إدارة الورديات ومواعيد العمل الرسمية للموظفين.</p>
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

    <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">اسم الوردية</th>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">وقت البداية</th>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">وقت النهاية</th>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">فترة السماح</th>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($shifts as $shift)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-slate-800 font-semibold">{{ $shift->shift_name }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $shift->start_time }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $shift->end_time }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $shift->grace_period_minutes }} دقيقة</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('shifts.edit', $shift) }}" class="p-2 rounded-xl bg-slate-100 hover:bg-blue-100 text-slate-600 hover:text-blue-600 transition-colors" title="تعديل">
                                        <i data-lucide="edit" class="w-4 h-4"></i>
                                    </a>
                                    <form action="{{ route('shifts.destroy', $shift) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه الوردية؟')">
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
                            <td colspan="5" class="px-6 py-10 text-center text-slate-500">لا توجد ورديات مسجلة حتى الآن.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-100 bg-slate-50 px-6 py-4">
            {{ $shifts->links() }}
        </div>
    </div>
</div>
@endsection
