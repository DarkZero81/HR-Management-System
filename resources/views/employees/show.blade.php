@extends('layouts.app')
@section('title', $employee->full_name)
@section('content')
<div class="max-w-4xl mx-auto space-y-6 px-4 py-4" dir="rtl">
    <div class="border-b border-white/5 pb-4">
        <p class="text-xs font-black uppercase tracking-[0.35em] text-teal-400">الموظفين</p>
        <h1 class="mt-3 text-3xl font-bold text-slate-800">الملف الوظيفي: {{ $employee->full_name }}</h1>
        <p class="text-xs text-slate-400 mt-1">عرض جميع البيانات المسجلة للموظف.</p>
    </div>
    <div class="employee-form-card rounded-[28px] border border-white/10 dark:border-white/5 p-6 shadow-2xl backdrop-blur-md">
        <div class="flex flex-col md:flex-row gap-6">
            <div class="md:w-1/3 flex flex-col items-center text-center">
                @if($employee->avatar)
                    <img src="{{ asset('storage/' . $employee->avatar) }}" class="w-32 h-32 rounded-full object-cover border-4 border-white/10 shadow-lg mb-4">
                @else
                    <div class="w-32 h-32 rounded-full bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center text-white font-black text-3xl shadow-lg mb-4">
                        {{ strtoupper(substr($employee->first_name ?? 'U', 0, 1)) }}
                    </div>
                @endif
                <h2 class="mt-2 text-3xl font-bold text-slate-800">{{ $employee->full_name }}</h2>
                <p class="text-sm text-slate-400 mt-1">{{ $employee->job_title ?? 'بدون مسمى وظيفي' }}</p>
                @if($employee->age)
                    <span class="mt-3 inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-cyan-500/10 text-cyan-300 border border-cyan-500/20">
                        <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                        {{ $employee->age }} سنة
                    </span>
                @endif
            </div>
            <div class="md:w-2/3 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="rounded-xl border border-slate-200/60 dark:border-white/5 bg-slate-50/50 dark:bg-slate-950/40 p-3">
                    <p class="text-xs text-slate-500 mb-1">الرقم الوطني</p>
                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-200 font-mono">{{ $employee->national_id }}</p>
                </div>
                <div class="rounded-xl border border-slate-200/60 dark:border-white/5 bg-slate-50/50 dark:bg-slate-950/40 p-3">
                    <p class="text-xs text-slate-500 mb-1">رقم الهاتف</p>
                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $employee->phone ?? '-' }}</p>
                </div>
                <div class="rounded-xl border border-slate-200/60 dark:border-white/5 bg-slate-50/50 dark:bg-slate-950/40 p-3">
                    <p class="text-xs text-slate-500 mb-1">البريد الإلكتروني</p>
                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $employee->user?->email ?? '-' }}</p>
                </div>
                <div class="rounded-xl border border-slate-200/60 dark:border-white/5 bg-slate-50/50 dark:bg-slate-950/40 p-3">
                    <p class="text-xs text-slate-500 mb-1">القسم</p>
                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $employee->department?->name ?? 'غير معين' }}</p>
                </div>
                <div class="rounded-xl border border-slate-200/60 dark:border-white/5 bg-slate-50/50 dark:bg-slate-950/40 p-3">
                    <p class="text-xs text-slate-500 mb-1">الوردية</p>
                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $employee->shift?->shift_name ?? 'بدون وردية' }}</p>
                </div>
                <div class="rounded-xl border border-slate-200/60 dark:border-white/5 bg-slate-50/50 dark:bg-slate-950/40 p-3">
                    <p class="text-xs text-slate-500 mb-1">الراتب الأساسي</p>
                    <p class="text-sm font-semibold text-emerald-600 dark:text-emerald-400">{{ number_format($employee->base_salary, 2) }} ل.س</p>
                </div>
                <div class="rounded-xl border border-slate-200/60 dark:border-white/5 bg-slate-50/50 dark:bg-slate-950/40 p-3">
                    <p class="text-xs text-slate-500 mb-1">تاريخ التعيين</p>
                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $employee->join_date?->format('Y-m-d') }}</p>
                </div>
                <div class="rounded-xl border border-slate-200/60 dark:border-white/5 bg-slate-50/50 dark:bg-slate-950/40 p-3">
                    <p class="text-xs text-slate-500 mb-1">رصيد الإجازات</p>
                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $employee->vacation_balance }} يوم</p>
                </div>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-slate-200 dark:border-white/5">
            <p class="text-xs font-black uppercase tracking-[0.35em] text-blue-400 dark:text-cyan-400 mb-4">بيانات شخصية إضافية</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="rounded-xl border border-slate-200/60 dark:border-white/5 bg-slate-50/50 dark:bg-slate-950/40 p-3">
                    <p class="text-xs text-slate-500 mb-1">تاريخ الميلاد</p>
                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $employee->date_of_birth?->format('Y-m-d') ?? '-' }}</p>
                </div>
                <div class="rounded-xl border border-slate-200/60 dark:border-white/5 bg-slate-50/50 dark:bg-slate-950/40 p-3">
                    <p class="text-xs text-slate-500 mb-1">مكان الولادة</p>
                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $employee->place_of_birth ?? '-' }}</p>
                </div>
                <div class="rounded-xl border border-slate-200/60 dark:border-white/5 bg-slate-50/50 dark:bg-slate-950/40 p-3">
                    <p class="text-xs text-slate-500 mb-1">نوع التعليم</p>
                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $employee->education_label }}</p>
                </div>
                <div class="rounded-xl border border-slate-200/60 dark:border-white/5 bg-slate-50/50 dark:bg-slate-950/40 p-3">
                    <p class="text-xs text-slate-500 mb-1">الحالة الاجتماعية</p>
                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $employee->marital_status_label }}</p>
                </div>
                <div class="rounded-xl border border-slate-200/60 dark:border-white/5 bg-slate-50/50 dark:bg-slate-950/40 p-3">
                    <p class="text-xs text-slate-500 mb-1">الجنسية</p>
                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $employee->nationality ?? '-' }}</p>
                </div>
                <div class="rounded-xl border border-slate-200/60 dark:border-white/5 bg-slate-50/50 dark:bg-slate-950/40 p-3">
                    <p class="text-xs text-slate-500 mb-1">العنوان</p>
                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $employee->address ?? '-' }}</p>
                </div>
                <div class="rounded-xl border border-slate-200/60 dark:border-white/5 bg-slate-50/50 dark:bg-slate-950/40 p-3">
                    <p class="text-xs text-slate-500 mb-1">اسم جهة اتصال الطوارئ</p>
                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $employee->emergency_contact_name ?? '-' }}</p>
                </div>
                <div class="rounded-xl border border-slate-200/60 dark:border-white/5 bg-slate-50/50 dark:bg-slate-950/40 p-3">
                    <p class="text-xs text-slate-500 mb-1">هاتف الطوارئ</p>
                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $employee->emergency_contact_phone ?? '-' }}</p>
                </div>
                <div class="rounded-xl border border-slate-200/60 dark:border-white/5 bg-slate-50/50 dark:bg-slate-950/40 p-3">
                    <p class="text-xs text-slate-500 mb-1">المسمى الوظيفي</p>
                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $employee->job_title ?? '-' }}</p>
                </div>
                <div class="rounded-xl border border-slate-200/60 dark:border-white/5 bg-slate-50/50 dark:bg-slate-950/40 p-3">
                    <p class="text-xs text-slate-500 mb-1">تاريخ انتهاء العقد</p>
                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $employee->contract_end_date?->format('Y-m-d') ?? '-' }}</p>
                </div>
                <div class="rounded-xl border border-slate-200/60 dark:border-white/5 bg-slate-50/50 dark:bg-slate-950/40 p-3">
                    <p class="text-xs text-slate-500 mb-1">رقم التأمين</p>
                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $employee->insurance_number ?? '-' }}</p>
                </div>
            </div>
        </div>

        <div class="mt-8 flex items-center justify-end gap-2.5 border-t border-slate-200 dark:border-white/5 pt-4">
            <a href="{{ route('employees.pdf', $employee->id) }}" target="_blank" class="inline-flex items-center gap-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 px-4 py-2.5 text-xs font-bold transition-colors">
                <i data-lucide="file-text" class="w-4 h-4"></i>
                تحميل PDF
            </a>
            <a href="{{ route('employees.edit', $employee->id) }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white px-4 py-2.5 text-xs font-bold shadow-lg transition">
                <i data-lucide="edit" class="w-4 h-4"></i>
                تعديل
            </a>
            <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد تماماً من رغبتك في حذف ملف هذا الموظف؟ لا يمكن التراجع!');">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-red-600 hover:bg-red-500 text-white px-4 py-2.5 text-xs font-bold shadow-lg transition">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                    حذف
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
