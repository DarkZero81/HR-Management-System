<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'HR System'))</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.468.0/lucide.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body
    class="min-h-screen bg-[radial-gradient(circle_at_top_left,_rgba(34,211,238,0.12),_transparent_32%),linear-gradient(135deg,_#020617_0%,_#0f172a_45%,_#111827_100%)] font-['Cairo'] text-slate-100 antialiased">
    <div class="flex min-h-screen flex-col gap-4 p-3 lg:flex-row lg:p-4">
        <aside
            class="w-50 shrink-0 rounded-[32px] border border-white/10 bg-slate-950/80 p-4 shadow-[0_25px_80px_-35px_rgba(8,15,30,0.8)] backdrop-blur-2xl lg:w-72 lg:p-5">
            <div class="flex items-center justify-between border-b border-white/10 pb-4 lg:block">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-[0.35em] text-slate-400">HR Engine</p>
                    <h1 class="mt-1 text-xl font-black text-white">نظام الموارد البشرية</h1>
                </div>
                <div
                    class="rounded-full border border-cyan-400/30 bg-cyan-500/10 px-3 py-1 text-[10px] font-bold text-cyan-300">
                    Dark UI</div>
            </div>

            <nav class="mt-6 space-y-1.5">
                @php($nav = [['label' => 'الرئيسية', 'route' => 'dashboard', 'icon' => 'home'], ['label' => 'الأقسام الإدارية', 'route' => 'departments.index', 'icon' => 'building'], ['label' => 'الدوام والحضور', 'route' => 'attendance.index', 'icon' => 'clock'], ['label' => 'الإجازات والطلبات', 'route' => 'my.requests.index', 'icon' => 'calendar'], ['label' => 'الوثائق', 'route' => 'my.documents', 'icon' => 'file-text'], ['label' => 'الرواتب', 'route' => 'payroll.index', 'icon' => 'dollar-sign']])
                @foreach ($nav as $item)
                    <a href="{{ route($item['route']) }}"
                        class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-bold transition duration-200 {{ request()->routeIs($item['route']) ? 'bg-gradient-to-l from-cyan-500 to-blue-600 text-white shadow-lg shadow-cyan-500/20' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        <i data-lucide="{{ $item['icon'] }}" class="h-4 w-4"></i>
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endforeach

                @if (in_array(optional(auth()->user()->role)->role_name, ['admin', 'hr', 'manager'], true))
                    <div class="pt-4 mt-4 border-t border-white/10">
                        <p class="px-4 text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mb-2">الإدارة
                        </p>
                        <a href="{{ route('reports.index') }}"
                            class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-bold transition duration-200 {{ request()->routeIs('reports.index') ? 'bg-gradient-to-l from-cyan-500 to-blue-600 text-white shadow-lg shadow-cyan-500/20' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                            <i data-lucide="bar-chart-3" class="h-4 w-4"></i>
                            <span>التقارير الإدارية</span>
                        </a>
                    </div>
                @endif

                @if (in_array(optional(auth()->user()->role)->role_name, ['admin', 'hr'], true))
                    <div class="pt-4 mt-4 border-t border-white/10">
                        <p class="px-4 text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mb-2">إدارة
                            النظام</p>

                        <a href="{{ route('employees.index') }}"
                            class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-bold transition duration-200 {{ request()->routeIs('employees.*') ? 'bg-gradient-to-l from-cyan-500 to-blue-600 text-white shadow-lg shadow-cyan-500/20' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                            <i data-lucide="users" class="h-4 w-4"></i>
                            <span>إدارة الموظفين</span>
                        </a>

                        <!-- زر إدارة الأقسام الإدارية -->
                        <a href="{{ route('departments.index') }}"
                            class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-bold transition duration-200 {{ request()->routeIs('departments.*') ? 'bg-gradient-to-l from-cyan-500 to-blue-600 text-white shadow-lg shadow-cyan-500/20' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                            <i data-lucide="building" class="h-4 w-4"></i>
                            <span>الأقسام الإدارية</span>
                        </a>

                        <a href="{{ route('shifts.index') }}"
                            class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-bold transition duration-200 {{ request()->routeIs('shifts.*') ? 'bg-gradient-to-l from-cyan-500 to-blue-600 text-white shadow-lg shadow-cyan-500/20' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                            <i data-lucide="clock" class="h-4 w-4"></i>
                            <span>الورديات</span>
                        </a>

                        <a href="{{ route('holidays.index') }}"
                            class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-bold transition duration-200 {{ request()->routeIs('holidays.*') ? 'bg-gradient-to-l from-cyan-500 to-blue-600 text-white shadow-lg shadow-cyan-500/20' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                            <i data-lucide="calendar-days" class="h-4 w-4"></i>
                            <span>الإجازات</span>
                        </a>

                        <a href="{{ route('requests.index') }}"
                            class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-bold transition duration-200 {{ request()->routeIs('requests.*') ? 'bg-gradient-to-l from-cyan-500 to-blue-600 text-white shadow-lg shadow-cyan-500/20' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                            <i data-lucide="clipboard-list" class="h-4 w-4"></i>
                            <span>الطلبات</span>
                        </a>

                        <a href="{{ route('devices.index') }}"
                            class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-bold transition duration-200 {{ request()->routeIs('devices.*') ? 'bg-gradient-to-l from-cyan-500 to-blue-600 text-white shadow-lg shadow-cyan-500/20' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                            <i data-lucide="monitor" class="h-4 w-4"></i>
                            <span>الأجهزة</span>
                        </a>

                        <a href="{{ route('documents.index') }}"
                            class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-bold transition duration-200 {{ request()->routeIs('documents.*') ? 'bg-gradient-to-l from-cyan-500 to-blue-600 text-white shadow-lg shadow-cyan-500/20' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                            <i data-lucide="folder-open" class="h-4 w-4"></i>
                            <span>الوثائق</span>
                        </a>

                        <a href="{{ route('attendance.index') }}"
                            class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-bold transition duration-200 {{ request()->routeIs('attendance.*') ? 'bg-gradient-to-l from-cyan-500 to-blue-600 text-white shadow-lg shadow-cyan-500/20' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                            <i data-lucide="user-check" class="h-4 w-4"></i>
                            <span>سجل الحضور</span>
                        </a>

                        <a href="{{ route('payroll.index') }}"
                            class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-bold transition duration-200 {{ request()->routeIs('payroll.*') ? 'bg-gradient-to-l from-cyan-500 to-blue-600 text-white shadow-lg shadow-cyan-500/20' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                            <i data-lucide="banknote" class="h-4 w-4"></i>
                            <span>الرواتب</span>
                        </a> <!-- تم إغلاق الوسم المكسور هنا بنجاح -->
                    </div>
                @endif
            </nav>



            <div class="mt-auto space-y-4 rounded-[28px] border border-white/10 bg-white/5 p-4">
                <div class="rounded-2xl border border-cyan-400/20 bg-gradient-to-br from-slate-900 to-slate-800 p-3">
                    <p
                        class="flex items-center gap-2 text-[11px] font-black uppercase tracking-[0.32em] text-slate-300">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span> مركز التحكم الآمن
                    </p>
                    <p class="mt-2 text-[11px] leading-relaxed text-slate-400">تابع الحضور والطلبات والرواتب بواجهة
                        حديثة ومناسبة للعمل العربي.</p>
                </div>

                <div class="flex items-center gap-3 rounded-2xl border border-white/10 bg-slate-900/60 p-3">
                    <div
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-cyan-500 to-blue-600 font-black text-white">
                        {{ strtoupper(substr(auth()->user()->email ?? 'U', 0, 1)) }}
                    </div>
                    <div class="overflow-hidden">
                        <p class="truncate text-sm font-black text-white">
                            {{ auth()->user()->name ?? auth()->user()->email }}</p>
                        <p class="mt-1 text-[11px] font-semibold text-slate-400">
                            {{ optional(auth()->user()->role)->role_name ?? 'موظف' }}</p>
                    </div>
                </div>
            </div>
        </aside>

        <main
            class="flex-1 overflow-y-auto rounded-[32px] border border-white/10 bg-slate-950/40 p-4 shadow-[0_20px_70px_-35px_rgba(15,23,42,0.85)] backdrop-blur-2xl lg:p-6">
            <div class="mx-auto max-w-[1600px] space-y-6">
                @if (session('success'))
                    <div
                        class="rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-4 py-3 text-sm font-semibold text-emerald-200">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div
                        class="rounded-2xl border border-rose-400/20 bg-rose-500/10 px-4 py-3 text-sm font-semibold text-rose-200">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <footer
        class="flex flex-col md:flex-row gap-3 items-center justify-around w-full py-4 text-sm bg-slate-800 text-white/70">
        <p>Copyright © 2025 HR Engine. All rights reserved.</p>

    </footer>

    <script src="https://unpkg.com/alpinejs@3.10.5/dist/cdn.min.js" defer></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) {
                window.lucide.createIcons();
            }
        });

        document.querySelectorAll('form[method="POST"]').forEach(form => {
            if (form.querySelector('input[name="_method"][value="DELETE"]')) {
                form.onsubmit = function() {
                    return confirm('هل أنت متأكد من تنفيذ هذا الإجراء؟ لا يمكن التراجع بعده.');
                };
            }
        });
    </script>
</body>

</html>
