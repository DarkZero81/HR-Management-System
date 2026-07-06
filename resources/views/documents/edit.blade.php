@extends('layouts.app')

@section('title', 'تعديل الوثيقة')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('documents.index') }}" class="p-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white transition-all border border-white/10">
            <i data-lucide="arrow-right" class="w-5 h-5"></i>
        </a>
        <div>
            <p class="text-xs font-black uppercase tracking-[0.35em] text-slate-400">الوثائق</p>
            <h1 class="text-2xl md:text-3xl font-black text-white mt-1">تعديل بيانات الوثيقة</h1>
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

    <div class="bg-slate-800 border border-white/10 rounded-2xl shadow-xl overflow-hidden">
        <div class="bg-gradient-to-l from-amber-500 to-orange-400 px-6 py-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center">
                <i data-lucide="edit" class="w-5 h-5 text-white"></i>
            </div>
            <div>
                <h5 class="text-white font-bold text-lg">تعديل بيانات الوثيقة</h5>
                <p class="text-amber-100 text-xs">قم بتعديل البيانات ثم اضغط حفظ التعديلات</p>
            </div>
        </div>
        <div class="p-6">
            <form action="{{ route('documents.update', $document->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="employee_id" class="block text-sm font-semibold text-white mb-2">الموظف <span class="text-rose-400">*</span></label>
                        <select name="employee_id" id="employee_id" required
                            class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all">
                            <option value="">اختر الموظف...</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" {{ old('employee_id', $document->employee_id) == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->full_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('employee_id')
                            <p class="text-rose-400 text-sm mt-1.5 flex items-center gap-1.5">
                                <i data-lucide="alert-circle" class="w-4 h-4"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="document_type" class="block text-sm font-semibold text-white mb-2">نوع الوثيقة <span class="text-rose-400">*</span></label>
                        <select name="document_type" id="document_type" required
                            class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all">
                            <option value="">اختر النوع...</option>
                            <option value="identity" {{ old('document_type', $document->document_type) == 'identity' ? 'selected' : '' }}>هوية</option>
                            <option value="passport" {{ old('document_type', $document->document_type) == 'passport' ? 'selected' : '' }}>جواز سفر</option>
                            <option value="contract" {{ old('document_type', $document->document_type) == 'contract' ? 'selected' : '' }}>عقد</option>
                            <option value="health_certificate" {{ old('document_type', $document->document_type) == 'health_certificate' ? 'selected' : '' }}>شهادة صحية</option>
                        </select>
                        @error('document_type')
                            <p class="text-rose-400 text-sm mt-1.5 flex items-center gap-1.5">
                                <i data-lucide="alert-circle" class="w-4 h-4"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="document_number" class="block text-sm font-semibold text-white mb-2">رقم الوثيقة <span class="text-rose-400">*</span></label>
                        <input type="text" name="document_number" id="document_number" value="{{ old('document_number', $document->document_number) }}" required
                            class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all"
                            placeholder="أدخل رقم الوثيقة">
                        @error('document_number')
                            <p class="text-rose-400 text-sm mt-1.5 flex items-center gap-1.5">
                                <i data-lucide="alert-circle" class="w-4 h-4"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="expiry_date" class="block text-sm font-semibold text-white mb-2">تاريخ الانتهاء <span class="text-rose-400">*</span></label>
                        <input type="date" name="expiry_date" id="expiry_date" value="{{ old('expiry_date', optional($document->expiry_date)->format('Y-m-d')) }}" required
                            class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all">
                        @error('expiry_date')
                            <p class="text-rose-400 text-sm mt-1.5 flex items-center gap-1.5">
                                <i data-lucide="alert-circle" class="w-4 h-4"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-white mb-2">الملف الحالي</label>
                        @if($document->file_path)
                            <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($document->file_path) }}" target="_blank" class="inline-flex items-center gap-2 text-blue-400 hover:text-blue-300 text-sm mb-3">
                                <i data-lucide="file-text" class="w-4 h-4"></i> عرض الملف الحالي
                            </a>
                        @endif
                        <div class="relative border-2 border-dashed border-slate-300 rounded-xl p-6 text-center hover:border-blue-400 transition-colors">
                            <input type="file" name="file" id="documentFile" accept=".pdf,.jpg,.jpeg,.png" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            <i data-lucide="upload-cloud" class="w-12 h-12 text-slate-400 mx-auto mb-2"></i>
                            <p class="text-sm text-slate-600">اترك هذا الحقل فارغاً للإبقاء على الملف الحالي، أو اختر ملفاً جديداً لاستبداله</p>
                            <p class="text-xs text-slate-400 mt-1">PDF, JPG, PNG - حد أقصى 5MB</p>
                        </div>
                        @error('file')
                            <p class="text-rose-400 text-sm mt-1.5 flex items-center gap-1.5">
                                <i data-lucide="alert-circle" class="w-4 h-4"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-4 border-t border-white/10">
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-500 to-teal-400 hover:from-blue-600 hover:to-teal-500 text-white rounded-xl font-bold shadow-lg transition-all">
                        <i data-lucide="save" class="w-5 h-5"></i>
                        حفظ التعديلات
                    </button>
                    <a href="{{ route('documents.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-slate-700 hover:bg-slate-600 text-white rounded-xl font-semibold transition-all border border-white/10">
                        <i data-lucide="x" class="w-5 h-5"></i>
                        إلغاء
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
