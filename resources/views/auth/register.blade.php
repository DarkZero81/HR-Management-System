<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
<div class="flex min-h-screen w-full flex-col bg-gradient-to-br from-slate-50 to-blue-50" dir="rtl">
    <div class="flex flex-1 items-center justify-center p-4">
        <div class="w-full max-w-5xl rounded-[32px] border border-slate-200/70 bg-white/80 p-2 shadow-[0_25px_80px_-35px_rgba(15,23,42,0.15)] backdrop-blur-xl lg:flex lg:p-0">
            <div class="w-full rounded-l-[28px] lg:block lg:w-1/2">
                <img class="h-full w-full rounded-l-[28px] object-cover" src="https://raw.githubusercontent.com/prebuiltui/prebuiltui/main/assets/login/leftSideImage.png" alt="leftSideImage">
            </div>
            <div class="flex w-full flex-col items-center justify-center p-8 lg:w-1/2">
                <form class="flex w-full max-w-sm flex-col items-center justify-center" method="POST" action="{{ route('register') }}" id="registerForm">
                    @csrf

                    <h2 class="text-3xl font-black text-slate-900">إنشاء حساب جديد</h2>
                    <p class="mt-2 text-sm text-slate-500">أنشئ حسابك للوصول إلى البوابة الذاتية</p>

                    <div class="mt-8 flex w-full items-center gap-2 border border-black/10 rounded-full px-3 py-3">
                        <i data-lucide="user" class="h-4 w-4 text-slate-400"></i>
                        <input type="text" placeholder="الاسم الكامل" class="flex-1 bg-transparent text-sm text-slate-700 outline-none placeholder:text-slate-400" required name="name" value="{{ old('name') }}">
                    </div>
                    <x-input-error :messages="$errors->get('name')" class="mt-2 self-start" />

                    <div class="my-2 flex w-full items-center gap-2 border border-black/10 rounded-full px-3 py-3">
                        <i data-lucide="mail" class="h-4 w-4 text-slate-400"></i>
                        <input type="email" placeholder="البريد الإلكتروني" class="flex-1 bg-transparent text-sm text-slate-700 outline-none placeholder:text-slate-400" required name="email" value="{{ old('email') }}">
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2 self-start" />

                    <div class="my-2 flex w-full items-center gap-2 border border-black/10 rounded-full px-3 py-3">
                        <i data-lucide="lock" class="h-4 w-4 text-slate-400"></i>
                        <input type="password" placeholder="كلمة المرور" class="flex-1 bg-transparent text-sm text-slate-700 outline-none placeholder:text-slate-400" required name="password">
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2 self-start" />

                    <div class="my-2 flex w-full items-center gap-2 border border-black/10 rounded-full px-3 py-3">
                        <i data-lucide="lock" class="h-4 w-4 text-slate-400"></i>
                        <input type="password" placeholder="تأكيد كلمة المرور" class="flex-1 bg-transparent text-sm text-slate-700 outline-none placeholder:text-slate-400" required name="password_confirmation">
                    </div>

                    <button type="submit" class="mt-6 w-full rounded-full bg-gradient-to-l from-cyan-500 to-blue-600 py-3 text-sm font-semibold text-white shadow-lg transition hover:opacity-90">
                        إنشاء حساب
                    </button>

                    <p class="mt-4 text-sm text-slate-500">لديك حساب؟ <a class="font-semibold text-blue-600 hover:underline" href="{{ route('login') }}">تسجيل الدخول</a></p>
                </form>
            </div>
        </div>
    </div>
</div>