@extends('layouts.app')
@section('title', 'تقويم الإجازات')
@section('content')
@php
    $holidaysList = collect($holidays);
    $totalCount = $holidaysList->count();
    $recurringCount = $holidaysList->filter(fn($h) => ($h['extendedProps']['recurring'] ?? false))->count();
    $currentYear = \Carbon\Carbon::now()->year;
    $years = range($currentYear - 2, $currentYear + 3);
    $months = [
        1 => 'يناير', 2 => 'فبراير', 3 => 'مارس', 4 => 'أبريل',
        5 => 'مايو', 6 => 'يونيو', 7 => 'يوليو', 8 => 'أغسطس',
        9 => 'سبتمبر', 10 => 'أكتوبر', 11 => 'نوفمبر', 12 => 'ديسمبر'
    ];
@endphp
<div class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-xs font-black uppercase tracking-[0.35em] text-slate-400">الإجازات</p>
            <h1 class="text-3xl font-bold text-slate-800">تقويم الإجازات</h1>
            <p class="text-sm text-slate-400 mt-1">عرض الإجازات الرسمية على التقويم السنوي.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('holidays.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-white rounded-xl font-medium transition-all border border-white/10">
                <i data-lucide="list" class="w-4 h-4"></i>
                <span class="hidden sm:inline">عرض قائمة</span>
            </a>
            <a href="{{ route('holidays.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-500 to-teal-400 hover:from-blue-600 hover:to-teal-500 text-white rounded-xl font-semibold shadow-lg transition-all">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span class="hidden sm:inline">إضافة إجازة</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="employee-form-card rounded-2xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                    <i data-lucide="calendar" class="w-5 h-5 text-blue-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-black text-slate-800">{{ $totalCount }}</p>
                    <p class="text-xs text-slate-500">إجازة مسجلة</p>
                </div>
            </div>
        </div>
        <div class="employee-form-card rounded-2xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                    <i data-lucide="repeat" class="w-5 h-5 text-emerald-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-black text-slate-800">{{ $recurringCount }}</p>
                    <p class="text-xs text-slate-500">متكررة سنوياً</p>
                </div>
            </div>
        </div>
        <div class="employee-form-card rounded-2xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center">
                    <i data-lucide="clock" class="w-5 h-5 text-amber-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-black text-slate-800">{{ \Carbon\Carbon::now()->format('Y') }}</p>
                    <p class="text-xs text-slate-500">السنة الحالية</p>
                </div>
            </div>
        </div>
    </div>

    <div class="employee-form-card rounded-[28px] border border-white/10 dark:border-white/5 p-4 md:p-6 shadow-2xl backdrop-blur-md">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
            <div class="flex items-center gap-2">
                <select id="calendarMonth" class="select select-bordered select-sm text-lg font-bold text-slate-800 dark:text-slate-200 dark:bg-slate-800 dark:border-white/10">
                    @foreach($months as $num => $name)
                        <option value="{{ $num }}" {{ \Carbon\Carbon::now()->month == $num ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
                <select id="calendarYear" class="select select-bordered select-sm dark:text-slate-200 dark:bg-slate-800 dark:border-white/10">
                    @foreach($years as $year)
                        <option value="{{ $year }}" {{ $currentYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
                <button type="button" id="calendarTodayBtn" class="btn btn-primary btn-sm">اليوم</button>
            </div>
        </div>
        <div id="holidayCalendar" style="min-height: 600px;"></div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
<style>
    html.dark .fc {
        --fc-bg-color: #0f172a;
        --fc-border-color: #1e293b;
        --fc-text-color: #e2e8f0;
        --fc-today-bg-color: rgba(59, 130, 246, 0.15);
        --fc-now-indicator-color: #3b82f6;
        color: #e2e8f0;
    }
    html.dark .fc .fc-toolbar {
        color: #e2e8f0;
    }
    html.dark .fc .fc-toolbar .fc-button {
        background: #1e293b;
        border-color: #334155;
        color: #e2e8f0;
    }
    html.dark .fc .fc-toolbar .fc-button:hover {
        background: #334155;
        color: #f1f5f9;
    }
    html.dark .fc .fc-toolbar .fc-button-active {
        background: #3b82f6;
        border-color: #3b82f6;
        color: #fff;
    }
    html.dark .fc .fc-toolbar .fc-button-active:hover {
        background: #2563eb;
        color: #fff;
    }
    html.dark .fc table {
        border-color: #1e293b;
    }
    html.dark .fc th {
        background: #1e293b;
        color: #94a3b8;
        border-color: #334155;
    }
    html.dark .fc td {
        border-color: #1e293b;
    }
    html.dark .fc .fc-daygrid-day-number {
        color: #cbd5e1;
    }
    html.dark .fc .fc-day-today {
        background: rgba(59, 130, 246, 0.15) !important;
    }
    html.dark .fc .fc-daygrid-day-frame {
        background: transparent;
    }
    html.dark .fc .fc-day-other {
        background: #020617;
        color: #475569;
    }
    html.dark .fc .fc-event {
        border-color: transparent;
    }
    html.dark .fc .fc-scrollgrid {
        border-color: #1e293b;
    }
    html.dark .fc .fc-scrollgrid td,
    html.dark .fc .fc-scrollgrid th {
        border-color: #1e293b;
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('holidayCalendar');
        if (!calendarEl) return;

        const monthSelect = document.getElementById('calendarMonth');
        const yearSelect = document.getElementById('calendarYear');
        const todayBtn = document.getElementById('calendarTodayBtn');

        const isDark = document.documentElement.classList.contains('dark');

        const calendar = new FullCalendar.Calendar(calendarEl, {
            locale: 'ar',
            direction: 'rtl',
            firstDay: 6,
            buttonText: {
                today: 'اليوم',
                month: 'شهر',
                week: 'أسبوع',
                day: 'يوم',
                list: 'قائمة'
            },
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,listWeek'
            },
            themeSystem: 'standard',
            events: <?php echo json_encode($holidays, JSON_UNESCAPED_UNICODE); ?>,
            eventDisplay: 'block',
            eventTextColor: '#ffffff',
            dayMaxEvents: true,
            height: 'auto',
            editable: false,
            eventColor: '#3b82f6',
            eventOrder: 'start',
            dayHeaderFormat: { weekday: 'short', month: 'numeric', day: 'numeric' },
            eventDidMount: function(info) {
                const recurring = info.event.extendedProps.recurring;
                if (recurring) {
                    info.el.style.backgroundColor = '#10b981';
                    info.el.style.borderColor = '#059669';
                }
            }
        });

        calendar.render();

        if (monthSelect && yearSelect) {
            const goToSelected = function() {
                const month = parseInt(monthSelect.value, 10);
                const year = parseInt(yearSelect.value, 10);
                const date = new Date(year, month - 1, 1);
                calendar.gotoDate(date);
            };

            monthSelect.addEventListener('change', goToSelected);
            yearSelect.addEventListener('change', goToSelected);
        }

        if (todayBtn) {
            todayBtn.addEventListener('click', function() {
                const now = new Date();
                monthSelect.value = now.getMonth() + 1;
                yearSelect.value = now.getFullYear();
                calendar.today();
            });
        }
    });
</script>
@endsection
