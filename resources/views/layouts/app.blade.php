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
<body class="min-h-screen bg-[radial-gradient(circle_at_top_left,_rgba(59,130,246,0.22),_transparent_28%),linear-gradient(135deg,_#f8fbff_0%,_#eef4ff_100%)] font-[Cairo] text-slate-800 antialiased">
    <div class="mx-auto flex min-h-screen max-w-7xl flex-col px-3 py-3 lg:flex-row lg:px-4 lg:py-4">
        <aside class="mb-3 w-full rounded-[28px] border border-slate-200/70 bg-slate-950/95 p-4 shadow-[0_25px_80px_-35px_rgba(15,23,42,0.45)] backdrop-blur-xl text-slate-200 lg:mb-0 lg:mr-4 lg:w-72 lg:p-5">
            <div class="flex items-center justify-between lg:block">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-400">HR Engine</p>
                    <h1 class="mt-1 text-2xl font-black text-white">نظام الموارد البشرية</h1>
                </div>
                <div class="hidden rounded-2xl bg-blue-500 px-3 py-2 text-xs font-semibold text-white lg:block">الإصدار الاحترافي</div>
            </div>

            <nav class="mt-6 space-y-2">
                @php($nav = [
                    ['label' => 'الرئيسية', 'route' => 'dashboard', 'icon' => 'home'],
                    ['label' => 'الدوام والحضور', 'route' => 'attendance.index', 'icon' => 'clock'],
                    ['label' => 'الإجازات والطلبات', 'route' => 'my.requests.index', 'icon' => 'calendar'],
                    ['label' => 'الوثائق', 'route' => 'my.documents', 'icon' => 'file-text'],
                    ['label' => 'الرواتب', 'route' => 'payroll.index', 'icon' => 'dollar-sign'],
                ])
                @foreach($nav as $item)
                    <a href="{{ route($item['route']) }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold transition {{ request()->routeIs($item['route']) ? 'bg-gradient-to-l from-cyan-500 to-blue-600 text-white shadow-xl' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                        <i data-lucide="{{ $item['icon'] }}" class="h-4 w-4"></i>
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endforeach

                @if(in_array(optional(auth()->user()->role)->role_name, ['admin', 'hr', 'manager'], true))
                    <a href="{{ route('reports.index') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold transition {{ request()->routeIs('reports.index') ? 'bg-gradient-to-l from-cyan-500 to-blue-600 text-white shadow-xl' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                        <i data-lucide="bar-chart-3" class="h-4 w-4"></i>
                        <span>التقارير</span>
                    </a>
                @endif
            </nav>

            <div class="mt-8 rounded-[24px] border border-white/10 bg-gradient-to-br from-blue-600 to-indigo-700 p-4 text-white shadow-xl">
                <p class="text-sm font-semibold">مركز التحكم</p>
                <p class="mt-2 text-sm text-slate-100">تابع الحضور، الطلبات، والرواتب من هنا بسهولة.</p>
            </div>

            <div class="mt-6 flex items-center gap-3 rounded-[24px] border border-white/10 bg-slate-900/80 p-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-slate-200 text-slate-950">{{ strtoupper(substr(auth()->user()->email ?? 'U', 0, 1)) }}</div>
                <div>
                    <p class="text-sm font-semibold text-white">{{ auth()->user()->name ?? auth()->user()->email }}</p>
                    <p class="text-xs text-slate-400">{{ optional(auth()->user()->role)->role_name ?? 'موظف' }}</p>
                </div>
            </div>
        </aside>

        <div class="flex-1 rounded-[30px] border border-white/70 bg-white/70 p-3 shadow-[0_25px_80px_-35px_rgba(15,23,42,0.35)] backdrop-blur-xl lg:p-4">
            <header class="flex flex-col gap-4 rounded-[24px] border border-slate-200/70 bg-white/80 p-4 shadow-sm sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm font-semibold text-slate-500">مرحباً بك في لوحة التحكم</p>
                    <h2 class="text-2xl font-black text-slate-900">@yield('title', 'لوحة التحكم')</h2>
                </div>
                <div class="flex items-center gap-3">
                    <button class="rounded-2xl border border-slate-200 bg-slate-50 p-3 text-slate-600 transition hover:bg-slate-100">
                        <i data-lucide="bell" class="h-5 w-5"></i>
                    </button>
                    <div class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-3 py-2">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 font-bold text-white">{{ strtoupper(substr(auth()->user()->email ?? 'U', 0, 1)) }}</div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-slate-900">{{ auth()->user()->email ?? 'مستخدم' }}</p>
                            <p class="text-xs text-slate-500">{{ optional(auth()->user()->role)->role_name ?? 'موظف' }}</p>
                        </div>
                    </div>
                </div>
            </header>

            <main class="mt-4">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) window.lucide.createIcons();
        });
    </script>
    <script src="https://unpkg.com/lucide@latest"></script>
</body>
</html>
