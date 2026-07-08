@extends('layouts.app')

@section('title', 'إرسال رسالة')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl md:text-3xl font-black text-white">إرسال رسالة جديدة</h1>
            <p class="text-sm text-slate-400 mt-1">أرسل رسالة نصية أو واتساب للموظفين</p>
        </div>
        <a href="{{ route('sms.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-xl">
            <i data-lucide="arrow-right" class="w-4 h-4"></i>
            <span>العودة</span>
        </a>
    </div>

    @if(session('success'))
        <div class="rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-4 py-3 text-sm font-semibold text-emerald-200">
            <i data-lucide="check-circle" class="w-5 h-5 inline-block mr-1"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
        <form method="POST" action="{{ route('sms.store') }}" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">نوع المستلم</label>
                <select name="type" class="w-full rounded-xl border border-slate-200 px-4 py-2 focus:border-blue-500" onchange="toggleRecipients(this.value)">
                    <option value="individual">موظف محدد</option>
                    <option value="department">قسم محدد</option>
                    <option value="all">جميع الموظفين</option>
                </select>
            </div>

            <div id="individualRecipients">
                <label class="block text-sm font-medium text-slate-700 mb-2">الموظفون</label>
                <select name="recipients[]" multiple class="w-full rounded-xl border border-slate-200 px-4 py-2 h-48">
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}">{{ $emp->first_name }} {{ $emp->last_name }} - {{ $emp->phone ?? $emp->user->email }}</option>
                    @endforeach
                </select>
            </div>

            <div id="departmentRecipients" class="hidden">
                <label class="block text-sm font-medium text-slate-700 mb-2">القسم</label>
                <select name="department_id" class="w-full rounded-xl border border-slate-200 px-4 py-2">
                    @foreach(\App\Models\Department::all() as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">نص الرسالة</label>
                <textarea name="message" rows="4" class="w-full rounded-xl border border-slate-200 px-4 py-2 focus:border-blue-500" placeholder="اكتب رسالتك هنا..."></textarea>
            </div>

            <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-500 to-teal-400 text-white rounded-xl font-bold shadow-lg">
                <i data-lucide="send" class="w-4 h-4"></i>
                إرسال الرسالة
            </button>
        </form>
    </div>
</div>

<script>
function toggleRecipients(type) {
    document.getElementById('individualRecipients').classList.toggle('hidden', type !== 'individual');
    document.getElementById('departmentRecipients').classList.toggle('hidden', type !== 'department');
}
</script>
@endsection