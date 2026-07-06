@extends('layouts.app')

@section('title', 'الرواتب')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs font-black uppercase tracking-[0.35em] text-slate-400">الرواتب</p>
                <h1 class="text-2xl md:text-3xl font-black text-white mt-1">إدارة كشوف الرواتب</h1>
                <p class="text-sm text-slate-400 mt-1">راجع كشوف الشهر، شغّل الحسابات، واطبع القسائم مباشرة من هنا.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('profile.edit') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-white rounded-xl font-medium transition-all border border-white/10">
                    <i data-lucide="user" class="w-4 h-4"></i>
                    <span class="hidden sm:inline">الملف الشخصي</span>
                </a>
                <button onclick="window.print()"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-white hover:bg-slate-100 text-slate-900 rounded-xl font-medium transition-all shadow-lg border-black/10">
                    <i data-lucide="printer" class="w-4 h-4"></i>
                    <span class="hidden sm:inline">طباعة القسائم</span>
                </button>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
            <form action="{{ route('payroll.generate') }}" method="POST"
                class="flex flex-col gap-3 sm:flex-row sm:items-center">
                @csrf
                <div class="flex-1">
                    <label class="block text-sm font-medium text-slate-700 mb-2">شهر الراتب</label>
                    <input type="month" name="salary_month" value="{{ $month ?? now()->format('Y-m') }}"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-slate-800">
                </div>
                @error('salary_month')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
                <div class="flex items-end">
                    <button type="submit"
                        class="px-6 py-3 bg-gradient-to-r from-blue-500 to-teal-400 bg-blue-600 p-2 hover:to-teal-500 text-white font-semibold rounded-xl shadow-lg transition-all whitespace-nowrap">
                        استعلام </button>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-3 md:grid-cols-3 gap-4 md:gap-6">
            <div class="w-75 bg-white rounded-2xl p-6 shadow-lg border border-slate-100 hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center">
                        <i data-lucide="wallet" class="w-6 h-6 text-blue-600"></i>
                    </div>
                    <span class="text-xs text-slate-400 font-medium">إجمالي الشهر</span>
                </div>
                <p class="text-2xl font-bold text-slate-800">
                    {{ ($totalBase = $payrolls->sum(fn($p) => $p->employee?->base_salary ?? 0)) ? number_format($totalBase, 2) . ' ل.س' : '0.00 ل.س' }}
                </p>
                <p class="text-sm text-slate-500 mt-2">الراتب الأساسي</p>
            </div>

            <div class="w-75 bg-white rounded-2xl p-6 shadow-lg border border-slate-100 hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center">
                        <i data-lucide="check-circle" class="w-6 h-6 text-emerald-600"></i>
                    </div>
                    <span class="text-xs text-slate-400 font-medium">آخر كشف</span>
                </div>
                <p class="text-2xl font-bold text-slate-800">
                    {{ $payrolls->first()?->net_salary ? number_format((float) $payrolls->first()->net_salary, 2) . ' ل.س' : '0.00 ل.س' }}
                </p>
                <p class="text-sm text-slate-500 mt-2">صافي الراتب</p>
            </div>

            <div class="w-75 bg-white rounded-2xl p-6 shadow-lg border border-slate-100 hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-violet-100 flex items-center justify-center">
                        <i data-lucide="file-text" class="w-6 h-6 text-violet-600"></i>
                    </div>
                    <span class="text-xs text-slate-400 font-medium">عدد الكشوف</span>
                </div>
                <p class="text-2xl font-bold text-slate-800">{{ $payrolls->count() }} كشف</p>
                <p class="text-sm text-slate-500 mt-2">من خلال النظام</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-black uppercase tracking-[0.35em] text-slate-400">كشف الشهر</p>
                        <h2 class="text-xl font-black text-slate-900 mt-1">{{ $month ?? now()->format('Y-m') }}</h2>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-4 text-slate-600 text-right font-medium">الموظف</th>
                            <th class="px-6 py-4 text-slate-600 text-right font-medium">الراتب الأساسي</th>
                            <th class="px-6 py-4 text-slate-600 text-right font-medium">البدلات</th>
                            <th class="px-6 py-4 text-slate-600 text-right font-medium">الخصومات</th>
                            <th class="px-6 py-4 text-slate-600 text-right font-medium">صافي الراتب</th>
                            <th class="px-6 py-4 text-slate-600 text-right font-medium">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($payrolls as $payroll)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-full bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center text-white font-bold text-sm">
                                            {{ strtoupper(substr($payroll->employee?->first_name ?? 'U', 0, 1)) }}
                                        </div>
                                        <span
                                            class="font-semibold text-slate-800">{{ $payroll->employee?->full_name ?? '—' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-slate-600">{{ number_format($payroll->employee?->base_salary ?? 0, 2) }} ل.س</td>
                                <td class="px-6 py-4 text-emerald-600 font-semibold">
                                    +{{ number_format($payroll->allowances, 2) }} ل.س</td>
                                <td class="px-6 py-4 text-red-600 font-semibold">
                                    -{{ number_format($payroll->deductions, 2) }} ل.س</td>
                                <td class="px-6 py-4 text-slate-800 font-bold">{{ number_format($payroll->net_salary, 2) }}
                                    ل.س</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        @if($payroll->employee)
                                            <a href="{{ route('payroll.download_pdf', ['employeeId' => $payroll->employee->id, 'month' => $payroll->salary_month]) }}"
                                               class="p-2 rounded-xl bg-slate-100 hover:bg-red-100 text-slate-600 hover:text-red-600 transition-colors"
                                               title="تحميل قسيمة PDF">
                                                <i data-lucide="file-down" class="w-4 h-4"></i>
                                            </a>
                                        @endif
                                        <button onclick="window.print()"
                                            class="p-2 rounded-xl bg-slate-100 hover:bg-blue-100 text-slate-600 hover:text-blue-600 transition-colors"
                                            title="طباعة">
                                            <i data-lucide="printer" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-slate-500">لا توجد كشوف رواتب لهذا
                                    الشهر.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-slate-100 bg-slate-50 px-6 py-4">
                {{ $payrolls->links() }}
            </div>
        </div>
    </div>
@endsection
