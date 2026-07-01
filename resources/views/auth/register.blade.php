<div class="flex h-[700px] w-full">
    <div class="w-full hidden md:inline-block">
        <img class="h-full" src="https://raw.githubusercontent.com/prebuiltui/prebuiltui/main/assets/login/leftSideImage.png" alt="leftSideImage">
    </div>

    <div class="w-full flex flex-col items-center justify-center">
        <form class="md:w-96 w-80 flex flex-col items-center justify-center" id="registerForm" method="POST" action="{{ route('register') }}">
            @csrf

            <h2 class="text-4xl text-gray-900 font-medium">Create account</h2>
            <p class="text-sm text-gray-500/90 mt-3">Join us and get started today</p>

            <button type="button" class="w-full mt-8 bg-gray-500/10 flex items-center justify-center h-12 rounded-full">
                <img src="https://raw.githubusercontent.com/prebuiltui/prebuiltui/main/assets/login/googleLogo.svg" alt="googleLogo">
            </button>

            <div class="flex items-center gap-4 w-full my-5">
                <div class="w-full h-px bg-gray-300/90"></div>
                <p class="w-full text-nowrap text-sm text-gray-500/90">or sign up with email</p>
                <div class="w-full h-px bg-gray-300/90"></div>
            </div>

            <div class="flex items-center w-full gap-2">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" stroke="#6B7280" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <circle cx="12" cy="7" r="4" stroke="#6B7280" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <input type="text" placeholder="Full Name" class="bg-transparent text-gray-500/80 placeholder-gray-500/80 outline-none text-sm w-full h-full" required name="name" value="{{ old('name') }}" autofocus autocomplete="name">                 
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-2 self-start" />

            <div class="flex items-center mt-4 w-full gap-2">
                <svg width="16" height="11" viewBox="0 0 16 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M0 .55.571 0H15.43l.57.55v9.9l-.571.55H.57L0 10.45zm1.143 1.138V9.9h13.714V1.69l-6.503 4.8h-.697zM13.749 1.1H2.25L8 5.356z" fill="#6B7280"/>
                </svg>
                <input type="email" placeholder="Email id" class="bg-transparent text-gray-500/80 placeholder-gray-500/80 outline-none text-sm w-full h-full" required name="email" value="{{ old('email') }}" autocomplete="username">                 
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 self-start" />

            <div class="flex items-center mt-4 w-full gap-2">
                <svg width="14" height="16" viewBox="0 0 14 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M13 6.5A3.5 3.5 0 009.5 3h-5A3.5 3.5 0 001 6.5v7A3.5 3.5 0 004.5 17h5a3.5 3.5 0 003.5-3.5v-7zM4.5 5A1.5 1.5 0 016 3.5h2A1.5 1.5 0 019.5 5V6h-5V5zM12 10.5a1.5 1.5 0 01-1.5 1.5h-5A1.5 1.5 0 014 10.5V8h8v2.5z" fill="#6B7280"/>
                </svg>
                <input type="password" placeholder="Password" class="bg-transparent text-gray-500/80 placeholder-gray-500/80 outline-none text-sm w-full h-full" required name="password" autocomplete="new-password">
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 self-start" />

            <div class="flex items-center mt-4 w-full gap-2">
                <svg width="14" height="16" viewBox="0 0 14 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M13 6.5A3.5 3.5 0 009.5 3h-5A3.5 3.5 0 001 6.5v7A3.5 3.5 0 004.5 17h5a3.5 3.5 0 003.5-3.5v-7zM4.5 5A1.5 1.5 0 016 3.5h2A1.5 1.5 0 019.5 5V6h-5V5zM12 10.5a1.5 1.5 0 01-1.5 1.5h-5A1.5 1.5 0 014 10.5V8h8v2.5z" fill="#6B7280"/>
                </svg>
                <input type="password" placeholder="Confirm Password" class="bg-transparent text-gray-500/80 placeholder-gray-500/80 outline-none text-sm w-full h-full" required name="password_confirmation" autocomplete="new-password">
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 self-start" />

            <button type="submit" class="mt-8 w-full h-11 rounded-full text-white bg-indigo-500 hover:opacity-90 transition-opacity" id="registerBtn">
                Register
            </button>

            <p class="text-gray-500/90 text-sm mt-4">Already have an account? <a class="text-indigo-400 hover:underline" href="{{ route('login') }}">Sign in</a></p>
        </form>
    </div>
</div>
