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

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[radial-gradient(circle_at_top_left,_rgba(59,130,246,0.22),_transparent_28%),linear-gradient(135deg,_#f8fbff_0%,_#eef4ff_100%)] font-[Cairo] text-slate-800 antialiased">
    <div class="mx-auto flex min-h-screen max-w-7xl flex-col px-3 py-3 lg:flex-row lg:px-4 lg:py-4">
        <aside class="mb-3 w-full rounded-[28px] border border-white/70 bg-white/70 p-4 shadow-[0_25px_80px_-35px_rgba(15,23,42,0.45)] backdrop-blur-xl lg:mb-0 lg:mr-4 lg:w-72 lg:p-5">
            <div class="flex items-center justify-between lg:block">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-500">Employer</p>
                    <h1 class="mt-1 text-2xl font-black text-slate-900">HR Suite</h1>
                </div>
                <div class="rounded-2xl bg-slate-950 px-3 py-2 text-xs font-semibold text-white">النسخة الاحترافية</div>
            </div>

            <nav class="mt-6 space-y-2">
                @php($nav = [
                    ['label' => 'لوحة التحكم', 'route' => 'dashboard', 'icon' => 'layout-dashboard'],
                    ['label' => 'الموظفون', 'route' => 'employees.index', 'icon' => 'users'],
                    ['label' => 'الطلبات', 'route' => 'my.requests.index', 'icon' => 'file-stack'],
                    ['label' => 'الدوام', 'route' => 'attendance.index', 'icon' => 'calendar-check-2'],
                    ['label' => 'الرواتب', 'route' => 'payroll.index', 'icon' => 'wallet'],
                    ['label' => 'الملفات', 'route' => 'my.documents', 'icon' => 'folder-open'],
                ])
                @foreach($nav as $item)
                    <a href="{{ route($item['route']) }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-900 hover:text-white {{ request()->routeIs($item['route']) ? 'bg-slate-900 text-white shadow-lg' : '' }}">
                        <i data-lucide="{{ $item['icon'] }}" class="h-4 w-4"></i>
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </nav>

            <div class="mt-8 rounded-[24px] border border-blue-100 bg-gradient-to-br from-blue-600 to-indigo-700 p-4 text-white shadow-xl">
                <p class="text-sm font-semibold">مركز العمليات</p>
                <p class="mt-2 text-sm text-blue-50">تتبع الطلبات والموافقات والرواتب من واجهة موحدة ومصممة بصرياً.</p>
            </div>
        </aside>

        <div class="flex-1 rounded-[30px] border border-white/70 bg-white/70 p-3 shadow-[0_25px_80px_-35px_rgba(15,23,42,0.35)] backdrop-blur-xl lg:p-4">
            <header class="flex flex-col gap-4 rounded-[24px] border border-slate-200/70 bg-white/80 p-4 shadow-sm sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm font-semibold text-slate-500">مرحباً بك</p>
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
                            <p class="text-xs text-slate-500">{{ optional(auth()->user()->role)->role_name ?? 'مستخدم' }}</p>
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
