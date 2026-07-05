{{-- resources/views/profile/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-slate-800/50 rounded-2xl p-6 border border-white/10">
    <h2 class="text-2xl font-bold text-white mb-6">تعديل الملف الشخصي</h2>

    <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
        @csrf
        @method('PATCH')

        {{-- حقل الاسم --}}
        <div>
            <label for="name" class="block text-sm font-medium text-slate-300 mb-1">الاسم</label>
            <input type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}"
                   class="w-full rounded-xl border-white/10 bg-slate-900 text-white px-4 py-2 focus:ring-2 focus:ring-blue-500">
            @error('name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- حقل البريد الإلكتروني --}}
        <div>
            <label for="email" class="block text-sm font-medium text-slate-300 mb-1">البريد الإلكتروني</label>
            <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}"
                   class="w-full rounded-xl border-white/10 bg-slate-900 text-white px-4 py-2 focus:ring-2 focus:ring-blue-500">
            @error('email') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center gap-4 pt-4">
            <button type="submit" class="px-6 py-2 bg-gradient-to-l from-blue-600 to-teal-500 text-white rounded-xl font-bold hover:opacity-90 transition">
                حفظ التغييرات
            </button>
            <a href="{{ route('dashboard') }}" class="text-slate-400 hover:text-white transition">إلغاء</a>
        </div>
    </form>
</div>
@endsection
