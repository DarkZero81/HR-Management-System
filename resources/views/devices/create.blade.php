@extends('layouts.app')
@section('title', 'إضافة جهاز جديد')
@section('content')
<div class="max-w-3xl mx-auto space-y-6 px-4 py-4" dir="rtl">
    <div class="border-b border-white/5 pb-4">
        <p class="text-xs font-black uppercase tracking-[0.35em] text-blue-400 dark:text-cyan-400">الأجهزة</p>
        <h1 class="text-3xl font-bold text-slate-800">إضافة جهاز جديد</h1>
        <p class="text-sm text-slate-400 dark:text-slate-500 mt-1">أدخل بيانات جهاز البصمة أو الحضور.</p>
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

    <form method="POST" action="{{ route('devices.store') }}" class="employee-form-card rounded-[28px] border border-white/10 dark:border-white/5 p-6 space-y-5 shadow-2xl backdrop-blur-md">
        @csrf

        <div>
            <label for="device_name" class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">اسم الجهاز <span class="text-rose-500">*</span></label>
            <input type="text" name="device_name" id="device_name" value="{{ old('device_name') }}" required placeholder="مثال: جهاز البصمة الرئيسي" class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 px-3 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all">
            @error('device_name')
                <p class="text-rose-500 text-sm mt-1.5 flex items-center gap-1.5">
                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div>
            <label for="ip_address" class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">عنوان IP <span class="text-rose-500">*</span></label>
            <input type="text" name="ip_address" id="ip_address" value="{{ old('ip_address') }}" required placeholder="192.168.1.100" class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 px-3 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all">
            @error('ip_address')
                <p class="text-rose-500 text-sm mt-1.5 flex items-center gap-1.5">
                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div>
            <label for="status" class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">الحالة</label>
            <select name="status" id="status" class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 px-8 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all cursor-pointer">
                <option value="online" {{ old('status') === 'online' ? 'selected' : '' }} class="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200">نشط</option>
                <option value="offline" {{ old('status') === 'offline' ? 'selected' : '' }} class="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200">متوقف</option>
            </select>
            @error('status')
                <p class="text-rose-500 text-sm mt-1.5 flex items-center gap-1.5">
                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div class="flex items-center justify-end gap-2.5 border-t border-slate-200 dark:border-white/5 pt-4">
            <a href="{{ route('devices.index') }}" class="rounded-xl bg-slate-200 hover:bg-slate-300 dark:bg-slate-800 dark:hover:bg-slate-700 border border-slate-300 dark:border-white/5 px-4 py-2.5 text-xs font-bold text-slate-700 dark:text-slate-300 transition active:scale-95">إلغاء والعودة</a>
            <button type="submit" class="rounded-xl bg-gradient-to-l from-cyan-500 to-blue-600 hover:opacity-95 px-4 py-2.5 text-xs font-bold text-white shadow-lg shadow-blue-600/10 transition active:scale-95 cursor-pointer">حفظ الجهاز</button>
        </div>
    </form>
</div>
@endsection
