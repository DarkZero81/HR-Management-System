@extends('layouts.app')

@section('title', 'تفاصيل الوثيقة')

@section('content')
<div class="max-w-3xl mx-auto space-y-6 px-4 py-4" dir="rtl">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-xs font-black uppercase tracking-[0.35em] text-slate-400">الوثائق</p>
            <h1 class="text-3xl font-bold text-slate-800">تفاصيل الوثيقة</h1>
        </div>
        <a href="{{ url()->previous() === url()->current() ? route('my.documents.index') : url()->previous() }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-500 rounded-xl font-semibold transition-all">
            <i data-lucide="arrow-right" class="w-4 h-4"></i>
            رجوع
        </a>
    </div>

    @php
        $user = auth()->user();
        $isAdmin = in_array(optional($user->role)->role_name, ['admin', 'manager'], true);
        $isOwner = $document->employee->user_id === $user->id;
        $canEdit = $isAdmin || $isOwner;
        $isExpired = $document->expiry_date && \Carbon\Carbon::parse($document->expiry_date)->lt(now());
        $isExpiring = !$isExpired && $document->expiry_date && \Carbon\Carbon::parse($document->expiry_date)->lte(now()->addMonths(3));
    @endphp

    <div class="employee-form-card rounded-[28px] border border-white/10 dark:border-white/5 p-6 space-y-6 shadow-2xl backdrop-blur-md">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <p class="text-xs font-bold text-slate-500 mb-1">نوع الوثيقة</p>
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-medium bg-blue-100 text-blue-700">
                    {{ ucfirst(str_replace('_', ' ', $document->document_type)) }}
                </span>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 mb-1">رقم الوثيقة</p>
                <p class="text-sm font-mono font-semibold text-slate-800">{{ $document->document_number }}</p>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 mb-1">تاريخ الانتهاء</p>
                <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-sm font-medium {{ $isExpired ? 'bg-rose-100 text-rose-700' : ($isExpiring ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700') }}">
                    {{ \Carbon\Carbon::parse($document->expiry_date)->format('Y-m-d') }}
                    @if($isExpired)
                        (منتهية)
                    @elseif($isExpiring)
                        (قريبة الانتهاء)
                    @else
                        (سارية)
                    @endif
                </span>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 mb-1">تاريخ الرفع</p>
                <p class="text-sm font-semibold text-slate-800">
                    {{ ($document->created_at && $document->created_at !== '0000-00-00 00:00:00') ? \Carbon\Carbon::parse($document->created_at)->format('Y-m-d') : '-' }}
                </p>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 mb-1">صاحب الوثيقة</p>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center text-white font-bold text-xs">
                        {{ strtoupper(substr($document->employee->first_name ?? 'U', 0, 1)) }}
                    </div>
                    <span class="text-sm font-semibold text-slate-800">{{ $document->employee->full_name ?? ($document->employee->first_name ?? '-') }}</span>
                </div>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 mb-1">البريد الإلكتروني</p>
                <p class="text-sm text-slate-700">{{ $document->employee->user->email ?? '-' }}</p>
            </div>
        </div>

        @if($document->file_path)
            <div class="border-t border-slate-200 dark:border-white/5 pt-6">
                <p class="text-xs font-bold text-slate-500 mb-3">الملف المرفق</p>
                <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank"
                   class="inline-flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-600 hover:to-blue-700 text-white rounded-xl font-semibold shadow-lg transition-all">
                    <i data-lucide="download" class="w-5 h-5"></i>
                    عرض / تحميل الملف
                </a>
            </div>
        @endif

        @if($canEdit)
            <div class="flex items-center justify-end gap-2.5 border-t border-slate-200 dark:border-white/5 pt-4">
                <a href="{{ route('my.documents.edit', $document->id) }}"
                   class="rounded-xl bg-gradient-to-l from-cyan-500 to-blue-600 hover:opacity-95 px-4 py-2.5 text-xs font-bold text-white shadow-lg shadow-blue-600/10 transition active:scale-95 inline-flex items-center gap-2">
                    <i data-lucide="edit" class="w-4 h-4"></i>
                    تعديل
                </a>
                <form action="{{ route('my.documents.destroy', $document->id) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه الوثيقة؟ لا يمكن التراجع!')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="rounded-xl bg-rose-500 hover:bg-rose-600 px-4 py-2.5 text-xs font-bold text-white shadow-lg transition active:scale-95 inline-flex items-center gap-2">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                        حذف
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection
