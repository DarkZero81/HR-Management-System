@extends('layouts.app')

@section('title', 'وثائقي')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-xs font-black uppercase tracking-[0.35em] text-slate-400">الوثائق</p>
            <h1 class="text-2xl md:text-3xl font-black text-white mt-1">وثائقي الرسمية</h1>
            <p class="text-sm text-slate-400 mt-1">ارفع وأديروثائقك الشخصية وتابع صلاحياتها.</p>
        </div>
        <div class="shrink-0">
            <a href="{{ route('my.documents.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-500 to-teal-400 hover:from-blue-600 hover:to-teal-500 text-white rounded-xl font-bold shadow-lg transition-all">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>رفع وثيقة جديدة</span>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-4 py-3 text-sm font-semibold text-emerald-200">
            <i data-lucide="check-circle" class="w-5 h-5 inline-block mr-1"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-2xl border border-rose-400/20 bg-rose-500/10 px-4 py-3 text-sm font-semibold text-rose-200">
            <i data-lucide="alert-circle" class="w-5 h-5 inline-block mr-1"></i>
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl p-5 shadow-lg border border-slate-100 text-center">
            <p class="text-sm text-slate-500 mb-1">إجمالي الوثائق</p>
            <p class="text-3xl font-bold text-slate-800">{{ $documents->total() }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-lg border border-slate-100 text-center">
            <p class="text-sm text-slate-500 mb-1">سارية</p>
            <p class="text-3xl font-bold text-emerald-600">{{ $documents->where('expiry_date', '>', now())->count() }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-lg border border-slate-100 text-center">
            <p class="text-sm text-slate-500 mb-1">قريبة الانتهاء</p>
            <p class="text-3xl font-bold text-amber-600">{{ $documents->where('expiry_date', '<=', now()->addMonths(3))->where('expiry_date', '>', now())->count() }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">نوع الوثيقة</th>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">الرقم</th>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">تاريخ الانتهاء</th>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">الحالة</th>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($documents as $doc)
                        @php($isExpired = $doc->expiry_date < now())
                        @php($isExpiringSoon = $doc->expiry_date <= now()->addMonths(3) && !$isExpired)
                        <tr class="hover:bg-slate-50 transition-colors {{ $isExpired ? 'bg-rose-50/50' : ($isExpiringSoon ? 'bg-amber-50/50' : '') }}">
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-medium bg-slate-100 text-slate-700">
                                    <i data-lucide="{{ $doc->document_type == 'identity' ? 'credit-card' : ($doc->document_type == 'passport' ? 'book' : ($doc->document_type == 'contract' ? 'file-text' : 'stethoscope')) }}" class="w-4 h-4"></i>
                                    {{ ucfirst(str_replace('_', ' ', $doc->document_type)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-600 font-mono">{{ $doc->document_number }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $doc->expiry_date }}</td>
                            <td class="px-6 py-4">
                                @if($isExpired)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-700">
                                        منتهية الصلاحية
                                    </span>
                                @elseif($isExpiringSoon)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                        قريبة الانتهاء
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                        سارية
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    @if($doc->file_path)
                                        <a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-medium transition-colors" title="عرض">
                                            <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                                            عرض
                                        </a>
                                        <a href="{{ Storage::url($doc->file_path) }}" download class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-100 hover:bg-emerald-200 text-emerald-700 text-xs font-medium transition-colors" title="تحميل">
                                            <i data-lucide="download" class="w-3.5 h-3.5"></i>
                                            تحميل
                                        </a>
                                    @endif
                                    <form action="{{ route('my.documents.destroy', $doc->id) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه الوثيقة؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-red-100 text-slate-600 hover:text-red-600 text-xs font-medium transition-colors" title="حذف">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                            حذف
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <i data-lucide="file-text" class="w-12 h-12 text-slate-300"></i>
                                    <p class="text-slate-500">لم تقم برفع أي وثائق بعد</p>
                                    <a href="{{ route('my.documents.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-all">
                                        <i data-lucide="plus" class="w-4 h-4"></i>
                                        رفع وثيقة الآن
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