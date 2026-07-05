@extends('layouts.app')

@section('title', 'الوثائق')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-xs font-black uppercase tracking-[0.35em] text-slate-400">الوثائق</p>
            <h1 class="text-2xl md:text-3xl font-black text-white mt-1">سجل المستندات</h1>
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

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100 hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center">
                    <i data-lucide="file-text" class="w-6 h-6 text-blue-600"></i>
                </div>
                <span class="text-xs text-slate-400 font-medium">الإجمالي</span>
            </div>
            <p class="text-3xl font-bold text-slate-800">{{ $documents->total() }}</p>
            <p class="text-sm text-slate-500 mt-2">وثيقة</p>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100 hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center">
                    <i data-lucide="check-circle" class="w-6 h-6 text-emerald-600"></i>
                </div>
                <span class="text-xs text-slate-400 font-medium">النشطة</span>
            </div>
            <p class="text-3xl font-bold text-slate-800">{{ $documents->where('expiry_date', '>', now())->count() }}</p>
            <p class="text-sm text-slate-500 mt-2">وثيقة سارية</p>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100 hover:shadow-xl transition-shadow">
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

    <!-- Quick Upload Section -->
    <section class="bg-gradient-to-r from-slate-800 via-slate-700 to-blue-800 rounded-2xl p-6 text-white shadow-xl">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-white/10 flex items-center justify-center shrink-0">
                    <i data-lucide="upload-cloud" class="h-7 w-7 text-white"></i>
                </div>
                <div>
                    <h2 class="text-xl font-black">رفع مستند جديد</h2>
                    <p class="text-sm text-slate-300 mt-1">ارفع مستندك الآن وسيتم ربطه بحسابك تلقائياً</p>
                </div>
            </div>
            <div class="shrink-0">
                <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" id="uploadDocumentForm">
                    @csrf
                    <input type="file" name="document" id="hiddenFileInput" onchange="submitFormAutomatically()" class="hidden" accept=".pdf,.jpg,.jpeg,.png">

                    <button type="button" onclick="triggerFileInput()" id="uploadDocumentCard" class="inline-flex items-center gap-2 px-6 py-3 bg-white text-slate-900 rounded-xl font-bold hover:bg-slate-100 transition-colors shadow-lg">
                        <i data-lucide="file-plus" class="w-5 h-5"></i>
                        <span>اختر ملفاً للرفع</span>
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Documents Table -->
    @if($documents->count())
        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-4 text-slate-600 text-right font-medium">الموظف</th>
                            <th class="px-6 py-4 text-slate-600 text-right font-medium">نوع الوثيقة</th>
                            <th class="px-6 py-4 text-slate-600 text-right font-medium">الرقم</th>
                            <th class="px-6 py-4 text-slate-600 text-right font-medium">تاريخ الانتهاء</th>
                            <th class="px-6 py-4 text-slate-600 text-right font-medium">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($documents as $doc)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center text-white font-bold text-sm">
                                            {{ strtoupper(substr($doc->employee->first_name ?? 'U', 0, 1)) }}
                                        </div>
                                        <span class="font-semibold text-slate-800">{{ $doc->employee->full_name ?? ($doc->employee->first_name ?? '-') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                        {{ ucfirst(str_replace('_', ' ', $doc->document_type)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-600 font-mono text-sm">{{ $doc->document_number }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $doc->expiry_date }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('documents.edit', $doc->id) }}" class="p-2 rounded-xl bg-slate-100 hover:bg-blue-100 text-slate-600 hover:text-blue-600 transition-colors" title="تعديل">
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
