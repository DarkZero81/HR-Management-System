@extends('layouts.app')

@section('title', 'إضافة قسم جديد')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('departments.index') }}" class="p-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white transition-all border border-white/10">
            <i data-lucide="arrow-right" class="w-5 h-5"></i>
        </a>
        <div>
            <p class="text-xs font-black uppercase tracking-[0.35em] text-slate-400">الأقسام</p>
            <h1 class="text-2xl md:text-3xl font-black text-white mt-1">إضافة قسم جديد</h1>
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-4 py-3 text-sm font-semibold text-emerald-200 flex items-center gap-2">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-2xl border border-rose-400/20 bg-rose-500/10 px-4 py-3 text-sm font-semibold text-rose-200 flex items-center gap-2">
            <i data-lucide="alert-circle" class="w-5 h-5"></i>
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-slate-800 border border-white/10 rounded-2xl shadow-xl overflow-hidden">
        <div class="bg-gradient-to-l from-blue-600 to-teal-500 px-6 py-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center">
                <i data-lucide="plus" class="w-5 h-5 text-white"></i>
            </div>
            <div>
                <h5 class="text-white font-bold text-lg">بيانات القسم الجديد</h5>
                <p class="text-blue-100 text-xs">أدخل بيانات القسم أدناه</p>
            </div>
        </div>
        <div class="p-6">
            <form action="{{ route('departments.store') }}" method="POST" class="space-y-5">
                @csrf
                
                <div>
                    <label for="name" class="block text-sm font-semibold text-white mb-2">اسم القسم <span class="text-rose-400">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all">
                    @error('name')
                        <p class="text-rose-400 text-sm mt-1.5 flex items-center gap-1.5">
                            <i data-lucide="alert-circle" class="w-4 h-4"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-semibold text-white mb-2">الوصف</label>
                    <textarea name="description" id="description" rows="4"
                        class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all resize-none">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-rose-400 text-sm mt-1.5 flex items-center gap-1.5">
                            <i data-lucide="alert-circle" class="w-4 h-4"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="flex items-center gap-3 pt-4">
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-500 to-teal-400 hover:from-blue-600 hover:to-teal-500 text-white rounded-xl font-bold shadow-lg transition-all">
                        <i data-lucide="save" class="w-5 h-5"></i>
                        حفظ القسم
                    </button>
                    <a href="{{ route('departments.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-slate-700 hover:bg-slate-600 text-white rounded-xl font-semibold transition-all border border-white/10">
                        <i data-lucide="x" class="w-5 h-5"></i>
                        إلغاء
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
