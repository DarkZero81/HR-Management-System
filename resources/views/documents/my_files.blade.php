@extends('layouts.app')

@section('title', 'ملفاتي')

@section('content')
<div class="space-y-6" dir="rtl">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-xs font-black uppercase tracking-[0.35em] text-slate-400">ملفاتي</p>
            <h1 class="text-3xl font-bold text-slate-800">ملفاتي الشخصية</h1>
            <p class="text-sm text-slate-500 mt-1">عرض الملفات التي قمت برفعها مع إمكانية فتحها مباشرة.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('my.documents') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-white rounded-xl font-medium transition-all border border-white/10">
                <i data-lucide="file-text" class="w-4 h-4"></i>
                <span class="hidden sm:inline">وثائقي</span>
            </a>
            <a href="{{ route('my.documents.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-500 to-teal-400 hover:from-blue-600 hover:to-teal-500 text-white rounded-xl font-semibold shadow-lg transition-all">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span class="hidden sm:inline">رفع ملف</span>
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

    <div class="employees-card rounded-2xl shadow-lg border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="employees-table-header">
                    <tr>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">#</th>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">نوع الملف</th>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">الرقم</th>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">تاريخ الانتهاء</th>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">تاريخ الرفع</th>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($documents as $doc)
                        @php
                            $ext = strtoupper(pathinfo($doc->file_path ?? '', PATHINFO_EXTENSION));
                            $isExpired = $doc->expiry_date && \Carbon\Carbon::parse($doc->expiry_date)->lt(now());
                            $isExpiring = !$isExpired && $doc->expiry_date && \Carbon\Carbon::parse($doc->expiry_date)->lte(now()->addMonths(3));
                        @endphp
                        <tr class="employees-table-row hover:bg-slate-50 transition-colors {{ $isExpired ? 'bg-rose-50/50' : '' }} {{ $isExpiring ? 'bg-amber-50/50' : '' }}">
                            <td class="px-6 py-4 text-sm text-slate-500">{{ $documents->firstItem() + $loop->index }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                    {{ ucfirst(str_replace('_', ' ', $doc->document_type)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-600 font-mono text-sm">{{ $doc->document_number }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium {{ $isExpired ? 'bg-rose-100 text-rose-700' : ($isExpiring ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700') }}">
                                    {{ $doc->expiry_date }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-600 text-sm">{{ $doc->created_at?->format('Y-m-d') ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    @if($doc->file_path)
                                        <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank"
                                            class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-medium transition-colors"
                                            title="عرض/تحميل">
                                            <i data-lucide="download" class="w-3.5 h-3.5"></i>
                                        </a>
                                    @endif
                                    <a href="{{ route('documents.edit', $doc->id) }}"
                                        class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-medium transition-colors"
                                        title="تعديل">
                                        <i data-lucide="edit" class="w-3.5 h-3.5"></i>
                                    </a>
                                    <form action="{{ route('my.documents.destroy', $doc->id) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه الوثيقة؟ لا يمكن التراجع!')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-slate-100 hover:bg-red-100 text-slate-600 hover:text-red-600 text-xs font-medium transition-colors"
                                            title="حذف">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center">
                                        <i data-lucide="file-text" class="w-8 h-8 text-slate-400"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-700">لا توجد ملفات مرفوعة حتى الآن</p>
                                        <p class="text-sm text-slate-500 mt-1">ابدأ برفع ملفاتك الرسمية للحفاظ على تنظيم سجلاتك</p>
                                    </div>
                                    <a href="{{ route('my.documents.create') }}" class="mt-2 inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-500 to-teal-400 hover:from-blue-600 hover:to-teal-500 text-white rounded-xl text-sm font-semibold shadow-lg transition-all">
                                        <i data-lucide="plus" class="w-4 h-4"></i>
                                        رفع ملف جديد
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($documents->hasPages())
            <div class="border-t border-slate-100 bg-slate-50 px-6 py-4">
                {{ $documents->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
