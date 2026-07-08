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
    {{-- Tailwind CSS via CDN (للنماذج الأولية) --}}
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    {{-- ==========================================
    الملفات المحلية (Vite)
    ========================================== --}}
    {{-- تجميع وتحميل ملفات CSS و JavaScript الخاصة بالمشروع --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen antialiased" dir="rtl">

    {{-- ==========================================
    رأس الصفحة للأجهزة المحمولة (Mobile Header)
    ========================================== --}}
    <header class="lg:hidden  top-0 right-0 left-0 h-16 bg-slate-900/90 backdrop-blur-xl border-b border-white/10 z-40 flex items-center justify-between px-4">
        <div class="flex items-center gap-3">
            {{-- زر فتح القائمة الجانبية --}}
            <button id="mobileMenuToggle" class="p-2 rounded-xl bg-slate-800 hover:bg-slate-700 transition-colors">
                <i data-lucide="menu" class="w-5 h-5 text-white"></i>
            </button>
            <div>
                <p class="text-sm font-black text-white">HR Engine</p>
                <p class="text-[10px] text-slate-400">نظام الموارد البشرية</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            {{-- زر تبديل الثيم في الموبايل --}}
            <button id="themeToggleMobile" class="p-2 rounded-xl bg-slate-800 hover:bg-slate-700 transition-colors">
                <i data-lucide="sun" class="w-4 h-4 text-amber-400 theme-icon-dark hidden"></i>
                <i data-lucide="moon" class="w-4 h-4 text-slate-300 theme-icon-light hidden"></i>
                <span class="text-xs font-bold text-white" id="themeLabelMobile">داكن</span>
            </button>
        </div>
    </header>

    {{-- ==========================================
    طبقة الخلفية المعتمة (Overlay) للقائمة الجانبية
    ========================================== --}}
    <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 hidden lg:hidden"></div>

    <div class="flex min-h-screen">
        {{-- ==========================================
        القائمة الجانبية (Sidebar)
        ========================================== --}}
        <aside id="sidebar" class="fixed top-0 right-0 h-full w-72 bg-gradient-to-b from-slate-900 to-slate-800 text-white z-50 transform translate-x-full lg:translate-x-0 transition-transform duration-300">
            {{-- منطقة الشعار --}}
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
                    {{-- زر إغلاق القائمة للهواتف --}}
                    <button id="closeSidebarMobile" class="lg:hidden p-2 rounded-xl hover:bg-slate-700/50 transition-colors">
                        <i data-lucide="x" class="w-5 h-5 text-slate-300"></i>
                    </button>
                </div>
            </div>

            {{-- ==========================================
            قائمة التنقل (Navigation)
            ========================================== --}}
            <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
                                    {{-- تعريف عناصر القائمة الأساسية --}}

                @php
                    $userRole = optional(auth()->user()->role)->role_name;
                    $isAdminRole = in_array($userRole, ['admin', 'hr', 'manager'], true);

                    $nav = [
                        ['label' => 'الرئيسية', 'route' => 'dashboard', 'icon' => 'home', 'roles' => ['all']],
                        // تصحيح: الموظف العادي يذهب لصفحة حضوره الشخصية، والإداري لصفحة إدارة الحضور الكاملة
                        ['label' => 'الدوام والحضور', 'route' => $isAdminRole ? 'attendance.index' : 'my.attendance', 'icon' => 'clock', 'roles' => ['all']],
                        ['label' => 'الإجازات والطلبات', 'route' => 'my.requests.index', 'icon' => 'calendar', 'roles' => ['employee', 'admin', 'hr', 'manager']],
                        ['label' => 'الوثائق', 'route' => 'my.documents', 'icon' => 'file-text', 'roles' => ['employee', 'admin', 'hr', 'manager']],
                        // تصحيح: هذه الصفحات محمية إدارياً على مستوى السيرفر (role:admin,hr,manager)
                        // لذلك يجب أن تظهر بالقائمة فقط لهذه الأدوار، وإلا يأخذ الموظف خطأ 403 عند الضغط
                        ['label' => 'الرواتب', 'route' => 'payroll.index', 'icon' => 'banknote', 'roles' => ['admin', 'hr', 'manager']],
                        ['label' => 'الأقسام', 'route' => 'departments.index', 'icon' => 'building', 'roles' => ['all']],
                        ['label' => 'الأجهزة', 'route' => 'devices.index', 'icon' => 'laptop', 'roles' => ['admin', 'hr', 'manager']],
                        ['label' => 'الورديات', 'route' => 'shifts.index', 'icon' => 'book', 'roles' => ['admin', 'hr', 'manager']],
                        ['label' => 'الموظفين', 'route' => 'employees.index', 'icon' => 'user', 'roles' => ['admin', 'hr', 'manager']],
                        // تصحيح: كان المفتاح 'calander' بدل 'roles' بالخطأ، وهذا يسبب خطأ Undefined array key عند تفعيل فحص الصلاحيات
                        ['label' => 'الإجازات', 'route' => 'holidays.index', 'icon' => 'calendar-days', 'roles' => ['admin', 'hr', 'manager']],
                        ['label' => 'التقرير', 'route' => 'reports.index', 'icon' => 'bar-chart', 'roles' => ['admin', 'hr', 'manager']],
                    ];
                @endphp
                    {{-- تحديد دور المستخدم الحالي --}}

                {{-- عرض عناصر القائمة حسب الصلاحيات --}}
                @foreach ($nav as $item)
                    @php($hasAccess = in_array('all', $item['roles']) || in_array($userRole, $item['roles']))
                    @if($hasAccess)
                        <a href="{{ route($item['route']) }}"
                           class="flex items-center gap-3 px-4 py-1 rounded-xl transition-all duration-200 {{ request()->routeIs($item['route']) ? 'bg-gradient-to-l from-blue-600 to-teal-500 text-white shadow-lg' : 'text-slate-300 hover:bg-slate-700/50 hover:text-white' }}">
                            <i data-lucide="{{ $item['icon'] }}" class="w-5 h-5"></i>
                            <span class="font-medium">{{ $item['label'] }}</span>
                        </a>
                    @endif
                @endforeach



            </nav>

            {{-- ==========================================
            المنطقة السفلية في القائمة (مستخدم + إشعارات + ثيم)
            ========================================== --}}
            <div class="p-4 border-t border-white/10 space-y-2">
                {{-- زر تبديل الثيم --}}
                <button id="themeToggle" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 text-slate-300 hover:bg-slate-700/50 hover:text-white">
                    <i data-lucide="sun" class="w-5 h-5 theme-icon-dark hidden"></i>
                    <i data-lucide="moon" class="w-5 h-5 theme-icon-light hidden"></i>
                    <span class="font-medium" id="themeLabel">تبديل المظهر</span>
                </button>

                {{-- ==========================================
                قائمة الإشعارات (Notifications)
                ========================================== --}}
                @php($latestRequests = \App\Models\HrTransaction::query()->where('employee_id', auth()->user()?->employee?->id)->latest()->take(5)->get())
                <div class="relative">
                    <button id="notificationsToggle" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 text-slate-300 hover:bg-slate-700/50 hover:text-white">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                        <span class="font-medium">الإشعارات</span>
                        @php($unread = $latestRequests->where('status', 'pending')->count())
                        @if($unread > 0)
                            <span class="mr-auto bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $unread }}</span>
                        @endif
                    </button>
                    {{-- القائمة المنسدلة للإشعارات --}}
                    <div id="notificationsDropdown" class="hidden absolute bottom-full left-0 w-72 bg-slate-800 border border-white/10 rounded-2xl shadow-2xl mb-2 overflow-hidden z-50">
                        <div class="p-3 border-b border-white/10">
                            <p class="text-sm font-bold text-white">آخر الطلبات</p>
                        </div>
                        <div class="max-h-64 overflow-y-auto">
                            @forelse($latestRequests as $req)
                                <a href="{{ route('my.requests.index') }}" class="block px-4 py-3 hover:bg-slate-700/50 transition-colors border-b border-white/5 last:border-0">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-semibold text-white">{{ match($req->transaction_type) { 'leave' => 'إجازة', 'permission' => 'إذن', 'promotion' => 'ترقية', 'penalty' => 'جزاء', 'transfer' => 'نقل', default => $req->transaction_type } }}</span>
                                        <span class="text-[10px] px-2 py-0.5 rounded-full {{ $req->status === 'pending' ? 'bg-amber-500/20 text-amber-300' : ($req->status === 'approved' ? 'bg-emerald-500/20 text-emerald-300' : 'bg-rose-500/20 text-rose-300') }}">{{ $req->status }}</span>
                                    </div>
                                    <p class="text-xs text-slate-400 mt-1">{{ $req->start_date_time?->format('Y-m-d') ?? '—' }}</p>
                                </a>
                            @empty
                                <div class="px-4 py-6 text-center text-slate-400 text-sm">لا توجد طلبات حالياً</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- ==========================================
                قائمة الملف الشخصي (Profile)
                ========================================== --}}
                <div class="relative">
                    <button id="profileToggle" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 text-slate-300 hover:bg-slate-700/50 hover:text-white">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center shrink-0">
                            <span class="text-xs font-black text-white">{{ strtoupper(substr(auth()->user()->email ?? 'U', 0, 1)) }}</span>
                        </div>
                        <div class="flex-1 min-w-0 text-right">
                            <p class="truncate text-sm font-black text-white">{{ auth()->user()->name ?? auth()->user()->email }}</p>
                            <p class="text-[10px] font-semibold text-slate-400">{{ optional(auth()->user()->role)->role_name ?? 'موظف' }}</p>
                        </div>
                        <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400"></i>
                    </button>
                    {{-- القائمة المنسدلة للملف الشخصي --}}
                    <div id="profileDropdown" class="hidden absolute bottom-full left-0 w-64 bg-slate-800 border border-white/10 rounded-2xl shadow-2xl mb-2 overflow-hidden z-50">
                        <div class="p-3 border-b border-white/10">
                            <p class="text-sm font-bold text-white">الملف الشخصي</p>
                        </div>
                        <div class="p-2">
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-slate-700/50 transition-colors text-white">
                                <i data-lucide="user" class="w-4 h-4 text-slate-400"></i>
                                <span class="text-sm">بيانات الحساب</span>
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="mt-1">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-red-500/10 transition-colors text-red-400">
                                    <i data-lucide="log-out" class="w-4 h-4"></i>
                                    <span class="text-sm">تسجيل الخروج</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        {{-- ==========================================
        المحتوى الرئيسي (Main Content)
        ========================================== --}}
        <main class="flex-1 w-full lg:mr-72 pt-16 lg:pt-0">
            <div class="p-4 md:p-6 lg:p-8">
                <div class="mx-auto max-w-[1600px] space-y-6">
                    {{-- عرض رسالة نجاح (Session Flash) --}}
                    @if (session('success'))
                        <div class="mt-10 rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-4 py-3 text-sm font-semibold text-emerald-800 flex items-center gap-2">
                            <i data-lucide="check-circle" class="w-5 h-5"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- عرض رسالة خطأ (Session Flash) --}}
                    @if (session('error'))
                        <div class="mt-10 rounded-2xl border border-rose-400/20 bg-rose-500/10 px-4 py-3 text-sm font-semibold text-rose-200 flex items-center gap-2">
                            <i data-lucide="alert-circle" class="w-5 h-5"></i>
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- هنا يتم حقن محتوى الصفحات الفرعية --}}
                    @yield('content')
                </div>
            </div>
        </main>
    </div>

    {{-- ==========================================
    الفوتر (Footer) الثابت
    ========================================== --}}
    <footer class="bottom-0 left-0 right-0 lg:right-72 bg-slate-900/90 backdrop-blur-xl border-t border-white/10 py-3 px-4 z-30">
        <div class="flex flex-col md:flex-row items-center justify-center gap-2">
            <p class="text-xs text-slate-400">Copyright © 2025 HR Engine. All rights reserved.</p>
            <div class="flex items-center gap-3">
                <span class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500">v1.0</span>
            </div>
        </div>
    </footer>

    {{-- مسافة للفوتر الثابت في الموبايل --}}
    <div class="h-12 lg:hidden"></div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- ==========================================
    السكربتات الخارجية (Alpine.js & Lucide)
    ========================================== --}}
    <script src="https://unpkg.com/alpinejs@3.10.5/dist/cdn.min.js" defer></script>
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
                    html.setAttribute('data-theme', theme);
                    try {
                        localStorage.setItem('theme', theme);
                    } catch (storageErr) {
                        console.warn('تعذر حفظ الثيم بـ localStorage', storageErr);
                    }

                    const isDark = theme === 'dark';
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
            } catch (e) {
                console.error('theme init failed', e);
            }

            // ==========================================
            // التحكم في قائمة الإشعارات (Notifications)
            // ==========================================
            const notificationsToggle = document.getElementById('notificationsToggle');
            const notificationsDropdown = document.getElementById('notificationsDropdown');

            if (notificationsToggle && notificationsDropdown) {
                notificationsToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    // إغلاق أي قوائم منسدلة مفتوحة أخرى
                    document.querySelectorAll('.dropdown-open').forEach(function(el) {
                        if (el !== notificationsDropdown) {
                            el.classList.add('hidden');
                        }
                    });
                    notificationsDropdown.classList.toggle('hidden');
                    // إغلاق قائمة الملف الشخصي إذا كانت مفتوحة
                    if (profileDropdown) {
                        profileDropdown.classList.add('hidden');
                    }
                });
            }

            // ==========================================
            // التحكم في قائمة الملف الشخصي (Profile)
            // ==========================================
            const profileToggle = document.getElementById('profileToggle');
            const profileDropdown = document.getElementById('profileDropdown');

            if (profileToggle && profileDropdown) {
                profileToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    profileDropdown.classList.toggle('hidden');
                    // إغلاق قائمة الإشعارات إذا كانت مفتوحة
                    if (notificationsDropdown) {
                        notificationsDropdown.classList.add('hidden');
                    }
                });
            }

            // ==========================================
            // إغلاق القوائم المنسدلة عند النقر خارجها
            // ==========================================
            document.addEventListener('click', function(e) {
                // إغلاق قائمة الإشعارات
                if (notificationsToggle && notificationsDropdown) {
                    if (!notificationsToggle.contains(e.target) && !notificationsDropdown.contains(e.target)) {
                        notificationsDropdown.classList.add('hidden');
                    }
                }
                // إغلاق قائمة الملف الشخصي
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
