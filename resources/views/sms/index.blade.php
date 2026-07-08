@extends('layouts.app')

@section('title', 'الرسائل')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl md:text-3xl font-black text-white">الرسائل النصية</h1>
            <p class="text-sm text-slate-400 mt-1">إرسال وإدارة الرسائل للموظفين</p>
        </div>
        <a href="{{ route('sms.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-500 to-teal-400 text-white rounded-xl font-bold">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>إرسال رسالة جديدة</span>
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
        <p class="text-slate-500 text-center py-8">سجل الرسائل سيظهر هنا عند تفعيل التكامل مع Twilio/WhatsApp API</p>
    </div>
</div>
@endsection