@extends('layouts.auth')

@section('title', 'أدخل رمز التحقق')

@section('content')
    <form class="flex w-full max-w-sm flex-col items-center justify-center" method="POST" action="{{ route('otp.verify') }}" dir="ltr">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">

        <h2 class="text-3xl font-black text-slate-900 text-center w-full">أدخل رمز التحقق</h2>
        <p class="mt-2 text-sm text-slate-500 text-center w-full">تم إرسال رمز مكوّن من 6 أرقام إلى {{ $email }}</p>

        <div class="mt-8 flex items-center justify-center gap-3" dir="ltr">
            @for($i = 0; $i < 6; $i++)
                <input type="text" maxlength="1"
                    class="otp-input w-12 h-12 text-center text-xl font-bold border border-black/10 rounded-full bg-slate-50 outline-none focus:border-blue-500 transition-all"
                    name="code[]" inputmode="numeric" pattern="[0-9]*" required autocomplete="one-time-code">
            @endfor
            <input type="hidden" name="code" id="otpCode">
        </div>

        <x-input-error :messages="$errors->get('code')" class="mt-2 self-start" />

        @if(session('error'))
            <div class="mt-4 w-full rounded-full border border-rose-400/20 bg-rose-500/10 px-4 py-3 text-sm font-semibold text-rose-200">
                {{ session('error') }}
            </div>
        @endif

        <button type="submit" class="mt-6 w-full rounded-full bg-gradient-to-l from-cyan-500 to-blue-600 py-3 text-sm font-semibold text-white shadow-lg transition hover:opacity-90">
            تحقق من الرمز
        </button>

        <div class="mt-4 flex items-center gap-2">
            <form method="POST" action="{{ route('otp.resend') }}" class="inline">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">
                <button type="submit" class="text-sm text-blue-600 hover:underline">إعادة الإرسال</button>
            </form>
            <span class="text-slate-300">|</span>
            <a href="{{ route('login') }}" class="text-sm text-slate-500 hover:underline">تعديل البريد الإلكتروني</a>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.otp-input');

            inputs.forEach((input, idx) => {
                input.addEventListener('input', function(e) {
                    if (e.target.value.length >= 1 && idx < 5) {
                        inputs[idx + 1].focus();
                    }
                    updateHiddenCode();
                });

                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && e.target.value.length === 0 && idx > 0) {
                        inputs[idx - 1].focus();
                    }
                });
            });

            function updateHiddenCode() {
                const code = Array.from(inputs).map(i => i.value).join('');
                document.getElementById('otpCode').value = code;
            }
        });
    </script>
@endsection
