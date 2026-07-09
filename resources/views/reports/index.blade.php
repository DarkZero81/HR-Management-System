@extends('layouts.app')

@section('title', 'التقارير والإحصائيات')

@section('content')
<div class="space-y-6">
    <div class="rounded-[28px] border border-slate-200/70 bg-white/80 p-6 shadow-[0_20px_60px_-35px_rgba(15,23,42,0.45)] backdrop-blur">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.35em] text-slate-500">لوحة التقارير</p>
                <h2 class="text-3xl font-bold text-slate-800">ملخص الأداء والتقارير التشغيلية والمالية</h2>
                <p class="mt-2 text-sm text-slate-600">اطّلع على المؤشرات التشغيلية والمالية في مكان واحد.</p>
            </div>
            <a href="{{ route('reports.financial_pdf') }}"
                class="rounded-2xl bg-blue-600 px-5 py-3 text-l p-3 font-semibold text-white transition hover:bg-blue-700 inline-flex items-center gap-2">
                <i data-lucide="download" class="w-5 h-5"></i>
                تصدير PDF
            </a>
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-[24px] border border-slate-200/70 bg-white/80 p-5 shadow-[0_20px_60px_-35px_rgba(15,23,42,0.45)] backdrop-blur">
            <p class="text-sm font-semibold text-slate-500">عدد الموظفين</p>
            <div class="mt-3 text-3xl font-bold text-slate-800">{{ $totalEmployees }}</div>
        </div>
        <div class="rounded-[24px] border border-slate-200/70 bg-white/80 p-5 shadow-[0_20px_60px_-35px_rgba(15,23,42,0.45)] backdrop-blur">
            <p class="text-sm font-semibold text-slate-500">الوردية النشطة</p>
            <div class="mt-3 text-3xl font-bold text-slate-800">{{ $activeShifts }}</div>
        </div>
        <div class="rounded-[24px] border border-slate-200/70 bg-white/80 p-5 shadow-[0_20px_60px_-35px_rgba(15,23,42,0.45)] backdrop-blur">
            <p class="text-sm font-semibold text-slate-500">الطلبات المعلقة</p>
            <div class="mt-3 text-3xl font-bold text-slate-800">{{ $pendingRequests }}</div>
        </div>
        <div class="rounded-[24px] border border-slate-200/70 bg-white/80 p-5 shadow-[0_20px_60px_-35px_rgba(15,23,42,0.45)] backdrop-blur">
            <p class="text-sm font-semibold text-slate-500">سجلات الدوام الشهرية</p>
            <div class="mt-3 text-3xl font-bold text-slate-800">{{ $monthlyAttendance }}</div>
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-2">
        <div class="rounded-[24px] border border-slate-200/70 bg-white/80 p-5 shadow-[0_20px_60px_-35px_rgba(15,23,42,0.45)] backdrop-blur">
            <p class="text-sm font-semibold text-slate-500 mb-3">إجمالي الرواتب الأساسية</p>
            <div class="text-3xl font-black text-blue-600">{{ number_format($financialData['totalBaseSalary'], 2) }} ل.س</div>
        </div>
        <div class="rounded-[24px] border border-slate-200/70 bg-white/80 p-5 shadow-[0_20px_60px_-35px_rgba(15,23,42,0.45)] backdrop-blur">
            <p class="text-sm font-semibold text-slate-500 mb-3">إجمالي الرواتب الصافية</p>
            <div class="text-3xl font-black text-emerald-600">{{ number_format($financialData['totalNetSalary'], 2) }} ل.س</div>
        </div>
        <div class="rounded-[24px] border border-slate-200/70 bg-white/80 p-5 shadow-[0_20px_60px_-35px_rgba(15,23,42,0.45)] backdrop-blur">
            <p class="text-sm font-semibold text-slate-500 mb-3">إجمالي البدلات</p>
            <div class="text-3xl font-black text-teal-600">+{{ number_format($financialData['totalAllowances'], 2) }} ل.س</div>
        </div>
        <div class="rounded-[24px] border border-slate-200/70 bg-white/80 p-5 shadow-[0_20px_60px_-35px_rgba(15,23,42,0.45)] backdrop-blur">
            <p class="text-sm font-semibold text-slate-500 mb-3">إجمالي الخصومات</p>
            <div class="text-3xl font-black text-red-600">-{{ number_format($financialData['totalDeductions'], 2) }} ل.س</div>
        </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-[1.4fr_0.8fr]">
        <section class="rounded-[28px] border border-slate-200/70 bg-white/80 p-6 shadow-[0_20px_60px_-35px_rgba(15,23,42,0.45)] backdrop-blur">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="mt-3 text-3xl font-bold text-slate-800">مخطط توزيع الرواتب</h3>
                    <p class="mt-1 text-sm text-slate-500">نسبة الرواتب حسب الموظفين</p>
                </div>
            </div>
            <div class="h-64">
                <canvas id="salaryChart" class="w-full h-full"></canvas>
            </div>
        </section>

        <aside class="rounded-[28px] border border-slate-200/70 bg-white/80 p-6 shadow-[0_20px_60px_-35px_rgba(15,23,42,0.45)] backdrop-blur">
            <h3 class="text-l font-bold text-slate-800">مخطط توزيع الأقسام</h3>
            <div class="h-64">
                <canvas id="departmentChart" class="w-full h-full"></canvas>
            </div>
        </aside>

        <section class="rounded-[28px] border border-slate-200/70 bg-white/80 p-6 shadow-[0_20px_60px_-35px_rgba(15,23,42,0.45)] backdrop-blur">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="mt-3 text-3xl font-bold text-slate-800">تفاصيل الرواتب</h3>
                    <p class="mt-1 text-sm text-slate-500">عدد كشوف الرواتب الشهر الحالي: {{ $monthlyPayrolls }} كشف</p>
                </div>
            </div>
            <div class="mt-6 overflow-hidden rounded-3xl border border-slate-200/70 bg-slate-950/5 p-4">
                <div class="grid gap-4 sm:grid-cols-3">
                    <div class="rounded-3xl bg-slate-950/10 p-4 text-slate-900">
                        <p class="text-l font-bold text-slate-800">إجمالي المدفوعات</p>
                        <p class="mt-3 text-xl font-bold text-slate-800">{{ $monthlyPayrolls }} دفعة</p>
                    </div>
                    <div class="rounded-3xl bg-slate-950/10 p-4 text-slate-900">
                        <p class="text-l font-bold text-slate-800">معدل الإنجاز</p>
                        <p class="mt-3 text-xl font-bold text-slate-800">90%</p>
                    </div>
                    <div class="rounded-3xl bg-slate-950/10 p-4 text-slate-900">
                        <p class="text-l font-bold text-slate-800">طلب الموافقة</p>
                        <p class="mt-3 text-xl font-bold text-slate-800">{{ $pendingRequests }}</p>
                    </div>
                </div>
            </div>
        </section>

        <aside class="rounded-[28px] border border-slate-200/70 bg-gradient-to-br from-slate-950 to-slate-800 p-6 text-white shadow-[0_20px_60px_-35px_rgba(15,23,42,0.9)]">
            <h3 class="text-xl font-black">ملاحظات سريعة</h3>
            <p class="mt-3 text-sm text-slate-300">راقب أداء الموارد البشرية بالتقارير الرقمية وراجع الانقطاعات، الحضور، وحالة طلبات الإجازات.</p>
            <ul class="mt-5 space-y-3 text-sm text-slate-300">
                <li class="rounded-2xl bg-white/5 px-4 py-3">احفظ نسخة PDF من تقرير الرواتب قبل نهاية الشهر.</li>
                <li class="rounded-2xl bg-white/5 px-4 py-3">راجع الطلبات المعلقة يومياً لتجنب تأخير الإجراءات.</li>
                <li class="rounded-2xl bg-white/5 px-4 py-3">ضبط إعدادات التنبيهات للتقارير الحساسة.</li>
            </ul>
        </aside>
    </div>
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
