<!DOCTYPE html>
<html lang="ar" dir="rtl" data-theme="dark">
<head>
    {{-- ==========================================
    البيانات الوصفية الأساسية (Meta Tags)
    ========================================== --}}
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- CSRF Token لأمان النماذج وطلبات AJAX --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- عنوان الصفحة: يأتي من الـ View الفرعي أو الاسم الافتراضي --}}
    <title>@yield('title', config('app.name', 'HR Engine'))</title>

    {{-- ==========================================
    روابط الخطوط والإطارات (Fonts & Frameworks)
    ========================================== --}}
    {{-- خط Tajawal المناسب للغة العربية --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    {{-- مكتبة الأيقونات Lucide --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.468.0/lucide.min.css">

    {{-- ==========================================
    الملفات المحلية (Vite)
    ========================================== --}}
    {{-- تجميع وتحميل ملفات CSS و JavaScript الخاصة بالمشروع --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen antialiased" dir="rtl">

    {{-- ==========================================
    Desktop Header Component
    ========================================== --}}
    <x-layout.header>
    </x-layout.header>


    {{-- ==========================================
    Mobile Header
    ========================================== --}}
    <header class="lg:hidden fixed top-0 right-0 left-0 h-16 bg-slate-900/90 backdrop-blur-xl border-b border-white/10 z-40 flex items-center justify-between px-4">
        <div class="flex items-center gap-3">
            <button id="mobileMenuToggle" class="p-2 rounded-xl bg-slate-800 hover:bg-slate-700 transition-colors">
                <i data-lucide="menu" class="w-5 h-5 text-white"></i>
            </button>
            <div>
                <p class="text-sm font-black text-white">HR Engine</p>
                <p class="text-[10px] text-slate-400">نظام الموارد البشرية</p>
            </div>
        </div>
    </header>

    {{-- ==========================================
    Sidebar Overlay
    ========================================== --}}
    <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 hidden lg:hidden"></div>

    <div class="flex min-h-screen">
        {{-- ==========================================
        Sidebar (Navigation Only)
        ========================================== --}}
        <aside id="sidebar" class="fixed top-0 right-0 h-full w-72 bg-gradient-to-b from-slate-900 to-slate-800 text-white z-50 transform translate-x-full lg:translate-x-0 transition-transform duration-300">
            <div class="p-6 border-b border-white/10">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-teal-400 flex items-center justify-center shadow-lg">
                            <i data-lucide="briefcase" class="w-6 h-6 text-white"></i>
                        </div>
                        <div>
                            <h1 class="font-bold text-lg text-white">HR Engine</h1>
                            <p class="text-xs text-slate-400">نظام الموارد البشرية</p>
                        </div>
                    </div>
                    <button id="closeSidebarMobile" class="lg:hidden p-2 rounded-xl hover:bg-slate-700/50 transition-colors">
                        <i data-lucide="x" class="w-5 h-5 text-slate-300"></i>
                    </button>
                </div>
            </div>

            <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
                @php
                    $userRole = optional(auth()->user()->role)->role_name;
                    $isAdminRole = in_array($userRole, ['admin', 'manager'], true);

                    $nav = [
                        ['label' => 'الرئيسية', 'route' => 'dashboard', 'icon' => 'home', 'roles' => ['all']],
                        ['label' => 'الدوام', 'route' => $isAdminRole ? 'attendance.index' : 'my.attendance.my_index', 'icon' => 'clock', 'roles' => ['all']],
                        ['label' => 'الطلبات', 'route' => 'my.requests.index', 'icon' => 'calendar', 'roles' => ['employee', 'admin', 'manager']],
                        ['label' => 'الوثائق', 'route' => 'my.documents.index', 'icon' => 'file-text', 'roles' => ['employee', 'admin', 'manager']],
                        ['label' => 'الرواتب', 'route' => 'payroll.index', 'icon' => 'banknote', 'roles' => ['admin', 'manager']],
                        ['label' => 'الأقسام', 'route' => 'departments.index', 'icon' => 'building', 'roles' => ['all']],
                        ['label' => 'الأجهزة', 'route' => 'devices.index', 'icon' => 'laptop', 'roles' => ['admin', 'manager']],
                        ['label' => 'الورديات', 'route' => 'shifts.index', 'icon' => 'book', 'roles' => ['admin', 'manager']],
                        ['label' => 'الموظفين', 'route' => 'employees.index', 'icon' => 'user', 'roles' => ['admin', 'manager']],
                        ['label' => 'الإجازات', 'route' => 'holidays.index', 'icon' => 'calendar-days', 'roles' => ['admin', 'manager']],
                        ['label' => 'التقرير', 'route' => 'reports.index', 'icon' => 'bar-chart', 'roles' => ['admin', 'manager']],
                    ];
                @endphp

                @foreach ($nav as $item)
                    @php($hasAccess = in_array('all', $item['roles']) || in_array($userRole, $item['roles']))
                    @if($hasAccess)
                        <a href="{{ route($item['route']) }}"
                           class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs($item['route']) ? 'bg-gradient-to-l from-blue-600 to-teal-500 text-white shadow-lg' : 'text-slate-300 hover:bg-slate-700/50 hover:text-white' }}">
                            <i data-lucide="{{ $item['icon'] }}" class="w-5 h-5"></i>
                            <span class="font-medium">{{ $item['label'] }}</span>
                        </a>
                    @endif
                @endforeach
            </nav>
        </aside>

        {{-- ==========================================
        Main Content
        ========================================== --}}
        <main class="flex-1 w-full lg:mr-72 pt-2 lg:pt-2">
            <div class="p-4 md:p-6 lg:p-8">
                <div class="mx-auto max-w-[1600px] space-y-6">
                    @if (session('success'))
                        <div class="rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-4 py-3 text-sm font-semibold text-emerald-800 flex items-center gap-2">
                            <i data-lucide="check-circle" class="w-5 h-5"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="rounded-2xl border border-rose-400/20 bg-rose-500/10 px-4 py-3 text-sm font-semibold text-rose-200 flex items-center gap-2">
                            <i data-lucide="alert-circle" class="w-5 h-5"></i>
                            {{ session('error') }}
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>
        </main>
    </div>

    {{-- Footer --}}
    <footer class="fixed bottom-0 left-0 right-0 lg:right-72 bg-slate-900/90 backdrop-blur-xl border-t border-white/10 py-3 px-4 z-30">
        <div class="flex flex-col md:flex-row items-center justify-center gap-2">
            <p class="text-xs text-slate-400">Copyright © 2025 HR Engine. All rights reserved.</p>
            <span class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500">v1.0</span>
        </div>
    </footer>

    {{-- Mobile spacer --}}
    <div class="h-12 lg:hidden"></div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- ==========================================
    السكربتات الخارجية (Lucide)
    ========================================== --}}
    <script src="https://unpkg.com/lucide@latest"></script>

    {{-- ==========================================
    السكربتات المخصصة للصفحة
    ========================================== --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ==========================================
            // تهيئة أيقونات Lucide
            // ==========================================
            try {
                if (window.lucide) {
                    window.lucide.createIcons();
                }
            } catch (e) {
                console.error('lucide icons init failed', e);
            }

            // ==========================================
            // التحكم في القائمة الجانبية للهواتف (Mobile Sidebar)
            // مهم: هذا القسم معزول بـ try/catch مستقل ويُنفّذ أولاً حتى لو
            // فشل أي قسم آخر (مثل الثيم)، يبقى زر فتح/إغلاق القائمة شغّالاً.
            // ==========================================
            try {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebarOverlay');
                const toggleBtn = document.getElementById('mobileMenuToggle');
                const closeBtn = document.getElementById('closeSidebarMobile');

                function openSidebar() {
                    if (!sidebar) return;
                    sidebar.classList.remove('translate-x-full');
                    if (overlay) overlay.classList.remove('hidden');
                    document.body.classList.add('sidebar-open');
                }

                function closeSidebar() {
                    if (!sidebar) return;
                    sidebar.classList.add('translate-x-full');
                    if (overlay) overlay.classList.add('hidden');
                    document.body.classList.remove('sidebar-open');
                }

                if (toggleBtn) {
                    toggleBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        openSidebar();
                    });
                }

                if (closeBtn) {
                    closeBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        closeSidebar();
                    });
                }

                if (overlay) {
                    overlay.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        closeSidebar();
                    });
                }

                window.addEventListener('popstate', function() {
                    if (window.innerWidth < 1024) closeSidebar();
                });

                window.addEventListener('resize', function() {
                    if (window.innerWidth >= 1024) closeSidebar();
                });

                if (sidebar) {
                    sidebar.querySelectorAll('a').forEach(function(link) {
                        link.addEventListener('click', function() {
                            if (window.innerWidth < 1024) {
                                setTimeout(closeSidebar, 150);
                            }
                        });
                    });
                }
            } catch (e) {
                console.error('mobile sidebar init failed', e);
            }

            // ==========================================
            // نظام الثيمات (Light/Dark Mode)
            // ==========================================
            try {
                const html = document.documentElement;

                let savedTheme = 'dark';
                try {
                    savedTheme = localStorage.getItem('theme') || 'dark';
                } catch (storageErr) {
                    console.warn('localStorage غير متاح، سيتم استخدام الثيم الداكن كافتراضي', storageErr);
                }

                function applyTheme(theme) {
                    const isDark = theme === 'dark';
                    html.setAttribute('data-theme', theme);
                    html.classList.toggle('dark', isDark);
                    try {
                        localStorage.setItem('theme', theme);
                    } catch (storageErr) {
                        console.warn('تعذر حفظ الثيم بـ localStorage', storageErr);
                    }

                    document.querySelectorAll('.theme-icon-dark').forEach(el => el.classList.toggle('hidden', !isDark));
                    document.querySelectorAll('.theme-icon-light').forEach(el => el.classList.toggle('hidden', isDark));

                    const themeLabelMobile = document.getElementById('themeLabelMobile');
                    if (themeLabelMobile) themeLabelMobile.textContent = isDark ? 'داكن' : 'فاتح';
                }

                applyTheme(savedTheme);

                document.getElementById('themeToggle')?.addEventListener('click', function() {
                    const current = html.getAttribute('data-theme');
                    applyTheme(current === 'dark' ? 'light' : 'dark');
                });

                document.getElementById('themeToggleMobile')?.addEventListener('click', function() {
                    const current = html.getAttribute('data-theme');
                    applyTheme(current === 'dark' ? 'light' : 'dark');
                });

                document.getElementById('themeToggleDesktop')?.addEventListener('click', function() {
                    const current = html.getAttribute('data-theme');
                    applyTheme(current === 'dark' ? 'light' : 'dark');
                });
            } catch (e) {
                console.error('theme init failed', e);
            }

            // ==========================================
            // Desktop Header Dropdowns
            // ==========================================
            const desktopNotifToggle = document.getElementById('notificationsToggleDesktop');
            const desktopNotifDropdown = document.getElementById('notificationsDropdownDesktop');
            const desktopProfileToggle = document.getElementById('profileToggleDesktop');
            const desktopProfileDropdown = document.getElementById('profileDropdownDesktop');

            function toggleDesktopDropdown(toggle, dropdown, otherToggle, otherDropdown) {
                if (!toggle || !dropdown) return;
                toggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const isHidden = dropdown.classList.contains('hidden');
                    if (otherDropdown) otherDropdown.classList.add('hidden');
                    dropdown.classList.toggle('hidden', !isHidden);
                });
            }

            toggleDesktopDropdown(desktopNotifToggle, desktopNotifDropdown, desktopProfileToggle, desktopProfileDropdown);
            toggleDesktopDropdown(desktopProfileToggle, desktopProfileDropdown, desktopNotifToggle, desktopNotifDropdown);

            document.addEventListener('click', function(e) {
                if (desktopNotifToggle && desktopNotifDropdown && !desktopNotifToggle.contains(e.target) && !desktopNotifDropdown.contains(e.target)) {
                    desktopNotifDropdown.classList.add('hidden');
                }
                if (desktopProfileToggle && desktopProfileDropdown && !desktopProfileToggle.contains(e.target) && !desktopProfileDropdown.contains(e.target)) {
                    desktopProfileDropdown.classList.add('hidden');
                }
            });

            // ==========================================
            // Mobile Sidebar Dropdowns
            // ==========================================
            const notificationsToggle = document.getElementById('notificationsToggle');
            const notificationsDropdown = document.getElementById('notificationsDropdown');
            const profileToggle = document.getElementById('profileToggle');
            const profileDropdown = document.getElementById('profileDropdown');

            if (notificationsToggle && notificationsDropdown) {
                notificationsToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    document.querySelectorAll('.dropdown-open').forEach(function(el) {
                        if (el !== notificationsDropdown) el.classList.add('hidden');
                    });
                    notificationsDropdown.classList.toggle('hidden');
                    if (profileDropdown) profileDropdown.classList.add('hidden');
                });
            }

            if (profileToggle && profileDropdown) {
                profileToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    profileDropdown.classList.toggle('hidden');
                    if (notificationsDropdown) notificationsDropdown.classList.add('hidden');
                });
            }

            document.addEventListener('click', function(e) {
                if (notificationsToggle && notificationsDropdown) {
                    if (!notificationsToggle.contains(e.target) && !notificationsDropdown.contains(e.target)) {
                        notificationsDropdown.classList.add('hidden');
                    }
                }
                if (profileToggle && profileDropdown) {
                    if (!profileToggle.contains(e.target) && !profileDropdown.contains(e.target)) {
                        profileDropdown.classList.add('hidden');
                    }
                }
            });

            // ==========================================
            // تأكيد الحذف (Delete Confirmation)
            // ==========================================
            // إضافة تأكيد لكل نموذج يحوي DELETE method
            document.querySelectorAll('form[method="POST"]').forEach(function(form) {
                if (form.querySelector('input[name="_method"][value="DELETE"]')) {
                    form.onsubmit = function() {
                        return confirm('هل أنت متأكد من تنفيذ هذا الإجراء؟ لا يمكن التراجع بعده.');
                    };
                }
            });
        });
    </script>
</body>
</html>
