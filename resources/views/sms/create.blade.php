@extends('layouts.app')
@section('title', 'إرسال رسالة')
@section('content')
<div class="max-w-3xl mx-auto space-y-6 px-4 py-4" dir="rtl">
    <div class="border-b border-white/5 pb-4">
        <p class="text-xs font-black uppercase tracking-[0.35em] text-blue-400 dark:text-cyan-400">الرسائل</p>
        <h1 class="text-2xl md:text-3xl font-black text-white dark:text-slate-900 mt-0.5">إرسال رسالة جديدة</h1>
        <p class="text-sm text-slate-400 dark:text-slate-500 mt-1">أرسل رسالة نصية أو واتساب للموظفين.</p>
    </div>

    @if(session('success'))
        <div class="rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-4 py-3 text-sm font-semibold text-emerald-200 flex items-center gap-2">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('sms.store') }}" class="employee-form-card rounded-[28px] border border-white/10 dark:border-white/5 p-6 space-y-5 shadow-2xl backdrop-blur-md">
        @csrf

        <div>
            <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">نوع المستلم <span class="text-rose-500">*</span></label>
            <select name="type" class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 px-8 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all cursor-pointer" onchange="toggleRecipients(this.value)">
                <option value="individual" class="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200">موظف محدد</option>
                <option value="department" class="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200">قسم محدد</option>
                <option value="all" class="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200">جميع الموظفين</option>
            </select>
        </div>

        <div id="individualRecipients">
            <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">الموظفون</label>
            <select name="recipients[]" multiple class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 px-8 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all cursor-pointer h-48">
                @foreach($employees as $emp)
                    <option value="{{ $emp->id }}" class="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200">{{ $emp->first_name }} {{ $emp->last_name }} - {{ $emp->phone ?? $emp->user->email }}</option>
                @endforeach
            </select>
        </div>

        <div id="departmentRecipients" class="hidden">
            <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">القسم</label>
            <select name="department_id" class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 px-8 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all cursor-pointer">
                @foreach(\App\Models\Department::all() as $dept)
                    <option value="{{ $dept->id }}" class="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200">{{ $dept->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">نص الرسالة <span class="text-rose-500">*</span></label>
            <textarea name="message" rows="5" class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 px-8 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all resize-none" placeholder="اكتب رسالتك هنا..."></textarea>
        </div>

        <div class="flex items-center justify-end gap-2.5 border-t border-slate-200 dark:border-white/5 pt-4">
            <a href="{{ route('sms.index') }}" class="rounded-xl bg-slate-200 hover:bg-slate-300 dark:bg-slate-800 dark:hover:bg-slate-700 border border-slate-300 dark:border-white/5 px-4 py-2.5 text-xs font-bold text-slate-700 dark:text-slate-300 transition active:scale-95">إلغاء والعودة</a>
            <button type="submit" class="rounded-xl bg-gradient-to-l from-cyan-500 to-blue-600 hover:opacity-95 px-4 py-2.5 text-xs font-bold text-white shadow-lg shadow-blue-600/10 transition active:scale-95 cursor-pointer inline-flex items-center gap-2">
                <i data-lucide="send" class="w-4 h-4"></i>
                إرسال الرسالة
            </button>
        </div>
    </form>
</div>

<script>
function toggleRecipients(type) {
    document.getElementById('individualRecipients').classList.toggle('hidden', type !== 'individual');
    document.getElementById('departmentRecipients').classList.toggle('hidden', type !== 'department');
}
</script>
@endsection
