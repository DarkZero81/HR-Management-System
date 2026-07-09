@extends('layouts.app')
@section('title', 'تعديل بيانات الموظف')
@section('content')
<div class="max-w-4xl mx-auto space-y-6 px-4 py-4" dir="rtl">
    <div class="border-b border-white/5 pb-4">
        <p class="text-xs font-black uppercase tracking-[0.35em] text-blue-400 dark:text-cyan-400">الموظفين</p>
        <h1 class="mt-3 text-3xl font-bold text-slate-800">تعديل الملف الوظيفي: {{ $employee->full_name }}</h1>
        <p class="text-sm text-slate-400 dark:text-slate-500 mt-1">تحديث السجلات والبيانات التعاقدية أو المالية.</p>
    </div>
    @if ($errors->any())
        <div class="rounded-2xl border border-rose-500/20 bg-rose-500/10 p-4">
            <ul class="list-inside list-disc text-xs font-medium text-rose-400 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('employees.update', $employee->id) }}" method="POST" enctype="multipart/form-data" class="employee-form-card rounded-[28px] border border-white/10 dark:border-white/5 p-6 space-y-5 shadow-2xl backdrop-blur-md">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <div>
                <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">الاسم الأول <span class="text-rose-500">*</span></label>
                <input type="text" name="first_name" value="{{ old('first_name', $employee->first_name) }}" required class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 px-3 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">الاسم الأخير <span class="text-rose-500">*</span></label>
                <input type="text" name="last_name" value="{{ old('last_name', $employee->last_name) }}" required class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 px-3 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all">
            </div>

            <div class="sm:col-span-2">
                <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">صورة الموظف</label>
                <input type="file" name="avatar" accept="image/*" class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-xs text-slate-600 dark:text-slate-400 px-3 py-2 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-200 dark:file:bg-slate-800 file:text-slate-700 dark:file:text-slate-300 hover:file:bg-slate-300 dark:hover:file:bg-slate-700 file:cursor-pointer">
                @if($employee->avatar)
                    <img src="{{ asset('storage/' . $employee->avatar) }}" class="mt-3 w-20 h-20 rounded-full object-cover border border-white/10 shadow-lg">
                @endif
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">الرقم الوطني / الهوية <span class="text-rose-500">*</span></label>
                <input type="text" name="national_id" value="{{ old('national_id', $employee->national_id) }}" required class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm font-mono text-slate-800 dark:text-slate-200 px-3 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">رقم الهاتف</label>
                <input type="text" name="phone" value="{{ old('phone', $employee->phone) }}" class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 px-3 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">الراتب الأساسي <span class="text-rose-500">*</span></label>
                <input type="number" step="0.01" name="base_salary" value="{{ old('base_salary', $employee->base_salary) }}" required class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm font-semibold text-emerald-600 dark:text-emerald-400 px-3 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">رصيد الإجازات <span class="text-rose-500">*</span></label>
                <input type="number" name="vacation_balance" value="{{ old('vacation_balance', $employee->vacation_balance) }}" required class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 px-3 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">القسم الوظيفي</label>
                <select name="department_id" class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 px-8 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all cursor-pointer">
                    <option value="" class="bg-white dark:bg-slate-900 text-slate-500 dark:text-slate-400">-- اختر القسم --</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ old('department_id', $employee->department_id) == $dept->id ? 'selected' : '' }} class="bg-white dark:bg-slate-900">{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">وردية الدوام</label>
                <select name="shift_id" class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 px-8 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all cursor-pointer">
                    <option value="" class="bg-white dark:bg-slate-900 text-slate-500 dark:text-slate-400">-- اختر الوردية --</option>
                    @foreach($shifts as $shift)
                        <option value="{{ $shift->id }}" {{ old('shift_id', $employee->shift_id) == $shift->id ? 'selected' : '' }} class="bg-white dark:bg-slate-900">{{ $shift->shift_name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">تاريخ التعيين <span class="text-rose-500">*</span></label>
                <input type="date" name="join_date" value="{{ old('join_date', $employee->join_date?->format('Y-m-d')) }}" required class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 px-3 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">تاريخ نهاية الخدمة</label>
                <input type="date" name="resign_date" value="{{ old('resign_date', $employee->resign_date?->format('Y-m-d')) }}" class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 px-3 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all">
            </div>

            <div class="sm:col-span-2">
                <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">رقم الحساب البنكي (IBAN)</label>
                <input type="text" name="bank_account_iban" value="{{ old('bank_account_iban', $employee->bank_account_iban) }}" placeholder="JO00AAAA0000..." class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm font-mono text-slate-800 dark:text-slate-200 px-3 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all placeholder-slate-700">
            </div>
        </div>

        <div class="pt-4 border-t border-slate-200 dark:border-white/5">
            <p class="text-xs font-black uppercase tracking-[0.35em] text-blue-400 dark:text-cyan-400 mb-4">بيانات شخصية إضافية</p>
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">تاريخ الميلاد</label>
                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $employee->date_of_birth?->format('Y-m-d')) }}" class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 px-3 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">مكان الولادة</label>
                    <input type="text" name="place_of_birth" value="{{ old('place_of_birth', $employee->place_of_birth) }}" class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 px-3 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">نوع التعليم</label>
                    <select name="education_level" class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 px-8 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all cursor-pointer">
                        <option value="" class="bg-white dark:bg-slate-900 text-slate-500 dark:text-slate-400">-- اختر الشهادة --</option>
                        <option value="high_school" class="bg-white dark:bg-slate-900" {{ old('education_level', $employee->education_level) == 'high_school' ? 'selected' : '' }}>ثانوية</option>
                        <option value="diploma" class="bg-white dark:bg-slate-900" {{ old('education_level', $employee->education_level) == 'diploma' ? 'selected' : '' }}>دبلوم</option>
                        <option value="bachelor" class="bg-white dark:bg-slate-900" {{ old('education_level', $employee->education_level) == 'bachelor' ? 'selected' : '' }}>بكالوريوس</option>
                        <option value="master" class="bg-white dark:bg-slate-900" {{ old('education_level', $employee->education_level) == 'master' ? 'selected' : '' }}>ماجستير</option>
                        <option value="phd" class="bg-white dark:bg-slate-900" {{ old('education_level', $employee->education_level) == 'phd' ? 'selected' : '' }}>دكتوراه</option>
                        <option value="other" class="bg-white dark:bg-slate-900" {{ old('education_level', $employee->education_level) == 'other' ? 'selected' : '' }}>أخرى</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">الحالة الاجتماعية</label>
                    <select name="marital_status" class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 px-8 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all cursor-pointer">
                        <option value="" class="bg-white dark:bg-slate-900 text-slate-500 dark:text-slate-400">-- اختر الحالة --</option>
                        <option value="single" class="bg-white dark:bg-slate-900" {{ old('marital_status', $employee->marital_status) == 'single' ? 'selected' : '' }}>أعزب</option>
                        <option value="married" class="bg-white dark:bg-slate-900" {{ old('marital_status', $employee->marital_status) == 'married' ? 'selected' : '' }}>متزوج</option>
                        <option value="divorced" class="bg-white dark:bg-slate-900" {{ old('divorced', $employee->marital_status) == 'divorced' ? 'selected' : '' }}>مطلق</option>
                        <option value="widowed" class="bg-white dark:bg-slate-900" {{ old('marital_status', $employee->marital_status) == 'widowed' ? 'selected' : '' }}>أرمل</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">الجنسية</label>
                    <input type="text" name="nationality" value="{{ old('nationality', $employee->nationality) }}" class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 px-3 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">العنوان</label>
                    <input type="text" name="address" value="{{ old('address', $employee->address) }}" class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 px-3 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">اسم جهة اتصال الطوارئ</label>
                    <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name', $employee->emergency_contact_name) }}" class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 px-3 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">هاتف الطوارئ</label>
                    <input type="text" name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $employee->emergency_contact_phone) }}" class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 px-3 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">المسمى الوظيفي</label>
                    <input type="text" name="job_title" value="{{ old('job_title', $employee->job_title) }}" class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 px-3 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">تاريخ انتهاء العقد</label>
                    <input type="date" name="contract_end_date" value="{{ old('contract_end_date', $employee->contract_end_date?->format('Y-m-d')) }}" class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 px-3 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">رقم التأمين</label>
                    <input type="text" name="insurance_number" value="{{ old('insurance_number', $employee->insurance_number) }}" class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 px-3 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all">
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-2.5 border-t border-slate-200 dark:border-white/5 pt-4">
            <a href="{{ route('employees.index') }}" class="form-cancel-btn rounded-xl bg-slate-200 hover:bg-slate-300 dark:bg-slate-800 dark:hover:bg-slate-700 border border-slate-300 dark:border-white/5 px-4 py-2.5 text-xs font-bold text-slate-700 dark:text-slate-300 transition active:scale-95">إلغاء والعودة</a>
            <button type="submit" class="rounded-xl bg-gradient-to-l from-cyan-500 to-blue-600 hover:opacity-95 px-4 py-2.5 text-xs font-bold text-white shadow-lg shadow-blue-600/10 transition active:scale-95 cursor-pointer">تحديث ملف الموظف</button>
        </div>
    </form>
</div>
@endsection
