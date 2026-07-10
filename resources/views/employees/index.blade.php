@extends('layouts.app')
@section('title', 'إدارة الموظفين')
@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs font-black uppercase tracking-[0.35em] text-slate-400">الموظفين</p>
                <h1 class="text-3xl font-bold text-slate-800">الملفات الوظيفية</h1>
                <p class="text-sm text-slate-400 mt-1">إدارة بيانات الموظفين والرواتب والأقسام.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('profile.edit') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-white rounded-xl font-medium transition-all border border-white/10">
                    <i data-lucide="user" class="w-4 h-4"></i>
                    <span class="hidden sm:inline">الملف الشخصي</span>
                </a><a href="{{ route('employees.create') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-500 to-teal-400 hover:from-blue-600 hover:to-teal-500 text-white rounded-xl font-semibold shadow-lg transition-all">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span class="hidden sm:inline">إضافة موظف</span></a>
                @auth
                    @if (auth()->user()->role && in_array(strtolower(auth()->user()->role->role_name), ['admin', 'hr']))
                        <a href="{{ route('register') }}"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-emerald-700 to-cyan-700 hover:from-emerald-600 hover:to-cyan-600 text-slate-800 rounded-xl font-semibold shadow-lg transition-all">
                            <i data-lucide="user-plus" class="w-4 h-4"></i>
                            <span class="hidden sm:inline">إنشاء حساب موظف</span>
                        </a>
                    @endif
                @endauth
            </div>
        </div>
        @if (session('success'))
            <div
                class="rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-4 py-3 text-sm font-semibold text-emerald-200 flex items-center gap-2">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div
                class="rounded-2xl border border-rose-400/20 bg-rose-500/10 px-4 py-3 text-sm font-semibold text-rose-200 flex items-center gap-2">
                <i data-lucide="alert-circle" class="w-5 h-5"></i>
                {{ session('error') }}
            </div>
        @endif

        <div class="employees-card rounded-2xl shadow-lg border p-6">
            <form action="{{ route('employees.index') }}" method="GET" class="grid grid-cols-1 gap-4 md:grid-cols-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">بحث نصي</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="الاسم، الكنية، أو الرقم الوطني..."
                        class="employees-card-input w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">تصفية حسب القسم</label>
                    <select name="department_id"
                        class="employees-card-input w-full px-8 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all cursor-pointer">
                        <option value="">كل الأقسام</option>
                        @foreach ($departments as $dept)
                            <option value="{{ $dept->id }}"
                                {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">تصفية حسب الوردية</label>
                    <select name="shift_id"
                        class="employees-card-input w-full px-8 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all cursor-pointer">
                        <option value="">كل الورديات</option>
                        @foreach ($shifts as $shift)
                            <option value="{{ $shift->id }}"
                                {{ request('shift_id') == $shift->id ? 'selected' : '' }}>{{ $shift->shift_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="flex-1 px-4 py-3 bg-slate-900 hover:bg-slate-800 text-white rounded-xl font-medium transition-all">
                        تطبيق الفلترة
                    </button>
                    @if (request()->has('search') || request()->has('department_id'))
                        <a href="{{ route('employees.index') }}"
                            class="px-4 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-medium transition-all">
                            إعادة
                        </a>
                    @endif
                </div>
            </form>
        </div>
        <div class="employees-card rounded-2xl shadow-lg border overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="employees-table-header">
                        <tr>
                            <th class="px-6 py-4 text-slate-600 text-right font-medium">الموظف</th>
                            <th class="px-6 py-4 text-slate-600 text-right font-medium">الرقم الوطني</th>
                            <th class="px-6 py-4 text-slate-600 text-right font-medium">القسم والوردية</th>
                            <th class="px-6 py-4 text-slate-600 text-right font-medium">الراتب الأساسي</th>
                            <th class="px-6 py-4 text-slate-600 text-right font-medium">رصيد الإجازات</th>
                            <th class="px-6 py-4 text-slate-600 text-right font-medium">تاريخ التعيين</th>
                            <th class="px-6 py-4 text-slate-600 text-right font-medium">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($employees as $employee)
                            <tr class="employees-table-row hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if ($employee->avatar)
                                            <img src="{{ asset('storage/' . $employee->avatar) }}"
                                                class="w-10 h-10 rounded-full object-cover border border-white/10 shadow-sm">
                                        @else
                                            <div
                                                class="w-10 h-10 rounded-full bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                                                {{ strtoupper(substr($employee->first_name ?? 'U', 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <div class="font-semibold text-slate-800">{{ $employee->full_name }}</div>
                                            <div class="text-xs text-slate-500">
                                                {{ $employee->user?->email ?? 'لا يوجد حساب مرتبط' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-slate-600 font-mono text-sm">{{ $employee->national_id }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                        {{ $employee->department?->name ?? 'غير معين' }}</span>
                                    <div class="text-xs text-slate-600 mt-2 items-center px-3">
                                        {{ $employee->shift?->shift_name ?? 'بدون وردية' }}</div>
                                </td>
                                <td class="px-6 py-4 text-slate-800 font-semibold">
                                    {{ number_format($employee->base_salary, 2) }} ل.س</td>
                                <td class="px-6 py-4 text-slate-800 font-semibold">{{ $employee->vacation_balance }} يوم
                                </td>
                                <td class="px-6 py-4 text-slate-600 text-sm">{{ $employee->join_date?->format('Y-m-d') ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('employees.show', $employee->id) }}"
                                            class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-cyan-50 hover:bg-cyan-100 text-cyan-700 text-xs font-medium transition-colors"
                                            title="عرض">
                                            <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                           </a>
                                        <a href="{{ route('employees.edit', $employee->id) }}"
                                            class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-medium transition-colors"
                                            title="تعديل">
                                            <i data-lucide="edit" class="w-3.5 h-3.5"></i>
                                           </a>
                                        <a href="{{ route('employees.pdf', $employee->id) }}"
                                            target="_blank"
                                            class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-medium transition-colors"
                                            title="تحميل PDF">
                                            <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                                            </a>
                                        <form action="{{ route('employees.destroy', $employee->id) }}" method="POST"
                                            class="inline"
                                            onsubmit="return confirm('هل أنت متأكد تماماً من رغبتك في حذف ملف هذا الموظف؟ لا يمكن التراجع!');">
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
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-slate-500">لا يوجد موظفين مسجلين.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-slate-100 bg-slate-50 px-6 py-4">
                {{ $employees->links() }}</div>
        </div>
    </div>
@endsection
