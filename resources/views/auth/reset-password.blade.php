<x-guest-layout>
    <div class="mb-4 text-sm text-slate-600">
        أدخل كلمة المرور جديدة reunited.
    </div>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="mt-4">
            <x-input-label for="email" :value="__('البريد الإلكتروني')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('كلمة المرور الجديدة')" />
            <div class="relative">
                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                <button type="button" onclick="togglePassword('password', this)" class="absolute right-2 top-2.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                    <i data-lucide="eye" class="h-4 w-4"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('تأكيد كلمة المرور')" />
            <div class="relative">
                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                <button type="button" onclick="togglePassword('password_confirmation', this)" class="absolute right-2 top-2.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                    <i data-lucide="eye" class="h-4 w-4"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('إعادة تعيين كلمة المرور') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
