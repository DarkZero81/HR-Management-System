<x-guest-layout>
    <div class="mb-4 text-sm text-slate-600">
        شكراً لتسجيلك! قبل البدء، يرجى التحقق من بريدك الإلكتروني بالنقر على الرابط الذي أرسلناه لك. إذا لم تستلم البريد، سنرسل لك رابطاً آخر.
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600">
            تم إرسال رابط تحقق جديد إلى البريد الإلكتروني الذي أدخلته أثناء التسجيل.
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button>
                    {{ __('إعادة إرسال رابط التحقق') }}
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="underline text-sm text-slate-600 hover:text-slate-800 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                {{ __('تسجيل الخروج') }}
            </button>
        </form>
    </div>
</x-guest-layout>
