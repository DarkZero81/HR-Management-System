{{-- resources/views/profile/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'تعديل الملف الشخصي')

@section('content')
<div class="max-w-2xl mx-auto">
    @if(session('status') === 'profile-updated')
        <div class="mb-6 rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-4 py-3 text-sm font-semibold text-emerald-200 flex items-center gap-2">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            تم تحديث الملف الشخصي بنجاح
        </div>
    @endif

    <div class="bg-slate-800/50 rounded-2xl p-6 border border-white/10">
        <h2 class="text-2xl font-bold text-white mb-6">تعديل الملف الشخصي</h2>

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PATCH')

            <div class="flex flex-col items-center gap-4">
                <div class="relative">
                    <div class="w-24 h-24 rounded-full overflow-hidden bg-slate-700 border-2 border-white/10 flex items-center justify-center">
                        @if($user->avatar)
                            <img src="{{ Storage::url($user->avatar) }}" alt="الصورة الشخصية" class="w-full h-full object-cover">
                        @else
                            <i data-lucide="user" class="w-10 h-10 text-slate-400"></i>
                        @endif
                    </div>
                </div>

                <div class="w-full">
                    <label for="avatar" class="block text-sm font-medium text-slate-300 mb-2">الصورة الشخصية</label>
                    <input type="file" name="avatar" id="avatar"
                        class="w-full rounded-xl border-white/10 bg-slate-900 text-white px-4 py-2.5 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-500 file:text-white hover:file:bg-blue-600 focus:ring-2 focus:ring-blue-500">
                    @error('avatar')
                        <p class="text-rose-400 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="name" class="block text-sm font-medium text-slate-300 mb-2">الاسم</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                    class="w-full rounded-xl border-white/10 bg-slate-900 text-white px-4 py-2.5 focus:ring-2 focus:ring-blue-500">
                @error('name')
                    <p class="text-rose-400 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-slate-300 mb-2">البريد الإلكتروني</label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                    class="w-full rounded-xl border-white/10 bg-slate-900 text-white px-4 py-2.5 focus:ring-2 focus:ring-blue-500">
                @error('email')
                    <p class="text-rose-400 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-3 pt-4">
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-l from-blue-600 to-teal-500 text-white rounded-xl font-bold hover:opacity-90 transition">
                    <i data-lucide="save" class="w-5 h-5"></i>
                    حفظ التغييرات
                </button>
                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-slate-700 hover:bg-slate-600 text-white rounded-xl font-semibold transition-all border border-white/10">
                    <i data-lucide="x" class="w-5 h-5"></i>
                    إلغاء
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
