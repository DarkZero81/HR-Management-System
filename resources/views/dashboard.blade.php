@extends('layouts.app')

@section('title', 'الرئيسية')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs font-black uppercase tracking-[0.35em] text-slate-400">لوحة القيادة</p>
                <h1 class="text-3xl font-bold text-slate-800">أهلاً بك، {{ auth()->user()->name ?? 'المستخدم' }}!</h1>
                <p class="text-sm text-slate-400 mt-1">تابع الحضور، الطلبات، والرواتب من واجهة حديثة.</p>
            </div>
            <div class="shrink-0">
                <a href="{{ route('profile.edit') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-white rounded-xl font-medium transition-all border border-white/10">
                    <i data-lucide="user" class="w-4 h-4"></i>
                    <span>الملف الشخصي</span>
                </a>
            </div>
        </div>

        @if ($viewMode === 'admin')
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 md:gap-6">
                <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100 hover:shadow-xl transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center">
                            <i data-lucide="users" class="w-6 h-6 text-blue-600"></i>
                        </div>
                        <span class="text-xs text-slate-400 font-medium">الإجمالي</span>
                    </div>
                    <p class="text-3xl font-bold text-slate-800">{{ $employeeCount }}</p>
                    <p class="text-sm text-slate-500 mt-2">موظف</p>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100 hover:shadow-xl transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center">
                            <i data-lucide="check-circle" class="w-6 h-6 text-emerald-600"></i>
                        </div>
                        <span class="text-xs text-slate-400 font-medium">اليوم</span>
                    </div>
                    <p class="text-3xl font-bold text-slate-800">{{ $todayAttendance }}</p>
                    <p class="text-sm text-slate-500 mt-2">حاضر اليوم</p>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100 hover:shadow-xl transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center">
                            <i data-lucide="clock" class="w-6 h-6 text-amber-600"></i>
                        </div>
                        <span class="text-xs text-slate-400 font-medium">تأخير</span>
                    </div>
                    <p class="text-3xl font-bold text-slate-800">{{ $lateMinutes }}</p>
                    <p class="text-sm text-slate-500 mt-2">دقيقة تأخير</p>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100 hover:shadow-xl transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-violet-100 flex items-center justify-center">
                            <i data-lucide="clipboard-list" class="w-6 h-6 text-violet-600"></i>
                        </div>
                        <span class="text-xs text-slate-400 font-medium">معلق</span>
                    </div>
                    <p class="text-3xl font-bold text-slate-800">{{ $pendingRequests }}</p>
                    <p class="text-sm text-slate-500 mt-2">طلب معلق</p>
                </div>


            </div>

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
                    <h2 class="text-lg font-black text-slate-800 mb-4">مخطط حركات الحضور الأسبوعية</h2>
                    <div class="h-64">
                        <canvas id="attendanceChart" class="w-full h-full"></canvas>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
                    <h2 class="text-lg font-black text-slate-800 mb-4">نسبة الحضور لهذا الأسبوع</h2>
                    <div class="h-64">
                        <canvas id="attendanceRateChart" class="w-full h-full"></canvas>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
                    <h2 class="text-lg font-black text-slate-800 mb-4">مخطط نسبة الأرباح</h2>
                    <div class="h-64">
                        <canvas id="profitChart" class="w-full h-full"></canvas>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
                    <h2 class="text-lg font-black text-slate-800 mb-4">مخطط الرواتب الشهرية</h2>
                    <div class="h-64">
                        <canvas id="payrollChart" class="w-full h-full"></canvas>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
                    <h2 class="text-lg font-black text-slate-800 mb-4">آخر حركات الحضور</h2>
                    <div class="space-y-3">
                        @forelse($recentAttendance as $log)
                            <div
                                class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                                <span class="font-semibold text-slate-800">{{ $log->employee?->first_name }}
                                    {{ $log->employee?->last_name }}</span>
                                <span class="rounded-lg bg-cyan-500/10 px-3 py-1 font-mono font-bold text-cyan-600 text-sm">
                                    @if ($log->check_in)
                                        {{ is_string($log->check_in) ? \Carbon\Carbon::parse($log->check_in)->format('h:i A') : $log->check_in->format('h:i A') }}
                                    @else
                                        —
                                    @endif
                                </span>
                            </div>
                        @empty
                            <p class="text-center text-slate-500 py-4">لا توجد حركات اليوم.</p>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
                    <h2 class="text-lg font-black text-slate-800 mb-4">طلبات بانتظار الموافقة</h2>
                    <div class="space-y-3">
                        @forelse($pendingTransactions as $t)
                            <div
                                class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                                <span class="font-semibold text-slate-800">{{ $t->employee?->first_name }}
                                    {{ $t->employee?->last_name }}</span>
                                <span class="rounded-lg bg-amber-500/10 px-3 py-1 font-bold text-amber-600 text-sm">
                                    @if ($t->transaction_type == 'leave')
                                        إجازة
                                    @elseif($t->transaction_type == 'permission')
                                        إذن غياب
                                    @elseif($t->transaction_type == 'penalty')
                                        جزاء مالي
                                    @elseif($t->transaction_type == 'promotion')
                                        ترقية
                                    @elseif($t->transaction_type == 'transfer')
                                        نقل إداري
                                    @else
                                        {{ $t->transaction_type }}
                                    @endif
                                </span>
                            </div>
                        @empty
                            <p class="text-center text-slate-500 py-4">لا توجد طلبات معلقة.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <script>
                <?php
                    $payrollSalaries = $recentPayrolls->map(function($p) {
                        return (float) $p->net_salary;
                    })->values()->all();
                    $payrollMonths = $recentPayrolls->map(function($p) {
                        return $p->salary_month ?? '';
                    })->values()->all();
                ?>
                document.addEventListener('DOMContentLoaded', function() {
                    const attendanceCtx = document.getElementById('attendanceChart');
                    const attendanceRateCtx = document.getElementById('attendanceRateChart');
                    const profitCtx = document.getElementById('profitChart');
                    const payrollCtx = document.getElementById('payrollChart');

                    if (attendanceCtx) {
                        const weeklyLabels = <?php echo json_encode($weekDays ?? ['السبت', 'الأحد', 'الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة'], JSON_UNESCAPED_UNICODE); ?>;
                        const weeklyData = <?php echo json_encode($weeklyAttendance ?? [0, 0, 0, 0, 0, 0, 0], JSON_UNESCAPED_UNICODE); ?>;

                        new Chart(attendanceCtx, {
                            type: 'line',
                            data: {
                                labels: weeklyLabels,
                                datasets: [{
                                    label: 'عدد الحضور',
                                    data: weeklyData,
                                    borderColor: '#3b82f6',
                                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                    tension: 0.4,
                                    fill: true,
                                    pointBackgroundColor: '#3b82f6',
                                    pointBorderColor: '#ffffff',
                                    pointRadius: 5,
                                    pointHoverRadius: 7
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: { legend: { display: false } },
                                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
                            }
                        });
                    }

                    if (attendanceRateCtx) {
                        const weeklyLabels = <?php echo json_encode($weekDays ?? ['السبت', 'الأحد', 'الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة'], JSON_UNESCAPED_UNICODE); ?>;
                        const weeklyRateData = <?php echo json_encode($weeklyAttendanceRate ?? [0, 0, 0, 0, 0, 0, 0], JSON_UNESCAPED_UNICODE); ?>;

                        new Chart(attendanceRateCtx, {
                            type: 'bar',
                            data: {
                                labels: weeklyLabels,
                                datasets: [{
                                    label: 'نسبة الحضور %',
                                    data: weeklyRateData,
                                    backgroundColor: weeklyRateData.map(rate => {
                                        if (rate >= 90) return 'rgba(16, 185, 129, 0.8)';
                                        if (rate >= 70) return 'rgba(245, 158, 11, 0.8)';
                                        return 'rgba(239, 68, 68, 0.8)';
                                    }),
                                    borderRadius: 8
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: { legend: { display: false } },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        max: 100,
                                        ticks: {
                                            callback: function(value) { return value + '%'; }
                                        }
                                    }
                                }
                            }
                        });
                    }

                    if (profitCtx) {
                        const profitData = <?php echo json_encode($profitMarginData ?? [], JSON_UNESCAPED_UNICODE); ?>;
                        const netRatio = profitData.netSalaryRatio ?? 0;
                        const deductionRatio = profitData.deductionRatio ?? 0;

                        new Chart(profitCtx, {
                            type: 'doughnut',
                            data: {
                                labels: ['صافي المدفوعات', 'الخصومات'],
                                datasets: [{
                                    data: [netRatio, deductionRatio],
                                    backgroundColor: ['#10b981', '#ef4444'],
                                    borderWidth: 0
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                        labels: { padding: 15 }
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                return context.label + ': ' + context.parsed.toFixed(1) + '%';
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    }

                    if (payrollCtx) {
                        const recentPayrolls = <?php echo json_encode($payrollSalaries, JSON_UNESCAPED_UNICODE); ?>;
                        const payrollLabels = <?php echo json_encode($payrollMonths, JSON_UNESCAPED_UNICODE); ?>;

                        new Chart(payrollCtx, {
                            type: 'bar',
                            data: {
                                labels: payrollLabels,
                                datasets: [{
                                    label: 'الراتب الصافي',
                                    data: recentPayrolls,
                                    backgroundColor: '#10b981',
                                    borderRadius: 6
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: { legend: { display: false } },
                                scales: { y: { beginAtZero: true } }
                            }
                        });
                    }
                });
            </script>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 md:gap-6">
                <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100 hover:shadow-xl transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center">
                            <i data-lucide="bar-chart-3" class="w-6 h-6 text-blue-600"></i>
                        </div>
                        <span class="text-xs text-slate-400 font-medium">تقدير أدائي</span>
                    </div>
                    <p class="text-3xl font-bold text-slate-800">{{ $employee->performance_score ?? 0 }}</p>
                    @if ($departmentAvgScore)
                        <p class="text-sm text-slate-500 mt-2">المتوسط القسمي: {{ number_format($departmentAvgScore, 2) }}
                        </p>
                    @endif
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100 hover:shadow-xl transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center">
                            <i data-lucide="wallet" class="w-6 h-6 text-blue-600"></i>
                        </div>
                        <span class="text-xs text-slate-400 font-medium">الراتب</span>
                    </div>
                    <p class="text-2xl font-bold text-slate-800">
                        {{ number_format((float) ($employee?->base_salary ?? 0), 2) }}</p>
                    <p class="text-sm text-slate-500 mt-2">الراتب الأساسي</p>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100 hover:shadow-xl transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center">
                            <i data-lucide="check-circle" class="w-6 h-6 text-emerald-600"></i>
                        </div>
                        <span class="text-xs text-slate-400 font-medium">اليوم</span>
                    </div>
                    <p class="text-3xl font-bold text-slate-800">{{ $attendanceToday ?? 0 }}</p>
                    <p class="text-sm text-slate-500 mt-2">حضور اليوم</p>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100 hover:shadow-xl transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-violet-100 flex items-center justify-center">
                            <i data-lucide="umbrella" class="w-6 h-6 text-violet-600"></i>
                        </div>
                        <span class="text-xs text-slate-400 font-medium">الإجازات</span>
                    </div>
                    <p class="text-3xl font-bold text-slate-800">{{ $vacationBalance }}</p>
                    <p class="text-sm text-slate-500 mt-2">رصيد الإجازات</p>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
                    <h2 class="text-lg font-black text-slate-800 mb-4">آخر حركاتي</h2>
                    <div class="space-y-3">
                        @forelse($recentAttendance as $log)
                            <div
                                class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                                <span class="text-slate-600">{{ $log->log_date?->format('Y-m-d') ?? '—' }}</span>
                                <span class="font-semibold text-slate-800">{{ $log->status }}</span>
                            </div>
                        @empty
                            <p class="text-center text-slate-500 py-4">لم يتم تسجيل أي حركة بعد.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const vacationCtx = document.getElementById('vacationChart');
                    if (vacationCtx) {
                        new Chart(vacationCtx, {
                            type: 'doughnut',
                            data: {
                                labels: ['الإجازات المستخدمة', 'الإجازات المتبقية'],
                                datasets: [{
                                    data: [{{ 30 - ($vacationBalance ?? 0) }},
                                        {{ $vacationBalance ?? 0 }}
                                    ],
                                    backgroundColor: ['#ef4444', '#10b981']
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                        labels: {
                                            padding: 15
                                        }
                                    }
                                }
                            }
                        });
                    }

                    const quarterlyCtx = document.getElementById('quarterlyChart');
                    @if ($quarterlyPerformance)
                        if (quarterlyCtx) {
                            const quarterlyLabels = <?php echo json_encode($quarterlyPerformance['labels'] ?? [], JSON_UNESCAPED_UNICODE); ?>;
                            const quarterlyPresent = <?php echo json_encode($quarterlyPerformance['present'] ?? [], JSON_UNESCAPED_UNICODE); ?>;
                            const quarterlyLate = <?php echo json_encode($quarterlyPerformance['late'] ?? [], JSON_UNESCAPED_UNICODE); ?>;

                            const trendData = quarterlyPresent.map((val, idx) => val + quarterlyLate[idx]);

                            new Chart(quarterlyCtx, {
                                type: 'line',
                                data: {
                                    labels: quarterlyLabels,
                                    datasets: [{
                                        label: 'نسبة الحضور',
                                        data: trendData,
                                        borderColor: '#3b82f6',
                                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                        tension: 0.4,
                                        fill: true
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: {
                                            display: false
                                        }
                                    },
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            ticks: {
                                                precision: 0
                                            }
                                        }
                                    }
                                }
                            });
                        }
                    @endif
                });
            </script>
        @endif
    </div>
@endsection
