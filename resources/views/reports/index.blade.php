{{-- Reports dashboard with financial metrics, charts, and export options --}}

@extends('layouts.app')

@section('title', 'التقارير والإحصائيات')

@section('content')
<div class="space-y-6 mb-8">
    {{-- Header --}}
    <div class="rounded-[28px] border border-slate-200/70 bg-white/80 p-6 shadow-[0_20px_60px_-35px_rgba(15,23,42,0.45)] backdrop-blur">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.35em] text-slate-500">لوحة التقارير</p>
                <h2 class="text-3xl font-bold text-slate-800">ملخص الأداء والتقارير التشغيلية والمالية</h2>
                <p class="mt-2 text-sm text-slate-600">اطّلع على المؤشرات التشغيلية والمالية في مكان واحد.</p>
            </div>
            <div class="flex items-center gap-3">
                <form method="GET" action="{{ route('reports.index') }}" class="flex items-center gap-2">
                    <input type="month" name="month" value="{{ $selectedMonth ?? now()->format('Y-m') }}"
                        class="px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="submit" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-white rounded-xl text-sm font-semibold transition-all">
                        عرض
                    </button>
                </form>
                <a href="{{ route('reports.financial_pdf', ['month' => $selectedMonth ?? now()->format('Y-m')]) }}"
                    class="rounded-2xl bg-blue-600 px-5 py-3 font-semibold text-white transition hover:bg-blue-700 inline-flex items-center gap-2">
                    <i data-lucide="download" class="w-5 h-5"></i>
                    تصدير PDF
                </a>
                <a href="{{ route('reports.export.csv', ['month' => $selectedMonth ?? now()->format('Y-m')]) }}"
                    class="rounded-2xl bg-emerald-600 px-5 py-3 font-semibold text-white transition hover:bg-emerald-700 inline-flex items-center gap-2">
                    <i data-lucide="file-spreadsheet" class="w-5 h-5"></i>
                    تصدير CSV
                </a>
            </div>
        </div>
    </div>

    {{-- KPI Cards --}}
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-[24px] border border-slate-200/70 bg-white/80 p-5 shadow-[0_20px_60px_-35px_rgba(15,23,42,0.45)] backdrop-blur">
            <p class="text-sm text-slate-500">عدد الموظفين</p>
            <div class="mt-3 text-3xl font-bold text-slate-800">{{ $totalEmployees }}</div>
        </div>
        <div class="rounded-[24px] border border-slate-200/70 bg-white/80 p-5 shadow-[0_20px_60px_-35px_rgba(15,23,42,0.45)] backdrop-blur">
            <p class="text-sm text-slate-500">الوردية النشطة</p>
            <div class="mt-3 text-3xl font-bold text-slate-800">{{ $activeShifts }}</div>
        </div>
        <div class="rounded-[24px] border border-slate-200/70 bg-white/80 p-5 shadow-[0_20px_60px_-35px_rgba(15,23,42,0.45)] backdrop-blur">
            <p class="text-sm text-slate-500">الطلبات المعلقة</p>
            <div class="mt-3 text-3xl font-bold text-slate-800">{{ $pendingRequests }}</div>
        </div>
        <div class="rounded-[24px] border border-slate-200/70 bg-white/80 p-5 shadow-[0_20px_60px_-35px_rgba(15,23,42,0.45)] backdrop-blur">
            <p class="text-sm text-slate-500">سجلات الدوام الشهرية</p>
            <div class="mt-3 text-3xl font-bold text-slate-800">{{ $monthlyAttendance }}</div>
        </div>
    </div>

    {{-- Financial Cards --}}
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-[24px] border border-slate-200/70 bg-white/80 p-5 shadow-[0_20px_60px_-35px_rgba(15,23,42,0.45)] backdrop-blur hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm font-semibold text-slate-500">إجمالي الرواتب الأساسية</p>
                <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                    <i data-lucide="banknote" class="w-5 h-5 text-blue-600"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-blue-600">{{ number_format($financialData['totalBaseSalary'], 2) }} <span class="text-sm font-medium">ل.س</span></div>
            <p class="text-xs text-slate-400 mt-2">لشهر {{ $selectedMonth ?? now()->format('Y-m') }}</p>
        </div>
        <div class="rounded-[24px] border border-slate-200/70 bg-white/80 p-5 shadow-[0_20px_60px_-35px_rgba(15,23,42,0.45)] backdrop-blur hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm font-semibold text-slate-500">إجمالي الرواتب الصافية</p>
                <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                    <i data-lucide="wallet" class="w-5 h-5 text-emerald-600"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-emerald-600">{{ number_format($financialData['totalNetSalary'], 2) }} <span class="text-sm font-medium">ل.س</span></div>
            <p class="text-xs text-slate-400 mt-2">الصافي المدفوع للموظفين</p>
        </div>
        <div class="rounded-[24px] border border-slate-200/70 bg-white/80 p-5 shadow-[0_20px_60px_-35px_rgba(15,23,42,0.45)] backdrop-blur hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm font-semibold text-slate-500">إجمالي البدلات</p>
                <div class="w-10 h-10 rounded-xl bg-teal-100 flex items-center justify-center">
                    <i data-lucide="trending-up" class="w-5 h-5 text-teal-600"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-teal-600">+{{ number_format($financialData['totalAllowances'], 2) }} <span class="text-sm font-medium">ل.س</span></div>
            <p class="text-xs text-slate-400 mt-2">بدلات+s_impact للشهر</p>
        </div>
        <div class="rounded-[24px] border border-slate-200/70 bg-white/80 p-5 shadow-[0_20px_60px_-35px_rgba(15,23,42,0.45)] backdrop-blur hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm font-semibold text-slate-500">إجمالي الخصومات</p>
                <div class="w-10 h-10 rounded-xl bg-rose-100 flex items-center justify-center">
                    <i data-lucide="trending-down" class="w-5 h-5 text-rose-600"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-rose-600">-{{ number_format($financialData['totalDeductions'], 2) }} <span class="text-sm font-medium">ل.س</span></div>
            <p class="text-xs text-slate-400 mt-2">تأخير+عقوبات+خصومات</p>
        </div>
    </div>

    {{-- Charts --}}
    <div class="grid gap-6 xl:grid-cols-[1.4fr_0.8fr]">
        <section class="rounded-[28px] border border-slate-200/70 bg-white/80 p-6 shadow-[0_20px_60px_-35px_rgba(15,23,42,0.45)] backdrop-blur">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-xl font-bold text-slate-800">مخطط توزيع الرواتب</h3>
                    <p class="text-sm text-slate-500">نسبة الرواتب حسب الموظفين</p>
                </div>
            </div>
            <div class="h-64">
                <canvas id="salaryChart" class="w-full h-full"></canvas>
            </div>
        </section>

        <aside class="rounded-[28px] border border-slate-200/70 bg-white/80 p-6 shadow-[0_20px_60px_-35px_rgba(15,23,42,0.45)] backdrop-blur">
            <h3 class="text-xl font-bold text-slate-800">توزيع الأقسام</h3>
            <p class="text-sm text-slate-500 mb-4">عدد الموظفين النشطين</p>
            <div class="h-64">
                <canvas id="departmentChart" class="w-full h-full"></canvas>
            </div>
        </aside>
    </div>

    {{-- Salary Details Table --}}
    <section class="rounded-[28px] border border-slate-200/70 bg-white/80 shadow-[0_20px_60px_-35px_rgba(15,23,42,0.45)] backdrop-blur overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 bg-gradient-to-l from-slate-50 to-white">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h3 class="text-xl font-bold text-slate-800">تفاصيل الرواتب</h3>
                    <p class="text-sm text-slate-500">عدد كشوف الشهر: {{ $monthlyPayrolls }} كشف | إجمالي صافي: {{ number_format($financialData['totalNetSalary'], 2) }} ل.س</p>
                </div>
                <form method="GET" action="{{ route('reports.index') }}" class="flex items-center gap-2">
                    <input type="hidden" name="month" value="{{ $selectedMonth ?? now()->format('Y-m') }}">
                    <div class="relative">
                        <i data-lucide="search" class="absolute right-3 top-2.5 h-4 w-4 text-slate-400 pointer-events-none"></i>
                        <input type="text" name="salary_search" value="{{ request('salary_search') }}" placeholder="بحث باسم الموظف..."
                            class="rounded-xl border border-slate-200 bg-white pl-4 pr-10 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all">
                    </div>
                    <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-xl text-sm font-semibold transition-all">
                        بحث
                    </button>
                    @if(request('salary_search'))
                        <a href="{{ route('reports.index', ['month' => $selectedMonth ?? now()->format('Y-m')]) }}"
                            class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-xl text-sm font-semibold transition-all">
                            إعادة تعيين
                        </a>
                    @endif
                </form>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-slate-600 text-right font-medium w-12">#</th>
                        <th class="px-4 py-3 text-slate-600 text-right font-medium min-w-[200px]">الموظف</th>
                        <th class="px-4 py-3 text-slate-600 text-right font-medium">القسم</th>
                        <th class="px-4 py-3 text-slate-600 text-right font-medium">الأساسي</th>
                        <th class="px-4 py-3 text-slate-600 text-right font-medium">البدلات</th>
                        <th class="px-4 py-3 text-slate-600 text-right font-medium">الخصومات</th>
                        <th class="px-4 py-3 text-slate-600 text-right font-medium">الصافي</th>
                        <th class="px-4 py-3 text-slate-600 text-center font-medium w-28">الحالة</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($salaryData as $index => $payroll)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-4 py-3 text-sm text-slate-500">{{ $salaryData->firstItem() + $index }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center text-white font-bold text-xs shadow-sm flex-shrink-0">
                                        {{ strtoupper(substr($payroll->employee?->first_name ?? 'U', 0, 1)) }}
                                    </div>
                                    <span class="font-semibold text-slate-800 text-sm">{{ $payroll->employee?->full_name ?? '—' }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-600">{{ $payroll->employee?->department?->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-sm text-slate-600">{{ number_format($payroll->employee?->base_salary ?? 0, 2) }} <span class="text-xs text-slate-400">ل.س</span></td>
                            <td class="px-4 py-3 text-sm font-semibold text-teal-600">+{{ number_format($payroll->allowances, 2) }}</td>
                            <td class="px-4 py-3 text-sm font-semibold text-rose-600">-{{ number_format($payroll->deductions, 2) }}</td>
                            <td class="px-4 py-3 text-sm font-bold text-slate-800">{{ number_format($payroll->net_salary, 2) }} <span class="text-xs text-slate-400">ل.س</span></td>
                            <td class="px-4 py-3 text-center">
                                @php
                                    $ps = match($payroll->payment_status) {
                                        'paid' => ['bg' => 'bg-emerald-100 text-emerald-700 border border-emerald-200', 'l' => 'مدفوع'],
                                        'draft' => ['bg' => 'bg-slate-100 text-slate-500 border border-slate-200', 'l' => 'مسودة'],
                                        'cancelled' => ['bg' => 'bg-rose-100 text-rose-700 border border-rose-200', 'l' => 'ملغي'],
                                        'approved' => ['bg' => 'bg-blue-100 text-blue-700 border border-blue-200', 'l' => 'معتمد'],
                                        default => ['bg' => 'bg-slate-100 text-slate-700 border border-slate-200', 'l' => $payroll->payment_status]
                                    };
                                @endphp
                                <span class="inline-flex items-center px-4 py-1 rounded-full text-xs font-bold {{ $ps['bg'] }}">
                                    {{ $ps['l'] }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center">
                                        <i data-lucide="inbox" class="w-8 h-8 text-slate-400"></i>
                                    </div>
                                    <p class="text-sm font-semibold text-slate-500">لا توجد كشوف رواتب لهذا الشهر.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($salaryData->hasPages())
            <div class="border-t border-slate-100 bg-slate-50 px-6 py-4">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-slate-500">
                        showing {{ $salaryData->firstItem() ?? 0 }} to {{ $salaryData->lastItem() ?? 0 }} of {{ $salaryData->total() }} records
                    </p>
                    <div class="flex items-center gap-1">
                        @if($salaryData->onFirstPage())
                            <span class="px-3 py-1.5 rounded-lg bg-slate-200 text-slate-400 cursor-not-allowed">
                                <i data-lucide="chevron-right" class="w-4 h-4"></i>
                            </span>
                        @else
                            <a href="{{ $salaryData->previousPageUrl() }}" class="px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 transition-all">
                                <i data-lucide="chevron-right" class="w-4 h-4"></i>
                            </a>
                        @endif

                        @foreach($salaryData->getUrlRange(1, $salaryData->lastPage()) as $page => $url)
                            @if($page == $salaryData->currentPage())
                                <span class="px-3 py-1.5 rounded-lg bg-blue-600 text-white text-sm font-bold shadow-sm">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="px-2 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 text-sm font-medium transition-all">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if($salaryData->hasMorePages())
                            <a href="{{ $salaryData->nextPageUrl() }}" class="px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 transition-all">
                                <i data-lucide="chevron-left" class="w-4 h-4"></i>
                            </a>
                        @else
                            <span class="px-2 py-1.5 rounded-lg bg-slate-200 text-slate-400 cursor-not-allowed">
                                <i data-lucide="chevron-left" class="w-4 h-4"></i>
                            </span>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
@endif
    </section>

    {{-- Holidays Table --}}
    <section class="rounded-[28px] mb-8 border border-slate-200/70 bg-white/80 shadow-[0_20px_60px_-35px_rgba(15,23,42,0.45)] backdrop-blur overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 bg-gradient-to-l from-slate-50 to-white">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold text-slate-800">الإجازات الرسمية</h3>
                    <p class="text-sm text-slate-500">عطلات وأعياد هذا الشهر</p>
                </div>
                <span class="rounded-full bg-amber-50 px-4 py-2 text-sm font-bold text-amber-700 border border-amber-100">{{ $holidays->count() }} إجازة</span>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">الاسم</th>
                        <th class="px-4 py-4 text-slate-600 text-right font-medium">من</th>
                        <th class="px-4 py-4 text-slate-600 text-right font-medium">إلى</th>
                        <th class="px-4 py-4 text-slate-600 text-center font-medium w-32">النوع</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($holidays as $holiday)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 font-semibold text-slate-800 text-sm">{{ $holiday->holiday_name }}</td>
                            <td class="px-4 py-4 text-slate-600 text-sm">{{ \Carbon\Carbon::parse($holiday->start_date)->format('Y-m-d') }}</td>
                            <td class="px-4 py-4 text-slate-600 text-sm">{{ \Carbon\Carbon::parse($holiday->end_date)->format('Y-m-d') }}</td>
                            <td class="px-4 py-4 text-center">
                                <span class="inline-flex items-center px-4 py-1 rounded-full text-xs font-bold {{ $holiday->is_recurring ? 'bg-blue-100 text-blue-700 border border-blue-200' : 'bg-emerald-100 text-emerald-700 border border-emerald-200' }}">
                                    {{ $holiday->is_recurring ? 'سنوية' : 'مرة واحدة' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-slate-500">لا توجد إجازات رسمية هذا الشهر.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const salaryCtx = document.getElementById('salaryChart');
    const deptCtx = document.getElementById('departmentChart');

    const salaryData = <?php echo json_encode($salaryData->map(function($p) {
        return ['name' => $p->employee->full_name ?? '—', 'net_salary' => (float)$p->net_salary];
    })->values()->all(), JSON_UNESCAPED_UNICODE); ?>;

    const departmentData = <?php echo json_encode($departments->map(function($d) {
        return ['name' => $d->name, 'count' => $d->employees_count];
    })->values()->all(), JSON_UNESCAPED_UNICODE); ?>;

    if (salaryCtx && salaryData.length > 0) {
        new Chart(salaryCtx, {
            type: 'bar',
            data: {
                labels: salaryData.map(d => d.name),
                datasets: [{
                    label: 'صافي الراتب (ل.س)',
                    data: salaryData.map(d => d.net_salary),
                    backgroundColor: '#3b82f6',
                    borderColor: '#2563eb',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('en-US').format(value);
                            }
                        }
                    }
                }
            }
        });
    }

    if (deptCtx && departmentData.length > 0) {
        new Chart(deptCtx, {
            type: 'doughnut',
            data: {
                labels: departmentData.map(d => d.name),
                datasets: [{
                    data: departmentData.map(d => d.count),
                    backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ef4444', '#06b6d4']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 15 }
                    }
                }
            }
        });
    }
});
</script>
@endsection
