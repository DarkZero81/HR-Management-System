@extends('layouts.app')

@section('title', 'الوثائق')

@section('content')
<div class="space-y-6">
    <section class="rounded-[32px] bg-white border border-slate-200/70 p-6 shadow-sm">
        <div class="flex flex-col gap-5 xl:flex-row xl:items-center xl:justify-between">
            <div class="space-y-3 text-right">
                <p class="text-sm uppercase tracking-[0.35em] text-slate-500">الوثائق</p>
                <h1 class="text-3xl font-black text-slate-900">سجل المستندات</h1>
                <p class="text-sm text-slate-600">ارفع المستندات الرسمية وحافظ على تنظيمها داخل النظام بسهولة.</p>
            </div>
            <div class="rounded-[28px] bg-gradient-to-r from-slate-900 to-blue-700 p-5 text-white shadow-xl shadow-blue-700/10">
                <div class="flex items-center gap-4">
                    <div class="flex h-14 w-14 items-center justify-center rounded-3xl bg-white/10 text-white">
                        <i data-lucide="file-plus" class="h-6 w-6"></i>
                    </div>
                    <div>
                        <p class="text-sm uppercase tracking-[0.35em] text-slate-300">رفع مستند</p>
                        <p class="mt-2 text-lg font-black">سهل وسريع</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="grid gap-6 xl:grid-cols-[1.3fr_0.7fr]">
        <div class="rounded-[32px] bg-white border border-slate-200/70 p-6 shadow-sm">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <p class="text-sm font-semibold text-slate-500">رفع مستند جديد</p>
                    <h2 class="text-2xl font-black text-slate-900">أضف وثيقة جديدة</h2>
                </div>
                <span class="rounded-full bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700">سريع وآمن</span>
            </div>
            <div class="mt-6">
                @livewire('document-uploader')
            </div>
        </div>

        <aside class="rounded-[32px] bg-slate-950 p-6 text-white shadow-xl shadow-slate-950/20">
            <p class="text-sm uppercase tracking-[0.35em] text-slate-400">ملخص الوثائق</p>
            <h2 class="mt-3 text-2xl font-black">إدارة سهلة</h2>
            <div class="mt-6 space-y-4">
                <div class="rounded-[24px] bg-white/5 p-4">
                    <p class="text-sm text-slate-300">المستندات المخزنة</p>
                    <p class="mt-2 text-2xl font-black">{{ $documents->total() }}</p>
                </div>
                <div class="rounded-[24px] bg-white/5 p-4">
                    <p class="text-sm text-slate-300">أحدث رفع</p>
                    <p class="mt-2 text-lg font-semibold">{{ $documents->first()?->created_at?->format('d F Y') ?? 'لا يوجد' }}</p>
                </div>
            </div>
        </aside>
    </section>

    @if($documents->count())
        <section class="rounded-[32px] bg-white border border-slate-200/70 shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-200/70 px-6 py-5">
                <div>
                    <p class="text-sm font-semibold text-slate-500">سجل المستندات</p>
                    <h2 class="text-lg font-black text-slate-900">المستندات المرفوعة</h2>
                </div>
                <span class="rounded-full bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700">{{ $documents->total() }} وثيقة</span>
            </div>
            <div class="overflow-x-auto p-6">
                <table class="min-w-full text-right text-sm text-slate-700">
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
                                <td class="px-5 py-4 font-semibold text-slate-900">{{ $doc->employee->full_name ?? ($doc->employee->first_name ?? '-') }}</td>
                                <td class="px-5 py-4 text-slate-600">{{ ucfirst(str_replace('_', ' ', $doc->document_type)) }}</td>
                                <td class="px-5 py-4 text-slate-600">{{ $doc->document_number }}</td>
                                <td class="px-5 py-4 text-slate-600">{{ $doc->expiry_date }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="border-t border-slate-200/70 bg-slate-50 px-6 py-4">
                {{ $documents->links() }}
            </div>
        </section>
    @else
        <section class="rounded-[32px] border border-slate-200/70 bg-white/90 p-10 text-center shadow-sm">
            <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-3xl bg-sky-100 text-sky-600">
                <i data-lucide="file-text" class="h-8 w-8"></i>
            </div>
            <h2 class="text-xl font-black text-slate-900">لا توجد وثائق حتى الآن</h2>
            <p class="mt-3 text-sm text-slate-500">ابدأ برفع مستنداتك الرسمية الآن للحفاظ على تنظيم ملفك الوظيفي.</p>
        </section>
    @endif
</div>
@endsection
