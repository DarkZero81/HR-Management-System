@extends('layouts.auth')

@section('title', 'تسجيل الدخول')

@section('content')
    <form method="POST" action="{{ route('login.submit') }}" class="space-y-5">
        @csrf

        <div class="text-center mb-6">
            <h2 class="text-xl font-black text-slate-900 dark:text-white">تسجيل الدخول</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">مرحباً بك مجدداً! يرجى تسجيل الدخول للمتابعة</p>
        </div>

        <div class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">البريد الإلكتروني</label>
                <div class="relative">
                    <i data-lucide="mail" class="absolute right-3 top-3 h-4 w-4 text-slate-400"></i>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                        class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 pr-10 pl-4 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all"
                        placeholder="name@example.com">
                </div>
                <x-input-error :messages="$errors->get('email')" class="text-rose-500 text-xs mt-1" />
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">كلمة المرور</label>
                <div class="relative">
                    <i data-lucide="lock" class="absolute right-3 top-3 h-4 w-4 text-slate-400"></i>
                    <input type="password" name="password" id="loginPassword" required autocomplete="current-password"
                        class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 pr-10 pl-10 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all"
                        placeholder="••••••••">
                    <button type="button" onclick="togglePassword('loginPassword', this)" class="absolute left-3 top-2.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                        <i data-lucide="eye" class="h-4 w-4"></i>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="text-rose-500 text-xs mt-1" />
            </div>
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 cursor-pointer select-none">
                <input type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-cyan-500 focus:ring-cyan-500">
                <span class="text-sm text-slate-600 dark:text-slate-400">تذكرني</span>
            </label>
            <a href="{{ route('password.request') }}" class="text-sm font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors">
                نسيت كلمة المرور؟
            </a>
        </div>

        <button type="submit" class="w-full rounded-xl bg-gradient-to-l from-cyan-500 to-blue-600 py-3 text-sm font-bold text-white shadow-lg shadow-blue-600/20 transition-all hover:opacity-95 active:scale-[0.99] cursor-pointer">
            تسجيل الدخول
        </button>

        <p class="text-center text-sm text-slate-600 dark:text-slate-400">
            لا تملك حساباً؟
         @if (auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'hr'))
                <a href="{{ route('register') }}" class="font-bold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors">
                إنشاء حساب جديد
            </a>
         @else
            <p class="font-bold text-slate-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors px-4 text-xs mt-0">تواصل مع مدير النظام او المدير المسؤول ليقوم بانشاء حساب لك.</p>
         @endif
    </form>
@endsection
