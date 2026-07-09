@extends('layouts.app')
@section('title', 'تقويم الإجازات')
@section('content')
@php
    $holidaysList = collect($holidays);
    $totalCount = $holidaysList->count();
    $recurringCount = $holidaysList->filter(fn($h) => ($h['extendedProps']['recurring'] ?? false))->count();
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
        <div id="holidayCalendar" style="min-height: 600px;"></div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('holidayCalendar');
        if (!calendarEl) return;

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
            themeSystem: isDark ? 'bootstrap5-dark' : 'bootstrap5',
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
    });
</script>
@endsection
