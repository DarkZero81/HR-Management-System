@extends('layouts.app')
@section('title', 'تعديل الوثيقة')
@section('content')
<div class="max-w-3xl mx-auto space-y-6 px-4 py-4" dir="rtl">
    <div class="border-b border-white/5 pb-4">
        <p class="text-xs font-black uppercase tracking-[0.35em] text-blue-400 dark:text-cyan-400">الوثائق</p>
        <h1 class="text-2xl md:text-3xl font-black text-white dark:text-slate-900 mt-0.5">تعديل بيانات الوثيقة</h1>
        <p class="text-sm text-slate-400 dark:text-slate-500 mt-1">قم بتعديل بيانات الوثيقة أو استبدال الملف.</p>
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
    @if ($errors->any())
        <div class="rounded-2xl border border-rose-500/20 bg-rose-500/10 p-4">
            <ul class="list-inside list-disc text-xs font-medium text-rose-400 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('documents.update', $document->id) }}" enctype="multipart/form-data" class="employee-form-card rounded-[28px] border border-white/10 dark:border-white/5 p-6 space-y-5 shadow-2xl backdrop-blur-md">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <div>
                <label for="employee_id" class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">الموظف <span class="text-rose-500">*</span></label>
                <select name="employee_id" id="employee_id" required class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 px-3 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all cursor-pointer">
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" {{ old('employee_id', $document->employee_id) == $employee->id ? 'selected' : '' }} class="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200">{{ $employee->full_name }}</option>
                    @endforeach
                </select>
                @error('employee_id')
                    <p class="text-rose-500 text-sm mt-1.5 flex items-center gap-1.5">
                        <i data-lucide="alert-circle" class="w-4 h-4"></i>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <label for="document_type" class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">نوع الوثيقة <span class="text-rose-500">*</span></label>
                <select name="document_type" id="document_type" required class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 px-3 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all cursor-pointer">
                    <option value="identity" {{ old('document_type', $document->document_type) == 'identity' ? 'selected' : '' }} class="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200">هوية</option>
                    <option value="passport" {{ old('document_type', $document->document_type) == 'passport' ? 'selected' : '' }} class="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200">جواز سفر</option>
                    <option value="contract" {{ old('document_type', $document->document_type) == 'contract' ? 'selected' : '' }} class="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200">عقد</option>
                    <option value="health_certificate" {{ old('document_type', $document->document_type) == 'health_certificate' ? 'selected' : '' }} class="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200">شهادة صحية</option>
                </select>
                @error('document_type')
                    <p class="text-rose-500 text-sm mt-1.5 flex items-center gap-1.5">
                        <i data-lucide="alert-circle" class="w-4 h-4"></i>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <label for="document_number" class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">رقم الوثيقة <span class="text-rose-500">*</span></label>
                <input type="text" name="document_number" id="document_number" value="{{ old('document_number', $document->document_number) }}" required placeholder="أدخل رقم الوثيقة" class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 px-3 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all">
                @error('document_number')
                    <p class="text-rose-500 text-sm mt-1.5 flex items-center gap-1.5">
                        <i data-lucide="alert-circle" class="w-4 h-4"></i>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <label for="expiry_date" class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">تاريخ الانتهاء <span class="text-rose-500">*</span></label>
                <input type="date" name="expiry_date" id="expiry_date" value="{{ old('expiry_date', optional($document->expiry_date)->format('Y-m-d')) }}" required class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 px-3 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all">
                @error('expiry_date')
                    <p class="text-rose-500 text-sm mt-1.5 flex items-center gap-1.5">
                        <i data-lucide="alert-circle" class="w-4 h-4"></i>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="sm:col-span-2">
                <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">الملف الحالي</label>
                @if($document->file_path)
                    <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($document->file_path) }}" target="_blank" class="inline-flex items-center gap-2 text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 text-sm mb-3">
                        <i data-lucide="file-text" class="w-4 h-4"></i> عرض الملف الحالي
                    </a>
                @endif
                <div class="relative border-2 border-dashed border-slate-300 dark:border-slate-700 rounded-xl p-6 text-center hover:border-cyan-500 transition-colors cursor-pointer">
                    <input type="file" name="file" id="documentFile" accept=".pdf,.jpg,.jpeg,.png" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="updateFileName(this)">
                    <i data-lucide="upload-cloud" class="w-12 h-12 text-slate-400 mx-auto mb-2"></i>
                    <p class="text-sm text-slate-600 dark:text-slate-400">اتركه فارغاً للحفاظ على الملف الحالي، أو اختر ملفاً للاستبدال</p>
                    <p class="text-xs text-slate-500 mt-1">PDF, JPG, PNG - حد أقصى 5MB</p>
                    <p id="fileName" class="mt-2 text-sm font-semibold text-cyan-600 hidden"></p>
                </div>
                @error('file')
                    <p class="text-rose-500 text-sm mt-1.5 flex items-center gap-1.5">
                        <i data-lucide="alert-circle" class="w-4 h-4"></i>
                        {{ $message }}
                    </p>
                @enderror
            </div>
        </div>

        <div class="flex items-center justify-end gap-2.5 border-t border-slate-200 dark:border-white/5 pt-4">
            <a href="{{ route('documents.index') }}" class="form-cancel-btn rounded-xl bg-slate-200 hover:bg-slate-300 dark:bg-slate-800 dark:hover:bg-slate-700 border border-slate-300 dark:border-white/5 px-4 py-2.5 text-xs font-bold text-slate-700 dark:text-slate-300 transition active:scale-95">إلغاء والعودة</a>
            <button type="submit" class="rounded-xl bg-gradient-to-l from-cyan-500 to-blue-600 hover:opacity-95 px-4 py-2.5 text-xs font-bold text-white shadow-lg shadow-blue-600/10 transition active:scale-95 cursor-pointer inline-flex items-center gap-2">
                <i data-lucide="save" class="w-4 h-4"></i>
                حفظ التعديلات
            </button>
        </div>
    </form>
</div>
<script>
function updateFileName(input) {
    const fileNameDisplay = document.getElementById('fileName');
    if (input.files && input.files.length > 0) {
        fileNameDisplay.textContent = input.files[0].name;
        fileNameDisplay.classList.remove('hidden');
    } else {
        fileNameDisplay.classList.add('hidden');
    }
}
</script>
@endsection
