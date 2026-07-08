{{-- resources/views/employees/create.blade.php --}}
@extends('layouts.app')
@section('title', 'إضافة موظف جديد')
@section('content')
<div class="max-w-3xl mx-auto space-y-6 px-4 py-4" dir="rtl">
    <div class="border-b border-white/5 pb-4">
        <p class="text-xs font-black uppercase tracking-[0.35em] text-teal-400">الموظفين</p>
        <h1 class="text-2xl md:text-3xl font-black text-white mt-0.5">إنشاء ملف موظف جديد</h1>
        <p class="text-xs text-slate-400 mt-1">إدخال البيانات الشخصية والمالية والتعاقدية للموظف الجديد بالنظام.</p>
    </div>
    @if ($errors->any())
        <div class="rounded-2xl border border-rose-500/20 bg-rose-500/10 p-4 animate-fade-in">
            <ul class="list-inside list-disc text-xs font-medium text-rose-400 space-y-1">@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
        </div>
    @endif
    <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data" class="bg-white/80 dark:bg-slate-900/40 rounded-[2rem] border border-white/10 dark:border-white/5 p-6 space-y-5 shadow-2xl backdrop-blur-md">
        @csrf
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <div>
                <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1.5">حساب المستخدم المرتبط <span class="text-rose-500">*</span></label>
                @if($users->isEmpty())
                    <div class="rounded-xl bg-rose-500/10 border border-rose-500/20 p-2.5 text-xs text-rose-600 dark:text-rose-400">لا توجد حسابات متاحة. <a href="{{ route('register') }}" class="underline font-bold text-rose-600 dark:text-rose-300">إنشاء حساب</a></div>
                @else
                    <select name="user_id" required class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 px-3 py-2.5 focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all cursor-pointer">
                        <option value="" class="bg-white dark:bg-slate-900 text-slate-500 dark:text-slate-400">-- اختر حساب مستخدم --</option>
                        @foreach($users as $user) <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }} class="bg-white dark:bg-slate-900">{{ $user->name ?? $user->email }}</option> @endforeach
                    </select>
                @endif
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1.5">الاسم الأول <span class="text-rose-500">*</span></label>
                <input type="text" name="first_name" value="{{ old('first_name') }}" required class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-white px-3 py-2.5 focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all placeholder-slate-700">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1.5">الاسم الأخير (الكنية) <span class="text-rose-500">*</span></label>
                <input type="text" name="last_name" value="{{ old('last_name') }}" required class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-white px-3 py-2.5 focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all placeholder-slate-700">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1.5">صورة الموظف</label>
                <input type="file" name="avatar" accept="image/*" class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-xs text-slate-600 dark:text-slate-400 px-3 py-2 focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-200 dark:file:bg-slate-800 file:text-slate-700 dark:file:text-slate-300 hover:file:bg-slate-300 dark:hover:file:bg-slate-700 file:cursor-pointer">
                @if(old('avatar')) <img src="{{ asset('storage/' . old('avatar')) }}" class="mt-2 w-12 h-12 rounded-full object-cover border border-white/10 shadow-md"> @endif
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1.5">الرقم الوطني / الهوية <span class="text-rose-500">*</span></label>
                <input type="text" name="national_id" value="{{ old('national_id') }}" required class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm font-mono text-slate-800 dark:text-white px-3 py-2.5 focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1.5">رقم الهاتف</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-white px-3 py-2.5 focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1.5">الراتب الأساسي الثابت <span class="text-rose-500">*</span></label>
                <input type="number" step="0.01" name="base_salary" value="{{ old('base_salary') }}" required class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm font-semibold text-emerald-600 dark:text-emerald-400 px-3 py-2.5 focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1.5">رصيد الإجازات السنوي الأولي</label>
                <input type="number" name="vacation_balance" value="{{ old('vacation_balance', 21) }}" class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-white px-3 py-2.5 focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1.5">القسم الوظيفي</label>
                <select name="department_id" class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 px-3 py-2.5 focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all cursor-pointer">
                    <option value="" class="bg-white dark:bg-slate-900 text-slate-500 dark:text-slate-400">-- اختر القسم --</option>
                    @foreach($departments as $dept) <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }} class="bg-white dark:bg-slate-900">{{ $dept->name }}</option> @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1.5">وردية الدوام الرسمي</label>
                <select name="shift_id" class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 px-3 py-2.5 focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all cursor-pointer">
                    <option value="" class="bg-white dark:bg-slate-900 text-slate-500 dark:text-slate-400">-- اختر الوردية الزمنية --</option>
                    @foreach($shifts as $shift) <option value="{{ $shift->id }}" {{ old('shift_id') == $shift->id ? 'selected' : '' }} class="bg-white dark:bg-slate-900">{{ $shift->shift_name }} ({{ $shift->start_time }} - {{ $shift->end_time }})</option> @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1.5">تاريخ التعيين المعتمد <span class="text-rose-500">*</span></label>
                <input type="date" name="join_date" value="{{ old('join_date', date('Y-m-d')) }}" required class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 px-3 py-2.5 focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1.5">رقم الحساب البنكي الدولي (IBAN)</label>
                <input type="text" name="bank_account_iban" value="{{ old('bank_account_iban') }}" placeholder="JO00AAAA0000..." class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm font-mono text-slate-800 dark:text-white px-3 py-2.5 focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all placeholder-slate-700">
            </div>
        </div>
        <div class="flex items-center justify-end gap-2.5 border-t border-slate-200 dark:border-white/5 pt-4">
            <a href="{{ route('employees.index') }}" class="rounded-xl bg-slate-200 hover:bg-slate-300 dark:bg-slate-800 dark:hover:bg-slate-700 border border-slate-300 dark:border-white/5 px-4 py-2.5 text-xs font-bold text-slate-700 dark:text-slate-300 transition active:scale-95">إلغاء والعودة</a>
            <button type="submit" class="rounded-xl bg-gradient-to-l from-blue-600 to-teal-500 hover:opacity-95 px-4 py-2.5 text-xs font-bold text-white shadow-lg shadow-blue-600/10 transition active:scale-95 cursor-pointer">حفظ وإنشاء ملف الموظف</button>
        </div>
    </form>
</div>
@endsection