@extends('layouts.app')

@section('title', 'كشوف رواتبي')

@section('content')
    <div class="space-y-6">
        <div>
            <p class="text-xs font-black uppercase tracking-[0.35em] text-slate-400">الرواتب</p>
            <h1 class="text-3xl font-bold text-slate-800">كشوف رواتبي</h1>
            <p class="text-sm text-slate-400 mt-1">يمكنك الاطلاع على كشوف رواتبك الشهرية وتحميل القسائم.</p>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
            <form method="GET" action="{{ route('payroll.my_index') }}" class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-slate-700 mb-2">شهر الراتب</label>
                    <input type="month" name="month" value="{{ $month ?? now()->format('Y-m') }}"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-slate-800">
                </div>
                <div class="flex items-end">
                    <button type="submit"
                        class="px-6 py-3 bg-gradient-to-r from-blue-500 to-teal-400 hover:to-teal-500 text-white font-semibold rounded-xl shadow-lg transition-all whitespace-nowrap">
                        <i data-lucide="search" class="w-4 h-4 inline-block ml-1"></i>
                        عرض
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100">
                <p class="text-xs font-black uppercase tracking-[0.35em] text-slate-400">كشف الشهر</p>
                <h2 class="text-xl font-black text-slate-900 mt-1">{{ $month ?? now()->format('Y-m') }}</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-4 text-slate-600 text-right font-medium">الشهر</th>
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
                                <td class="px-6 py-4 font-semibold text-slate-800">{{ $payroll->salary_month }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ number_format($payroll->employee?->base_salary ?? 0, 2) }} ل.س</td>
                                <td class="px-6 py-4 text-emerald-600 font-semibold">
                                    +{{ number_format($payroll->allowances, 2) }} ل.س</td>
                                <td class="px-6 py-4 text-red-600 font-semibold">
                                    -{{ number_format($payroll->deductions, 2) }} ل.س</td>
                                <td class="px-6 py-4 text-slate-800 font-bold">{{ number_format($payroll->net_salary, 2) }}
                                    ل.س</td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('payroll.download_pdf', ['employeeId' => $payroll->employee->id, 'month' => $payroll->salary_month]) }}"
                                       class="p-2 rounded-xl bg-slate-100 hover:bg-red-100 text-slate-600 hover:text-red-600 transition-colors inline-block"
                                       title="تحميل قسيمة PDF">
                                        <i data-lucide="file-down" class="w-4 h-4"></i>
                                    </a>
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
