<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
<div class="flex min-h-screen w-full flex-col bg-gradient-to-br from-slate-50 to-blue-50">
    <div class="flex flex-1 items-center justify-center p-4">
        <div class="w-full max-w-5xl rounded-[32px] border border-slate-200/70 bg-white/80 p-2 shadow-[0_25px_80px_-35px_rgba(15,23,42,0.15)] backdrop-blur-xl lg:flex lg:p-0">
            <div class="w-full rounded-l-[28px] lg:block lg:w-1/2">
                <img class="h-full w-full rounded-l-[28px] object-cover" src="https://raw.githubusercontent.com/prebuiltui/prebuiltui/main/assets/login/leftSideImage.png" alt="leftSideImage">
            </div>
            <div class="flex w-full flex-col items-center justify-center p-8 lg:w-1/2">
                <form class="flex w-full max-w-sm flex-col items-center justify-center" id="loginForm" method="POST" action="{{ route('login') }}">
                    @csrf

                    <h2 class="text-3xl font-black text-slate-900">تسجيل الدخول</h2>
                    <p class="mt-2 text-sm text-slate-500">مرحباً بك! يرجى تسجيل الدخول للمتابعة</p>

                    <div class="mt-8 flex w-full items-center gap-3 rounded-full border border-slate-200 bg-slate-50 px-4 py-3">
                        <img src="https://raw.githubusercontent.com/prebuiltui/prebuiltui/main/assets/login/googleLogo.svg" alt="googleLogo" class="h-5 w-5">
                        <span class="text-sm font-medium text-slate-700">تسجيل الدخول باستخدام Google</span>
                    </div>

                    <div class="my-6 flex w-full items-center gap-4">
                        <div class="h-px flex-1 bg-slate-200"></div>
                        <p class="text-sm text-slate-500">أو تسجيل الدخول بالبريد</p>
                        <div class="h-px flex-1 bg-slate-200"></div>
                    </div>

                    <div class="flex w-full items-center gap-2">
                        <i data-lucide="mail" class="h-4 w-4 text-slate-400"></i>
                        <input type="email" placeholder="البريد الإلكتروني" class="flex-1 bg-transparent text-sm text-slate-700 outline-none placeholder:text-slate-400" required name="email" value="{{ old('email') }}" autofocus autocomplete="username">
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2 self-start" />

                    <div class="mt-4 flex w-full items-center gap-2">
                        <i data-lucide="lock" class="h-4 w-4 text-slate-400"></i>
                        <input type="password" placeholder="كلمة المرور" class="flex-1 bg-transparent text-sm text-slate-700 outline-none placeholder:text-slate-400" required name="password" autocomplete="current-password">
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2 self-start" />

                    <div class="mt-4 flex w-full items-center justify-between text-slate-500">
                        <label class="flex cursor-pointer items-center gap-2">
                            <input class="h-4 w-4" type="checkbox" id="checkbox" name="remember">
                            <span class="text-sm">تذكرني</span>
                        </label>
                        <a class="text-sm text-blue-600 hover:underline" href="{{ route('password.request') }}">نسيت كلمة المرور؟</a>
                    </div>

                    <button type="submit" class="mt-6 w-full rounded-full bg-gradient-to-l from-cyan-500 to-blue-600 py-3 text-sm font-semibold text-white shadow-lg transition hover:opacity-90">
                        تسجيل الدخول
                    </button>

                    <p class="mt-4 text-sm text-slate-500">لا تملك حساباً؟ <a class="font-semibold text-blue-600 hover:underline" href="{{ route('register') }}">إنشاء حساب جديد</a></p>
                </form>
            </div>
        </div>
    </div>
</div>
