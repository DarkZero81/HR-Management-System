@extends('layouts.app')

@section('title', 'إدارة الورديات')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 rounded-[28px] border border-white/10 bg-slate-900/70 p-6 shadow-[0_20px_60px_-35px_rgba(15,23,42,0.45)] backdrop-blur lg:flex-row lg:items-center lg:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.35em] text-slate-400">الورديات</p>
            <h2 class="mt-2 text-3xl font-black text-white">قائمة الورديات</h2>
            <p class="mt-2 text-sm text-slate-400">إدارة الورديات ومواعيد العمل الرسمية للموظفين.</p>
        </div>
        <a href="{{ route('shifts.create') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-l from-cyan-500 to-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-lg transition hover:opacity-90">
            <i data-lucide="plus" class="h-4 w-4"></i>
            إضافة وردية جديدة
        </a>
    </div>

    <div class="overflow-hidden rounded-[28px] border border-white/10 bg-slate-900/60 shadow-[0_20px_60px_-35px_rgba(15,23,42,0.45)] backdrop-blur">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-white/10 text-right text-sm">
                <thead class="bg-slate-950/50 text-slate-300">
                    <tr>
                        <th class="px-5 py-4 font-semibold">اسم الوردية</th>
                        <th class="px-5 py-4 font-semibold">وقت البداية</th>
                        <th class="px-5 py-4 font-semibold">وقت النهاية</th>
                        <th class="px-5 py-4 font-semibold">فترة السماح (دقيقة)</th>
                        <th class="px-5 py-4 font-semibold">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10 bg-slate-900/70">
                    @forelse($shifts as $shift)
                        <tr class="transition hover:bg-slate-800/70">
                            <td class="px-5 py-4 font-semibold text-white">{{ $shift->shift_name }}</td>
                            <td class="px-5 py-4 text-slate-300">{{ $shift->start_time }}</td>
                            <td class="px-5 py-4 text-slate-300">{{ $shift->end_time }}</td>
                            <td class="px-5 py-4 text-slate-300">{{ $shift->grace_period_minutes }}</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('shifts.edit', $shift) }}" class="inline-flex items-center gap-1 rounded-full bg-cyan-500/10 px-3 py-1.5 text-sm font-semibold text-cyan-200 transition hover:bg-cyan-500/20">
                                        <i data-lucide="edit" class="h-3 w-3"></i>تعديل
                                    </a>
                                    <form action="{{ route('shifts.destroy', $shift) }}" method="POST">
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
                        <tr><td colspan="5" class="px-5 py-8 text-center text-slate-400">لا توجد ورديات مسجلة حتى الآن.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-white/10 px-6 py-4">{{ $shifts->links() }}</div>
    </div>
</div>
@endsection