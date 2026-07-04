@extends('layouts.app')

@section('title', 'أجهزة الحضور')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 rounded-[28px] border border-white/10 bg-slate-900/70 p-6 shadow-[0_20px_60px_-35px_rgba(15,23,42,0.45)] backdrop-blur lg:flex-row lg:items-center lg:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.35em] text-slate-400">الأجهزة</p>
            <h2 class="mt-2 text-3xl font-black text-white">أجهزة البصمة والحضور</h2>
            <p class="mt-2 text-sm text-slate-400">إدارة أجهزة تسجيل الحضور المرتبطة بالنظام.</p>
        </div>
        {{-- TODO: إضافة صفحة إنشاء جهاز جديد --}}
        <span class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-l from-cyan-500 to-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-lg opacity-75">
            <i data-lucide="plus" class="h-4 w-4"></i>
            إضافة جهاز (قريباً)
        </span>
    </div>

    <div class="overflow-hidden rounded-[28px] border border-white/10 bg-slate-900/60 shadow-[0_20px_60px_-35px_rgba(15,23,42,0.45)] backdrop-blur">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-white/10 text-right text-sm">
                <thead class="bg-slate-950/50 text-slate-300">
                    <tr>
                        <th class="px-5 py-4 font-semibold">اسم الجهاز</th>
                        <th class="px-5 py-4 font-semibold">عنوان IP</th>
                        <th class="px-5 py-4 font-semibold">الحالة</th>
                        <th class="px-5 py-4 font-semibold">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10 bg-slate-900/70">
                    @forelse($devices as $device)
                        <tr class="transition hover:bg-slate-800/70">
                            <td class="px-5 py-4 font-semibold text-white">{{ $device->device_name }}</td>
                            <td class="px-5 py-4 text-slate-300">{{ $device->ip_address ?? '—' }}</td>
                            <td class="px-5 py-4">
                                <span class="rounded-full {{ $device->status === 'active' ? 'bg-emerald-500/15 text-emerald-300' : 'bg-rose-500/15 text-rose-300' }} px-3 py-1 text-xs font-semibold">
                                    {{ $device->status === 'active' ? 'نشط' : 'متوقف' }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                {{-- TODO: أزرار تعديل/حذف تتطلب إضافة DeviceWebController CRUD --}}
                                <span class="text-xs text-slate-500">إجراءات غير متاحة حالياً</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-5 py-8 text-center text-slate-400">لا توجد أجهزة مسجلة حتى الآن.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-white/10 px-6 py-4">{{ $devices->links() }}</div>
    </div>
</div>
@endsection
