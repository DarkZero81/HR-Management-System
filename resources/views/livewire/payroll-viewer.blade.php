<div class="space-y-6">
    <div class="grid gap-4 xl:grid-cols-4">
        <div class="rounded-[28px] border border-slate-200/70 bg-white/90 p-5 shadow-sm">
            <p class="text-sm font-semibold text-slate-500">عدد الكشوف</p>
            <p class="mt-4 text-3xl font-black text-slate-900">{{ $summary['count'] }}</p>
        </div>
        <div class="rounded-[28px] border border-slate-200/70 bg-white/90 p-5 shadow-sm">
            <p class="text-sm font-semibold text-slate-500">إجمالي البدلات</p>
            <p class="mt-4 text-3xl font-black text-emerald-600">{{ number_format($summary['totalAllowances'], 2) }} د.ع</p>
        </div>
        <div class="rounded-[28px] border border-slate-200/70 bg-white/90 p-5 shadow-sm">
            <p class="text-sm font-semibold text-slate-500">إجمالي الخصومات</p>
            <p class="mt-4 text-3xl font-black text-rose-500">{{ number_format($summary['totalDeductions'], 2) }} د.ع</p>
        </div>
        <div class="rounded-[28px] border border-slate-200/70 bg-white/90 p-5 shadow-sm">
            <p class="text-sm font-semibold text-slate-500">الصافي الكلي</p>
            <p class="mt-4 text-3xl font-black text-sky-600">{{ number_format($summary['totalNet'], 2) }} د.ع</p>
        </div>
    </div>

    @if($payrolls->count())
        <div class="overflow-hidden rounded-[30px] border border-slate-200/70 bg-white/90 shadow-[0_30px_90px_-30px_rgba(15,23,42,0.12)]">
            <div class="flex flex-col gap-4 border-b border-slate-200/70 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.35em] text-slate-500">سجل الكشوف</p>
                    <h2 class="mt-2 text-xl font-black text-slate-900">كشوف الشهر {{ $month }}</h2>
                </div>
                <button class="rounded-full bg-cyan-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-cyan-600">تحميل تقارير PDF</button>
            </div>

            <div class="overflow-x-auto p-6">
                <table class="min-w-full text-right text-sm text-slate-700">
                    <thead class="bg-slate-100 text-slate-600">
                        <tr>
                            <th class="px-4 py-3 font-semibold">الموظف</th>
                            <th class="px-4 py-3 font-semibold">البدلات</th>
                            <th class="px-4 py-3 font-semibold">الخصومات</th>
                            <th class="px-4 py-3 font-semibold">الصافي</th>
                            <th class="px-4 py-3 font-semibold">الحالة</th>
                            <th class="px-4 py-3 font-semibold">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @foreach($payrolls as $payroll)
                            <tr class="transition hover:bg-slate-50">
                                <td class="px-4 py-4">
                                    <p class="font-semibold text-slate-900">{{ $payroll->employee->full_name ?? '—' }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $payroll->employee->national_id ?? '' }}</p>
                                </td>
                                <td class="px-4 py-4 text-emerald-600">{{ number_format((float) $payroll->allowances, 2) }} د.ع</td>
                                <td class="px-4 py-4 text-rose-500">{{ number_format((float) $payroll->deductions, 2) }} د.ع</td>
                                <td class="px-4 py-4 text-sky-600">{{ number_format((float) $payroll->net_salary, 2) }} د.ع</td>
                                <td class="px-4 py-4">
                                    <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">{{ $payroll->payment_status }}</span>
                                </td>
                                <td class="px-4 py-4">
                                    <button class="rounded-full border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-100">عرض</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="rounded-[28px] border border-slate-200/70 bg-white/90 p-10 text-center shadow-sm">
            <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-3xl bg-slate-100 text-slate-700">
                <i data-lucide="file-text" class="h-7 w-7"></i>
            </div>
            <h3 class="text-xl font-black text-slate-900">لا توجد كشوف لهذا الشهر</h3>
            <p class="mt-3 text-sm text-slate-500">شغل المحرك لتوليد كشوف الرواتب الشهرية أولاً.</p>
        </div>
    @endif
</div>
