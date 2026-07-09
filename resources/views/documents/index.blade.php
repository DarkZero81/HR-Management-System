@extends('layouts.app')

@section('title', 'الوثائق')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-xs font-black uppercase tracking-[0.35em] text-slate-400">الوثائق</p>
            <h1 class="text-3xl font-bold text-slate-800">سجل المستندات</h1>
            <p class="text-sm text-slate-400 mt-1">ارفع المستندات الرسمية وحافظ على تنظيمها داخل النظام بسهولة.</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-white rounded-xl font-medium transition-all duration-200 border border-white/10">
                <i data-lucide="user" class="w-4 h-4"></i>
                <span class="hidden sm:inline">الملف الشخصي</span>
            </a>

            @auth
                @if (in_array(optional(auth()->user()->role)->role_name, ['admin', 'hr', 'manager'], true))
                    <a href="{{ route('documents.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-500 to-teal-400 hover:from-blue-600 hover:to-teal-500 text-white rounded-xl font-semibold shadow-lg transition-all duration-200">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        <span class="hidden sm:inline">إضافة وثيقة</span>
                    </a>
                @endif
            @endauth
        </div>
    </div>

    {{-- عرض أخطاء التحقق (مثال: نوع/حجم الملف غير مسموح) --}}
    @if($errors->any())
        <div class="rounded-2xl border border-rose-400/20 bg-rose-500/10 px-4 py-3 text-sm font-semibold text-rose-200">
            <div class="flex items-center gap-2 mb-1">
                <i data-lucide="alert-circle" class="w-5 h-5"></i>
                <span>تعذّر رفع الملف، تحقق من الآتي:</span>
            </div>
            <ul class="list-disc pr-8 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-3 md:grid-cols-3 gap-4 md:gap-6">
        <div class="w-75 bg-white rounded-2xl p-6 shadow-lg border border-slate-100 hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center">
                    <i data-lucide="file-text" class="w-6 h-6 text-blue-600"></i>
                </div>
                <span class="text-xs text-slate-400 font-medium">الإجمالي</span>
            </div>
            <p class="text-3xl font-bold text-slate-800">{{ $documents->total() }}</p>
            <p class="text-sm text-slate-500 mt-2">وثيقة</p>
        </div>

        <div class="w-75 bg-white rounded-2xl p-6 shadow-lg border border-slate-100 hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center">
                    <i data-lucide="check-circle" class="w-6 h-6 text-emerald-600"></i>
                </div>
                <span class="text-xs text-slate-400 font-medium">النشطة</span>
            </div>
            <p class="text-3xl font-bold text-slate-800">{{ $documents->where('expiry_date', '>', now())->count() }}</p>
            <p class="text-sm text-slate-500 mt-2">وثيقة سارية</p>
        </div>

        <div class="w-75 bg-white rounded-2xl p-6 shadow-lg border border-slate-100 hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center">
                    <i data-lucide="clock" class="w-6 h-6 text-amber-600"></i>
                </div>
                <span class="text-xs text-slate-400 font-medium">قريبة الانتهاء</span>
            </div>
            <p class="text-3xl font-bold text-slate-800">{{ $documents->where('expiry_date', '<=', now()->addMonths(3))->where('expiry_date', '>', now())->count() }}</p>
            <p class="text-sm text-slate-500 mt-2">تحتاج متابعة</p>
        </div>
    </div>

    <!-- Documents Table -->
    @if($documents->count())
        @if($expiringDocuments->count() > 0)
            <div class="rounded-2xl border border-amber-400/20 bg-amber-500/10 p-4">
                <div class="flex items-center gap-2 mb-3">
                    <i data-lucide="alert-triangle" class="w-5 h-5 text-amber-500"></i>
                    <span class="font-bold text-amber-700">تنبيهات انتهاء الوثائق</span>
                </div>
                <div class="space-y-2">
                    @foreach($expiringDocuments as $doc)
                        <div class="flex items-center justify-between rounded-xl border border-amber-200 bg-amber-50 px-4 py-2.5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center text-white font-bold text-xs">
                                    {{ strtoupper(substr($doc->employee->first_name ?? 'U', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-800">{{ $doc->employee->full_name ?? ($doc->employee->first_name ?? 'موظف') }}</p>
                                    <p class="text-xs text-slate-500">وثيقة: {{ ucfirst(str_replace('_', ' ', $doc->document_type)) }} رقم {{ $doc->document_number }}</p>
                                </div>
                            </div>
                            <div class="text-left">
                                <p class="text-xs font-bold text-amber-700">تنتهي في {{ \Carbon\Carbon::parse($doc->expiry_date)->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">الموظف</th>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">المستخدم</th>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">نوع الوثيقة</th>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">الرقم</th>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">تاريخ الانتهاء</th>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">تاريخ الرفع</th>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">نوع الملف</th>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">الإجراءات</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($documents as $doc)
                            @php
                                $ext = strtoupper(pathinfo($doc->file_path ?? '', PATHINFO_EXTENSION));
                                $isOwner = auth()->id() === ($doc->employee->user_id ?? null);
                            @endphp
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center text-white font-bold text-sm">
                                            {{ strtoupper(substr($doc->employee->first_name ?? 'U', 0, 1)) }}
                                        </div>
                                        <span class="font-semibold text-slate-800">{{ $doc->employee->full_name ?? ($doc->employee->first_name ?? '-') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $doc->employee->user->name ?? $doc->employee->user->email ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                        {{ ucfirst(str_replace('_', ' ', $doc->document_type)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-600 font-mono text-sm">{{ $doc->document_number }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $doc->expiry_date }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $doc->created_at?->format('Y-m-d') ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-medium {{ $ext == 'PDF' ? 'bg-rose-100 text-rose-700' : 'bg-emerald-100 text-emerald-700' }}">
                                        {{ $ext ?: 'FILE' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        @if($isOwner && $doc->file_path)
                                            <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank"
                                                class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-medium transition-colors"
                                                title="عرض/تحميل">
                                                <i data-lucide="download" class="w-3.5 h-3.5"></i>
                                            </a>
                                        @endif
                                        <a href="{{ route('documents.edit', $doc->id) }}"
                                            class="p-2 rounded-xl bg-slate-100 hover:bg-blue-100 text-slate-600 hover:text-blue-600 transition-colors" title="تعديل">
                                            <i data-lucide="edit" class="w-4 h-4"></i>
                                        </a>
                                        <form action="{{ route('documents.destroy', $doc->id) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه الوثيقة؟ لا يمكن التراجع!')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 rounded-xl bg-slate-100 hover:bg-red-100 text-slate-600 hover:text-red-600 transition-colors" title="حذف">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="border-t border-slate-100 bg-slate-50 px-6 py-4">
                {{ $documents->links() }}
            </div>
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-10 text-center">
            <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-3xl bg-blue-100 text-blue-600">
                <i data-lucide="file-text" class="h-8 w-8"></i>
            </div>
            <h2 class="text-xl font-black text-slate-900">لا توجد وثائق حتى الآن</h2>
            <p class="mt-3 text-sm text-slate-500">ابدأ برفع مستنداتك الرسمية الآن للحفاظ على تنظيم ملفك الوظيفي.</p>
        </div>
    @endif
</div>

<script>
function triggerFileInput() {
    document.getElementById('hiddenFileInput').click();
}

function submitFormAutomatically() {
    const form = document.getElementById('uploadDocumentForm');
    const fileInput = document.getElementById('hiddenFileInput');
    const uploadCard = document.getElementById('uploadDocumentCard');

    if (fileInput.files.length > 0) {
        if (uploadCard) {
            uploadCard.disabled = true;
            uploadCard.innerHTML = '<div class="flex items-center gap-2"><div class="h-5 w-5 animate-spin rounded-full border-2 border-slate-900 border-t-transparent"></div><span>جاري الرفع...</span></div>';
        }

        setTimeout(() => {
            form.submit();
        }, 100);
    }
}
</script>
@endsection
