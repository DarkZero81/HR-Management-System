@extends('layouts.app')

@section('title', $myMode ? 'وثائقي' : 'سجل المستندات')

@section('content')
<div class="space-y-6 mb-4" dir="rtl">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-xs font-black uppercase tracking-[0.35em] text-slate-400">الوثائق</p>
            <h1 class="text-3xl font-bold text-slate-800">{{ $myMode ? 'وثائقي الشخصية' : 'سجل المستندات' }}</h1>
            <p class="text-sm text-slate-400 mt-1">{{ $myMode ? 'عرض وإدارة الوثائق الخاصة بك فقط.' : 'ارفع المستندات الرسمية وحافظ على تنظيمها داخل النظام بسهولة.' }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-white rounded-xl font-medium transition-all duration-200 border border-white/10">
                <i data-lucide="user" class="w-4 h-4"></i>
                <span class="hidden sm:inline">الملف الشخصي</span>
            </a>
            @if($myMode)
                <a href="{{ route('my.documents.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-500 to-teal-400 hover:from-blue-600 hover:to-teal-500 text-white rounded-xl font-semibold shadow-lg transition-all">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span class="hidden sm:inline">رفع وثيقة</span>
                </a>
                @php($userRole = optional(auth()->user()->role)->role_name)
                @if(in_array($userRole, ['admin', 'manager'], true))
                <a href="{{ route('documents.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-xl font-semibold transition-all">
                    <i data-lucide="users" class="w-4 h-4"></i>
                    <span class="hidden sm:inline">سجل المستندات</span>
                </a>
                @endif
            @else
                <a href="{{ route('my.documents.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-xl font-semibold transition-all">
                    <i data-lucide="user" class="w-4 h-4"></i>
                    <span class="hidden sm:inline">وثائقي</span>
                </a>
                <a href="{{ route('documents.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-500 to-teal-400 hover:from-blue-600 hover:to-teal-500 text-white rounded-xl font-semibold shadow-lg transition-all duration-200">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span class="hidden sm:inline">إضافة وثيقة</span>
                </a>
            @endif
        </div>
    </div>

    <form method="GET" action="{{ $myMode ? route('my.documents.index') : route('documents.index') }}" class="employee-form-card rounded-2xl border border-slate-200 p-4 space-y-3">
        <div class="flex items-center gap-2 text-sm font-bold text-slate-700 mb-2">
            <i data-lucide="filter" class="w-4 h-4"></i> فلترة الوثائق
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <select name="document_type" class="employee-form-input rounded-xl border border-slate-200 bg-slate-50 text-sm px-8 py-2">
                <option value="">كل الأنواع</option>
                <option value="identity" {{ request('document_type') == 'identity' ? 'selected' : '' }}>هوية</option>
                <option value="passport" {{ request('document_type') == 'passport' ? 'selected' : '' }}>جواز سفر</option>
                <option value="contract" {{ request('document_type') == 'contract' ? 'selected' : '' }}>عقد</option>
                <option value="health_certificate" {{ request('document_type') == 'health_certificate' ? 'selected' : '' }}>شهادة صحية</option>
            </select>
            <select name="status" class="employee-form-input rounded-xl border border-slate-200 bg-slate-50 text-sm px-8 py-2">
                <option value="">كل الحالات</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>سارية</option>
                <option value="expiring" {{ request('status') == 'expiring' ? 'selected' : '' }}>قريبة الانتهاء</option>
                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>منتهية</option>
            </select>
            @if(!$myMode)
            <select name="employee_id" class="employee-form-input rounded-xl border border-slate-200 bg-slate-50 text-sm px-8 py-2">
                <option value="">كل الموظفين</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                        {{ $employee->full_name }}
                    </option>
                @endforeach
            </select>
            <label class="flex items-center gap-2 px-3 py-2 bg-slate-50 rounded-xl border border-slate-200 cursor-pointer">
                <input type="checkbox" name="my_documents" value="1" {{ request('my_documents') ? 'checked' : '' }} class="rounded border-slate-300">
                <span class="text-sm text-slate-700">وثائقي فقط</span>
            </label>
            @endif
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div class="relative">
                <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute right-3 top-1/2 -translate-y-1/2"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="بحث برقم الوثيقة..."
                       class="employee-form-input pr-10 pl-3 py-2 rounded-xl border border-slate-200 bg-slate-50 text-sm w-full">
            </div>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="employee-form-input rounded-xl border border-slate-200 bg-slate-50 text-sm px-3 py-2" placeholder="من تاريخ">
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="employee-form-input rounded-xl border border-slate-200 bg-slate-50 text-sm px-3 py-2" placeholder="إلى تاريخ">
            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 rounded-xl bg-gradient-to-l from-cyan-500 to-blue-600 text-white text-xs font-bold py-2.5 hover:opacity-95 transition">
                    تطبيق
                </button>
                <a href="{{ $myMode ? route('my.documents.index') : route('documents.index') }}" class="rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold py-2.5 px-4 transition">
                    إعادة تعيين
                </a>
            </div>
        </div>
    </form>

    @if(session('success'))
        <div class="rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-4 py-3 text-sm font-semibold text-emerald-200 flex items-center gap-2">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 shadow-lg border border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center">
                    <i data-lucide="file-text" class="w-6 h-6 text-blue-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-black text-slate-800">{{ $stats['total'] }}</p>
                    <p class="text-xs text-slate-500">إجمالي الوثائق</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-lg border border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center">
                    <i data-lucide="check-circle" class="w-6 h-6 text-emerald-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-black text-slate-800">{{ $stats['active'] }}</p>
                    <p class="text-xs text-slate-500">سارية</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-lg border border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center">
                    <i data-lucide="clock" class="w-6 h-6 text-amber-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-black text-slate-800">{{ $stats['expiring'] }}</p>
                    <p class="text-xs text-slate-500">قريبة الانتهاء</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-lg border border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-rose-100 flex items-center justify-center">
                    <i data-lucide="alert-circle" class="w-6 h-6 text-rose-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-black text-slate-800">{{ $stats['expired'] }}</p>
                    <p class="text-xs text-slate-500">منتهية</p>
                </div>
            </div>
        </div>
    </div>

     @if($expiringDocuments->isNotEmpty())
        <div class="rounded-2xl border border-amber-400/20 bg-amber-500/10 p-5">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <i data-lucide="alert-triangle" class="w-5 h-5 text-amber-600"></i>
                    <span class="font-bold text-amber-700">تنبيهات انتهاء الوثائق</span>
                </div>
                <span class="text-xs text-amber-600 bg-amber-200/50 px-3 py-1 rounded-full font-medium">
                    {{ $expiringDocuments->total() }} تنبيه
                </span>
            </div>
            <div class="space-y-2">
                @foreach($expiringDocuments as $doc)
                    <a href="{{ $myMode ? route('my.documents.show', $doc->id) : route('documents.show', $doc->id) }}" class="flex items-center justify-between rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 hover:bg-amber-100 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center text-white font-bold text-xs">
                                {{ strtoupper(substr($doc->employee->first_name ?? 'U', 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-800">{{ $doc->employee->full_name ?? ($doc->employee->first_name ?? 'موظف') }}</p>
                                <p class="text-xs text-slate-500">{{ ucfirst(str_replace('_', ' ', $doc->document_type)) }} رقم {{ $doc->document_number }}</p>
                            </div>
                        </div>
                        <span class="text-xs font-bold text-amber-700">{{ \Carbon\Carbon::parse($doc->expiry_date)->diffForHumans() }}</span>
                    </a>
                @endforeach
            </div>
            @if($expiringDocuments->hasPages())
                <div class="mt-4 pt-3 border-t border-amber-200/50">
                    {{ $expiringDocuments->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-4 text-slate-600 text-right font-medium">الموظف</th>
                    @if(!$myMode)
                    <th class="px-6 py-4 text-slate-600 text-right font-medium">المستخدم</th>
                    @endif
                    <th class="px-6 py-4 text-slate-600 text-right font-medium">نوع الوثيقة</th>
                    <th class="px-6 py-4 text-slate-600 text-right font-medium">الرقم</th>
                    <th class="px-6 py-4 text-slate-600 text-right font-medium">تاريخ الانتهاء</th>
                    <th class="px-2 py-4 text-slate-600 text-right font-medium">تاريخ الرفع</th>
                    <th class="px-2 py-4 text-slate-600 text-right font-medium">نوع الملف</th>
                    <th class="px-6 py-4 text-slate-600 text-right font-medium">الإجراءات</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                @foreach($documents as $doc)
                @if ($doc)
                    @php($ext = strtoupper(pathinfo($doc->file_path ?? '', PATHINFO_EXTENSION)))
                    @php($isExpired = $doc->expiry_date && \Carbon\Carbon::parse($doc->expiry_date)->lt(now()))
                    @php($isExpiring = !$isExpired && $doc->expiry_date && \Carbon\Carbon::parse($doc->expiry_date)->lte(now()->addMonths(3)))
                    <tr class="hover:bg-slate-50 transition-colors {{ $isExpired ? 'bg-rose-50/50' : '' }} {{ $isExpiring ? 'bg-amber-50/50' : '' }}">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center text-white font-bold text-sm">
                                    {{ strtoupper(substr($doc->employee->first_name ?? 'U', 0, 1)) }}
                                </div>
                                <span class="font-semibold text-slate-800">{{ $doc->employee->full_name ?? ($doc->employee->first_name ?? '-') }}</span>
                            </div>
                        </td>
                        @if(!$myMode)
                        <td class="px-2 py-4 text-sm text-slate-600">{{ $doc->employee->user->name ?? $doc->employee->user->email ?? '-' }}</td>
                        @endif
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                {{ ucfirst(str_replace('_', ' ', $doc->document_type)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-600 font-mono text-sm">{{ $doc->document_number }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium {{ $isExpired ? 'bg-rose-100 text-rose-700' : ($isExpiring ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700') }}">
                                {{ $doc->expiry_date }}
                            </span>
                        </td>
                        <td class="px-2 py-4 text-slate-600 text-xs">
                            {{ ($doc->created_at && $doc->created_at !== '0000-00-00 00:00:00') ? \Carbon\Carbon::parse($doc->created_at)->format('Y-m-d') : '-' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-medium {{ $ext == 'PDF' ? 'bg-rose-100 text-rose-700' : 'bg-emerald-100 text-emerald-700' }}">
                                {{ $ext ?: 'FILE' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ $myMode ? route('my.documents.show', $doc->id) : route('documents.show', $doc->id) }}"
                                   class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-medium transition-colors"
                                   title="عرض">
                                    <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                </a>
                                <a href="{{ $myMode ? route('my.documents.edit', $doc->id) : route('documents.edit', $doc->id) }}"
                                   class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-medium transition-colors"
                                   title="تعديل">
                                    <i data-lucide="edit" class="w-3.5 h-3.5"></i>
                                </a>
                                <form action="{{ $myMode ? route('my.documents.destroy', $doc->id) : route('documents.destroy', $doc->id) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه الوثيقة؟ لا يمكن التراجع!')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-slate-100 hover:bg-red-100 text-slate-600 hover:text-red-600 text-xs font-medium transition-colors"
                                            title="حذف">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @else
                    <tr>
                        <td colspan="{{ $myMode ? 7 : 8 }}" class="px-6 py-12 text-center text-slate-500">لا توجد وثائق مطابقة للبحث</td>
                    </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-100 bg-slate-50 px-6 py-4">
            {{ $documents->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
