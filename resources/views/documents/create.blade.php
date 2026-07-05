@extends('layouts.app')

@section('title', 'إضافة وثيقة')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('documents.index') }}" class="p-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white transition-colors">
            <i data-lucide="arrow-right" class="w-5 h-5"></i>
        </a>
        <div>
            <p class="text-xs font-black uppercase tracking-[0.35em] text-slate-400">الوثائق</p>
            <h1 class="text-2xl font-black text-white mt-1">إضافة وثيقة جديدة</h1>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6 md:p-8">
        <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="block text-slate-700 text-sm font-medium">الموظف</label>
                    <select name="employee_id" style="color:black" class="w-full px-8 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all cursor-pointer" >
                        <option value="" >اختر الموظف...</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                {{ $employee->full_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('employee_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label class="block text-slate-700 text-sm font-medium">نوع الوثيقة</label>
                    <select name="document_type" class="w-full px-8 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all cursor-pointer">
                        <option value="">اختر النوع...</option>
                        <option value="identity" {{ old('document_type') == 'identity' ? 'selected' : '' }}>هوية</option>
                        <option value="passport" {{ old('document_type') == 'passport' ? 'selected' : '' }}>جواز سفر</option>
                        <option value="contract" {{ old('document_type') == 'contract' ? 'selected' : '' }}>عقد</option>
                        <option value="health_certificate" {{ old('document_type') == 'health_certificate' ? 'selected' : '' }}>شهادة صحية</option>
                    </select>
                    @error('document_type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label class="block text-slate-700 text-sm font-medium">رقم الوثيقة</label>
                    <input type="text" name="document_number" value="{{ old('document_number') }}" placeholder="أدخل رقم الوثيقة" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                    @error('document_number')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label class="block text-slate-700 text-sm font-medium">تاريخ الانتهاء</label>
                    <input type="date" name="expiry_date" value="{{ old('expiry_date') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                    @error('expiry_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2 md:col-span-2">
                    <label class="block text-slate-700 text-sm font-medium">الملف</label>
                    <div class="relative border-2 border-dashed border-slate-300 rounded-xl p-6 text-center hover:border-blue-400 transition-colors">
                        <input type="file" name="file" id="documentFile" accept=".pdf,.jpg,.jpeg,.png" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        <i data-lucide="upload-cloud" class="w-12 h-12 text-slate-400 mx-auto mb-2"></i>
                        <p class="text-sm text-slate-600">اسحب الملف هنا أو اضغط للاختيار</p>
                        <p class="text-xs text-slate-400 mt-1">PDF, JPG, PNG - حد أقصى 5MB</p>
                    </div>
                    @error('file')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                <button type="submit" class="h-10 px-6 py-3 bg-gradient-to-r bg-blue-500 to-teal-400 hover:from-blue-600 hover:to-teal-500 text-white font-semibold rounded-xl shadow-lg transition-all">
                    حفظ الوثيقة
                </button>
                <a href="{{ route('documents.index') }}" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-medium transition-all">
                    إلغاء
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
