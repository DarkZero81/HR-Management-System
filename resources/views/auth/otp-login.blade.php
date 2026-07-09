@extends('layouts.auth')

@section('title', 'تسجيل الدخول بالرمز')

@section('content')
    <form class="flex w-full max-w-sm flex-col items-center justify-center" method="POST" action="{{ route('otp.send') }}">
        @csrf

        <h2 class="text-3xl font-black text-slate-900">تسجيل الدخول بالرمز</h2>
        <p class="mt-2 text-sm text-slate-500">أدخل بريدك الإلكتروني لاستلام رمز التحقق</p>

        <div class="mt-8 flex w-full items-center gap-2 border border-black/10 rounded-full px-3 py-3">
            <i data-lucide="mail" class="h-4 w-4 text-slate-400"></i>
            <input type="email" placeholder="البريد الإلكتروني" class="flex-1 bg-transparent text-sm text-slate-700 outline-none placeholder:text-slate-400" required name="email" value="{{ old('email') }}" autofocus>
        </div>
        <x-input-error :messages="$errors->get('email')" class="mt-2 self-start" />

        @if(session('error'))
            <div class="mt-4 w-full rounded-full border border-rose-400/20 bg-rose-500/10 px-4 py-3 text-sm font-semibold text-rose-200">
                <i data-lucide="alert-circle" class="h-4 w-4 inline-block mr-1"></i>
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="mt-4 w-full rounded-full border border-emerald-400/20 bg-emerald-500/10 px-4 py-3 text-sm font-semibold text-emerald-200">
                <i data-lucide="check-circle" class="h-4 w-4 inline-block mr-1"></i>
                {{ session('success') }}
            </div>
        @endif

        <button type="submit" class="mt-6 w-full rounded-full bg-gradient-to-l from-cyan-500 to-blue-600 py-3 text-sm font-semibold text-white shadow-lg transition hover:opacity-90">
            إرسال رمز التحقق
        </button>

        <p class="mt-4 text-sm text-slate-500">تريد تسجيل الدخول بالبريد وكلمة المرور؟ <a class="font-semibold text-blue-600 hover:underline" href="{{ route('login') }}">تسجيل دخول عادي</a></p>
    </form>
@endsection
