<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
<div class="flex min-h-screen w-full flex-col bg-gradient-to-br from-slate-50 to-blue-50" dir="ltr">
    <div class="flex flex-1 items-center justify-center p-4">
        <div class="w-full max-w-5xl rounded-[32px] border border-slate-200/70 bg-white/80 p-2 shadow-[0_25px_80px_-35px_rgba(15,23,42,0.15)] backdrop-blur-xl lg:flex lg:p-0">

            <!-- القسم الجانبي للصورة -->
            <div class="w-full rounded-r-[28px] lg:block lg:w-1/2">
                <img class="h-full w-full rounded-r-[28px] object-cover" src="https://githubusercontent.com" alt="leftSideImage">
            </div>

            <!-- قسم النموذج (Form) -->
            <div class="flex w-full flex-col items-center justify-center p-8 lg:w-1/2">
                <form id="otpForm" class="flex w-full max-w-sm flex-col items-center justify-center" method="POST" action="{{ route('otp.verify') }}">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">

                    <h2 class="text-3xl font-black text-slate-900">أدخل رمز التحقق</h2>
                    <p class="mt-2 text-sm text-slate-500 text-center">تم إرسال رمز مكوّن من 6 أرقام إلى <span class="font-semibold text-slate-700">{{ $email }}</span></p>

                    <!-- حاوية الخانات معدلة لتعمل من اليسار لليمين مع الحفاظ على التناسق البصري -->
                    <div class="mt-8 flex flex-row-reverse items-center justify-center gap-3" dir="rtl">
                        @for($i = 0; $i < 6; $i++)
                            <input type="text" maxlength="1"
                                class="otp-input w-12 h-12 text-center text-xl font-bold border border-black/10 rounded-full bg-slate-50 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all shadow-sm"
                                name="code[]" inputmode="numeric" pattern="[0-9]*" required autocomplete="one-time-code">
                        @endfor
                        <input type="hidden" name="code" id="otpCode">
                    </div>

                    <!-- رسالة خطأ التحقق من جهة الفرونت اند -->
                    <div id="frontendError" class="mt-4 w-full text-sm font-semibold text-rose-600 text-right hidden">
                        * يرجى إدخال رمز التحقق كاملاً المكون من 6 أرقام.
                    </div>

                    <!-- رسائل الأخطاء القادمة من السيرفر (لارافيل) -->
                    <x-input-error :messages="$errors->get('code')" class="mt-2 self-start" />

                    @if(session('error'))
                        <div class="mt-4 w-full rounded-full border border-rose-400/20 bg-rose-500/10 px-4 py-3 text-sm font-semibold text-rose-600 text-center">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="mt-4 w-full rounded-full border border-emerald-400/20 bg-emerald-500/10 px-4 py-3 text-sm font-semibold text-emerald-600 text-center">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- زر الإرسال -->
                    <button type="submit" class="mt-6 w-full rounded-full bg-gradient-to-l from-cyan-500 to-blue-600 py-3 text-sm font-semibold text-white shadow-lg transition hover:opacity-90 active:scale-[0.98]">
                        تحقق من الرمز
                    </button>

                    <!-- روابط إعادة الإرسال وتعديل البيانات -->
                    <div class="mt-6 flex items-center gap-2">
                        <button type="button" onclick="document.getElementById('resendForm').submit();" class="text-sm text-blue-600 hover:underline cursor-pointer">إعادة الإرسال</button>
                        <span class="text-slate-300">|</span>
                        <a href="{{ route('login') }}" class="text-sm text-slate-500 hover:underline">تعديل البريد الإلكتروني</a>
                    </div>
                </form>

                <!-- نموذج إعادة الإرسال المنفصل لتجنب تداخل الـ Forms -->
                <form id="resendForm" method="POST" action="{{ route('otp.resend') }}" class="hidden">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">
                </form>
            </div>
        </div>
    </div>

    <!-- كود الجافاسكربت المطور لمعالجة الاتجاه، اللصق، والفحص -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputs = document.querySelectorAll('.otp-input');
        const form = document.getElementById('otpForm');
        const frontendError = document.getElementById('frontendError');
        const hiddenInput = document.getElementById('otpCode');

        // 1. معالجة حركة مؤشر الكتابة والمدخلات في الخانات
        inputs.forEach((input, idx) => {
            input.addEventListener('input', function(e) {
                // الفحص: قبول الأرقام فقط وحذف أي محرف آخر فوراً
                e.target.value = e.target.value.replace(/[^0-9]/g, '');

                // الانتقال التلقائي للخانة التالية جهة اليمين
                if (e.target.value.length >= 1 && idx < inputs.length - 1) {
                    inputs[idx + 1].focus();
                }
                updateHiddenCode();
            });

            // الرجوع التلقائي عند الضغط على Backspace والخانة فارغة
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && e.target.value.length === 0 && idx > 0) {
                    inputs[idx - 1].focus();
                }
            });

            // ميزة اللصق الذكي لكامل الرمز
            input.addEventListener('paste', function(e) {
                e.preventDefault();
                const pasteData = (e.clipboardData || window.clipboardData).getData('text').trim();

                // التأكد من أن النص المنسوخ عبارة عن 6 أرقام فقط
                if (/^\d{6}$/.test(pasteData)) {
                    inputs.forEach((numInput, numIdx) => {
                        numInput.value = pasteData[numIdx];
                    });
                    inputs[inputs.length - 1].focus();
                    updateHiddenCode();
                    frontendError.classList.add('hidden');
                }
            });
        });

        // تجميع الأرقام في حقل مخفي واحد
        function updateHiddenCode() {
            const code = Array.from(inputs).map(i => i.value).join('');
            hiddenInput.value = code;
        }

        // 2. الفحص من جهة الفرونت اند (Frontend Validation) عند إرسال النموذج
        form.addEventListener('submit', function(e) {
            updateHiddenCode();
            const fullCode = hiddenInput.value;

            // التحقق من أن الطول الإجمالي يساوي 6 أرقام تماماً
            if (fullCode.length !== 6 || /[^0-9]/.test(fullCode)) {
                e.preventDefault(); // منع إرسال البيانات للسيرفر
                frontendError.classList.remove('hidden'); // إظهار رسالة الخطأ للمستخدم

                // إضافة تأثير بصري أحمر للخانات الفارغة لتنبيه العميل
                inputs.forEach(input => {
                    if(input.value === '') {
                        input.classList.add('border-rose-500', 'bg-rose-50');
                        setTimeout(() => input.classList.remove('border-rose-500', 'bg-rose-50'), 3000);
                    }
                });
            } else {
                frontendError.classList.add('hidden');
            }
        });
    });
    </script>
</div>
