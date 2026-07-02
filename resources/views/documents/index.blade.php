@extends('layouts.app')

@section('title', 'الوثائق')

@section('content')
<div class="space-y-6">
    <div class="rounded-[32px] border border-slate-200/70 bg-white/90 p-6 shadow-[0_25px_90px_-35px_rgba(15,23,42,0.12)] backdrop-blur">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.35em] text-slate-500">الوثائق</p>
                <h1 class="mt-2 text-3xl font-black text-slate-900">الوثائق والمستندات الرسمية</h1>
                <p class="mt-2 text-sm text-slate-600">قم برفع وحفظ الأوراق الرسمية للاحتفاظ بها ومشاركتها مع الإدارة.</p>
            </div>
            <a href="#" class="inline-flex items-center justify-center rounded-2xl bg-sky-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-sky-700">
                <i data-lucide="upload-cloud" class="ml-2 h-4 w-4"></i>
                رفع وثيقة جديدة
            </a>
        </div>
    </div>

    @if($documents->count())
        <div class="overflow-hidden rounded-[32px] border border-slate-200/70 bg-white/90 shadow-[0_25px_90px_-35px_rgba(15,23,42,0.12)]">
            <div class="flex items-center justify-between border-b border-slate-200/70 px-6 py-5">
                <div>
                    <p class="text-sm font-semibold text-slate-500">سجل المستندات</p>
                    <h2 class="text-lg font-black text-slate-900">المستندات المرفوعة</h2>
                </div>
                <span class="rounded-full bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700">{{ $documents->total() }} وثيقة</span>
            </div>
            <div class="overflow-x-auto p-6">
                <table class="min-w-full text-right text-sm">
                    <thead class="bg-slate-100 text-slate-600">
                        <tr>
                            <th class="px-5 py-4 font-semibold">الموظف</th>
                            <th class="px-5 py-4 font-semibold">نوع الوثيقة</th>
                            <th class="px-5 py-4 font-semibold">الرقم</th>
                            <th class="px-5 py-4 font-semibold">تاريخ الانتهاء</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @foreach($documents as $doc)
                            <tr class="transition hover:bg-slate-50">
                                <td class="px-5 py-4 text-slate-900">{{ $doc->employee->full_name ?? ($doc->employee->first_name ?? '-') }}</td>
                                <td class="px-5 py-4 text-slate-600">{{ ucfirst(str_replace('_', ' ', $doc->document_type)) }}</td>
                                <td class="px-5 py-4 text-slate-600">{{ $doc->document_number }}</td>
                                <td class="px-5 py-4 text-slate-600">{{ $doc->expiry_date }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="border-t border-slate-200/70 px-6 py-4 bg-slate-50">
                {{ $documents->links() }}
            </div>
        </div>
    @else
        <div class="rounded-[32px] border border-slate-200/70 bg-white/90 p-10 text-center shadow-[0_25px_90px_-35px_rgba(15,23,42,0.12)]">
            <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-3xl bg-sky-100 text-sky-600">
                <i data-lucide="file-text" class="h-8 w-8"></i>
            </div>
            <h2 class="text-xl font-black text-slate-900">لا توجد وثائق حتى الآن</h2>
            <p class="mt-3 text-sm text-slate-500">قم برفع المستندات الخاصة بك لحفظها في النظام والوصول إليها بسرعة.</p>
        </div>
    @endif
</div>
@endsection
