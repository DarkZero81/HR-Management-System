<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'HR System'))</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.468.0/lucide.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="min-h-screen bg-slate-100 font-['Cairo'] text-slate-900 antialiased">

    <!-- حاوية النظام المركزية المرنة -->
    <div class="w-full h-full flex flex-col lg:flex-row gap-4 min-h-[92vh] items-stretch">

        <!-- 1. شريط القائمة الجانبي (Sleek Dark Sidebar) -->
        <aside class="w-50 lg:w-72 bg-slate-950/95 p-5 shadow-[0_25px_80px_-35px_rgba(15,23,42,0.45)] backdrop-blur-xl text-slate-200 flex flex-col justify-between shrink-0">
            <div>
                <!-- الشعار والهوية -->
                <div class="flex items-center justify-between lg:block border-b border-white/5 pb-4">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-slate-400">HR Engine</p>
                        <h1 class="mt-0.5 text-xl font-black text-white tracking-tight">نظام الموارد البشرية</h1>
                    ‍</div>
                    <div class="mt-2 inline-block rounded-xl bg-blue-600/30 border border-blue-500/20 px-2.5 py-1 text-[11px] font-bold text-blue-400">الإصدار الاحترافي</div>
                </div>

                <!-- روابط الملاحة المحدثة -->
                <nav class="mt-6 space-y-1.5">
                    @php($nav = [
                        ['label' => 'الرئيسية', 'route' => 'dashboard', 'icon' => 'home'],
                        ['label' => 'الدوام والحضور', 'route' => 'attendance.index', 'icon' => 'clock'],
                        ['label' => 'الإجازات والطلبات', 'route' => 'my.requests.index', 'icon' => 'calendar'],
                        ['label' => 'الوثائق', 'route' => 'my.documents', 'icon' => 'file-text'],
                        ['label' => 'الرواتب', 'route' => 'payroll.index', 'icon' => 'dollar-sign'],
                    ])
                    @foreach($nav as $item)
                        <a href="{{ route($item['route']) }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-bold transition duration-200 {{ request()->routeIs($item['route']) ? 'bg-gradient-to-l from-cyan-500 to-blue-600 text-white shadow-lg shadow-blue-600/10' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                            <i data-lucide="{{ $item['icon'] }}" class="h-4 w-4"></i>
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endforeach

                    @if(in_array(optional(auth()->user()->role)->role_name, ['admin', 'hr', 'manager'], true))
                        <a href="{{ route('reports.index') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-bold transition duration-200 {{ request()->routeIs('reports.index') ? 'bg-gradient-to-l from-cyan-500 to-blue-600 text-white shadow-lg shadow-blue-600/10' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                            <i data-lucide="bar-chart-3" class="h-4 w-4"></i>
                            <span>التقارير الإدارية</span>
                        </a>
                    @endif
                </nav>
            </div>

            <!-- معلومات المستخدم وبطاقة مركز التحكم بأسفل القائمة الجانبية -->
            <div class="space-y-4 mt-8 lg:mt-0">
                <div class="rounded-2xl border border-white/5 bg-gradient-to-br from-slate-900 to-slate-950 p-4 shadow-inner">
                    <p class="text-xs font-bold text-white flex items-center gap-1.5">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span> مركز التحكم الآمن
                    </p>
                    <p class="mt-1 text-[11px] text-slate-400 leading-relaxed">تابع الحضور والوقوعات والرواتب من هنا بمرونة.</p>
                </div>

                <div class="flex items-center gap-3 rounded-2xl border border-white/5 bg-slate-900/40 p-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 font-black text-white text-sm shadow-md">
                        {{ strtoupper(substr(auth()->user()->email ?? 'U', 0, 1)) }}
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-xs font-bold text-white truncate">{{ auth()->user()->name ?? auth()->user()->email }}</p>
                        <p class="text-[10px] text-slate-500 font-semibold mt-0.5">{{ optional(auth()->user()->role)->role_name ?? 'موظف' }}</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- 2. حاوية العرض الرئيسية -->
        <main class="flex-1 overflow-y-auto p-6">
            <div class="max-w-[1600px] mx-auto space-y-6">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- تفعيل مكتبة أيقونات Lucide بشكل آمن ومتوافق -->
    <script src="https://unpkg.com/alpinejs@3.10.5/dist/cdn.min.js" defer></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) {
                window.lucide.createIcons();
            }
        });
    </script>
</body>
</html>
