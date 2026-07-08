<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
<div class="flex min-h-screen w-full flex-col bg-gradient-to-br from-slate-50 to-blue-50" dir="rtl">
    <div class="flex flex-1 items-center justify-center p-4">
        <!-- الحاوية الرئيسية بتصميم زجاجي ناعم -->
        <div class="w-full max-w-5xl rounded-[32px] border border-slate-200/60 bg-white/70 p-3 shadow-[0_30px_100px_-40px_rgba(15,23,42,0.12)] backdrop-blur-xl lg:flex lg:p-0 overflow-hidden">

            <!-- قسم الصورة الجانبية المطور -->
            <div class="w-full hidden lg:block lg:w-1/2 relative group">
                <div class="absolute inset-0 bg-gradient-to-tr from-blue-600/10 to-transparent mix-blend-overlay z-10"></div>
                <img class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-102" src="https://raw.githubusercontent.com/prebuiltui/prebuiltui/main/assets/login/leftSideImage.png" alt="leftSideImage">
            </div>

            <!-- قسم النموذج والمُدخلات -->
            <div class="flex w-full flex-col items-center justify-center p-8 py-12 lg:w-1/2">
                <form class="flex w-full max-w-sm flex-col" method="POST" action="{{ route('login.submit') }}">
                    @csrf

                    <!-- رأس الصفحة -->
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-black text-slate-900 tracking-tight">تسجيل الدخول</h2>
                        <p class="mt-2.5 text-sm text-slate-500">مرحباً بك مجدداً! يرجى تسجيل الدخول للمتابعة</p>
                    </div>

                    <!-- مساحة حقول الإدخال والتحقق مع هوامش منظمة -->
                    <div class="space-y-4">

                        <!-- حقل البريد الإلكتروني -->
                        <div class="space-y-1.5">
                            <div class="flex w-full items-center gap-3 border border-slate-200 bg-white/60 rounded-2xl px-4 py-3.5 focus-within:border-blue-500 focus-within:ring-4 focus-within:ring-blue-500/10 transition-all group">
                                <i data-lucide="mail" class="h-4 w-4 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                                <input type="email" placeholder="البريد الإلكتروني" class="flex-1 bg-transparent text-sm text-slate-800 outline-none placeholder:text-slate-400 font-medium" required name="email" value="{{ old('email') }}" autofocus autocomplete="username">
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="text-xs text-rose-500 pr-2" />
                        </div>

                        <!-- حقل كلمة المرور -->
                        <div class="space-y-1.5">
                            <div class="flex w-full items-center gap-3 border border-slate-200 bg-white/60 rounded-2xl px-4 py-3.5 focus-within:border-blue-500 focus-within:ring-4 focus-within:ring-blue-500/10 transition-all group">
                                <i data-lucide="lock" class="h-4 w-4 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                                <input type="password" placeholder="كلمة المرور" class="flex-1 bg-transparent text-sm text-slate-800 outline-none placeholder:text-slate-400 font-medium" required name="password" autocomplete="current-password">
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="text-xs text-rose-500 pr-2" />
                        </div>

                    </div>

                    <!-- خيارات التذكر واستعادة الحساب -->
                    <div class="mt-5 flex w-full items-center justify-between text-slate-500 px-1">
                        <label class="flex cursor-pointer items-center gap-2 group select-none">
                            <input class="h-4 w-4 rounded-md border-slate-300 text-blue-600 focus:ring-blue-500/20 accent-blue-600 cursor-pointer" type="checkbox" id="checkbox" name="remember">
                            <span class="text-sm text-slate-600 group-hover:text-slate-900 transition-colors">تذكرني</span>
                        </label>
                        <a class="text-sm text-blue-600 font-medium hover:text-blue-700 hover:underline transition-colors" href="{{ route('password.request') }}">نسيت كلمة المرور؟</a>
                    </div>

                    <!-- زر إرسال النموذج -->
                    <button type="submit" class="mt-7 w-full rounded-2xl bg-gradient-to-l from-cyan-500 to-blue-600 py-3.5 text-sm font-bold text-white shadow-lg shadow-blue-500/10 transition-all hover:opacity-95 active:scale-[0.99] cursor-pointer">
                        تسجيل الدخول
                    </button>

                    <!-- التوجيه لإنشاء حساب -->
                    <p class="mt-6 text-sm text-slate-500 text-center">
                        لا تملك حساباً؟
                        <a class="font-bold text-blue-600 hover:text-blue-700 hover:underline transition-colors" href="{{ route('register') }}">إنشاء حساب جديد</a>
                    </p>
                </form>
            </div>

        </div>
    </div>
</div>
