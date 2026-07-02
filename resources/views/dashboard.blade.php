@extends('layouts.app')

@section('title', 'الرئيسية')

@section('content')
<!-- حاوية مستقلة تماماً لمنع تداخل أسلوب Breeze القديم -->
<div class="w-full flex flex-col gap-6 text-right" dir="rtl">

    <!-- 1. صندوق الترحيب العريض والوقت اللحظي المميز -->
    <div class="w-full rounded-[24px] bg-gradient-to-r from-blue-600 to-indigo-700 p-6 text-white shadow-lg flex flex-col md:flex-row justify-between items-center gap-6">
        <div class="flex items-center gap-4">
            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white/10 backdrop-blur-md shadow-inner">
                <i data-lucide="user" class="h-6 w-6 text-white"></i>
            </div>
            <div>
                <h3 class="text-xl font-black">مرحباً، {{ auth()->user()->name ?? 'micheal' }}!</h3>
                <p class="text-xs text-blue-100 mt-0.5 font-medium">نتمنى لك يوماً موفقاً في عملك</p>
            </div>
        </div>

        <!-- عداد الوقت الجانبي المتناسق -->
        <div class="rounded-xl bg-white/10 p-3 backdrop-blur-md border border-white/5 flex items-center gap-3 w-full md:w-auto justify-center">
            <div class="text-right">
                <p class="text-[10px] text-blue-200 uppercase font-bold tracking-wider">الوقت الحالي</p>
                <p class="text-xl font-black tracking-wide mt-0.5">{{ now()->format('H:i') }} <span class="text-xs font-normal">ص</span></p>
            </div>
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-white/10 text-white">
                <i data-lucide="clock" class="h-5 w-5"></i>
            </div>
        </div>
    </div>

    <!-- 2. شبكة بطاقات المؤشرات الأربعة الهندسية -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 w-full">

        <!-- بطاقة رصيد الإجازات -->
        <div class="rounded-[22px] border border-slate-200/60 bg-white p-4 shadow-sm transition hover:shadow-md flex flex-col justify-between min-h-[130px]">
            <div class="flex items-start justify-between">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-cyan-50 text-cyan-600">
                    <i data-lucide="calendar" class="h-5 w-5"></i>
                </div>
                <p class="text-xs font-bold text-slate-400">رصيد الإجازات</p>
            </div>
            <div class="mt-4 text-right">
                <h4 class="text-xl font-black text-slate-900">30 يوم</h4>
                <p class="text-[10px] text-slate-400 mt-0.5 font-medium">من أصل 30 يوم</p>
            </div>
        </div>

        <!-- بطاقة الراتب الأساسي -->
        <div class="rounded-[22px] border border-slate-200/60 bg-white p-4 shadow-sm transition hover:shadow-md flex flex-col justify-between min-h-[130px]">
            <div class="flex items-start justify-between">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                    <i data-lucide="dollar-sign" class="h-5 w-5"></i>
                </div>
                <p class="text-xs font-bold text-slate-400">الراتب الأساسي</p>
            </div>
            <div class="mt-4 text-right">
                <h4 class="text-xl font-black text-slate-900">{{ number_format(auth()->user()->employee->base_salary ?? 0) }}</h4>
                <p class="text-[10px] text-slate-400 mt-0.5 font-medium">ريال سعودي</p>
            </div>
        </div>

        <!-- بطاقة أيام الحضور -->
        <div class="rounded-[22px] border border-slate-200/60 bg-white p-4 shadow-sm transition hover:shadow-md flex flex-col justify-between min-h-[130px]">
            <div class="flex items-start justify-between">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                    <i data-lucide="check-square" class="h-5 w-5"></i>
                </div>
                <p class="text-xs font-bold text-slate-400">أيام الحضور</p>
            </div>
            <div class="mt-4 text-right">
                <h4 class="text-xl font-black text-slate-900">0</h4>
                <p class="text-[10px] text-slate-400 mt-0.5 font-medium">هذا الأسبوع</p>
            </div>
        </div>

        <!-- بطاقة الطلبات المعلقة -->
        <div class="rounded-[22px] border border-slate-200/60 bg-white p-4 shadow-sm transition hover:shadow-md flex flex-col justify-between min-h-[130px]">
            <div class="flex items-start justify-between">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-rose-50 text-rose-600">
                    <i data-lucide="alert-circle" class="h-5 w-5"></i>
                </div>
                <p class="text-xs font-bold text-slate-400">الطلبات المعلقة</p>
            </div>
            <div class="mt-4 text-right">
                <h4 class="text-xl font-black text-slate-900">0</h4>
                <p class="text-[10px] text-slate-400 mt-0.5 font-medium">طلب بانتظار الموافقة</p>
            </div>
        </div>

    </div>

    <!-- 3. النوافذ السفلية المفصلة للبيانات الحركية -->
    <div class="grid grid-cols-1 gap-5 md:grid-cols-2 w-full">

        <div class="rounded-[22px] border border-slate-200/60 bg-white p-5 shadow-sm">
            <h4 class="text-sm font-bold text-slate-800 mb-4">رصيد الإجازات بالتفصيل</h4>
            <div class="flex items-center justify-between border-t border-slate-100 pt-3">
                <span class="text-xs font-bold text-slate-700">0 يوم</span>
                <span class="text-xs text-slate-400 font-medium">الرصيد المستخدم حتى الآن</span>
            </div>
        </div>

        <div class="rounded-[22px] border border-slate-200/60 bg-white p-5 shadow-sm">
            <h4 class="text-sm font-bold text-slate-800 mb-4">سجل الدوام الأخير</h4>
            <div class="flex items-center justify-between border-t border-slate-100 pt-3">
                <span class="text-xs font-bold text-slate-700">0 حركة</span>
                <span class="text-xs text-slate-400 font-medium">عرض كافة التحركات اليومية الحية</span>
            </div>
        </div>

    </div>

</div>
@endsection
