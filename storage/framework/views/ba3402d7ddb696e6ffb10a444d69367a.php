<!DOCTYPE html>
<html lang="ar" dir="rtl" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', config('app.name', 'HR Engine')); ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.468.0/lucide.min.css">

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="min-h-screen antialiased" dir="rtl">

    <div class="fixed top-4 left-4 z-50">
        <button id="themeToggle" class="p-3 rounded-xl bg-slate-800/80 hover:bg-slate-700 transition-colors shadow-lg">
            <i data-lucide="sun" class="w-5 h-5 theme-icon-dark hidden"></i>
            <i data-lucide="moon" class="w-5 h-5 theme-icon-light hidden"></i>
        </button>
    </div>

    <div class="flex min-h-screen w-full flex-col">
        <div class="flex flex-1 items-center justify-center p-4">
            <div class="w-full max-w-md">
                <div class="rounded-[32px] border border-slate-200/60 bg-white/70 p-8 glass-shell backdrop-blur-xl dark:border-white/10 dark:bg-slate-900/60">
                    <div class="text-center mb-8">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-teal-400 mb-4 shadow-lg">
                            <i data-lucide="briefcase" class="w-7 h-7 text-white"></i>
                        </div>
                        <h1 class="text-2xl font-black text-slate-900 dark:text-white">HR Engine</h1>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">نظام الموارد البشرية</p>
                    </div>

                    <?php echo $__env->yieldContent('content'); ?>
                </div>

                <p class="text-center text-xs text-slate-400 dark:text-slate-500 mt-6">
                    © <?php echo e(date('Y')); ?> HR Engine. All rights reserved.
                </p>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/lucide@latest"></script>

    <script>
        function togglePassword(inputId, btn) {
            const input = document.getElementById(inputId);
            if (!input) return;

            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';

            const icon = btn.querySelector('svg') || btn.querySelector('i');
            if (icon) {
                icon.setAttribute('data-lucide', isPassword ? 'eye-off' : 'eye');
                try { if (window.lucide) window.lucide.createIcons(); } catch (e) { console.warn('lucide refresh failed', e); }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            try {
                if (window.lucide) {
                    window.lucide.createIcons();
                }
            } catch (e) {
                console.error('lucide icons init failed', e);
            }

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
                }

                applyTheme(savedTheme);

                document.getElementById('themeToggle')?.addEventListener('click', function() {
                    const current = html.getAttribute('data-theme');
                    applyTheme(current === 'dark' ? 'light' : 'dark');
                });
            } catch (e) {
                console.error('theme init failed', e);
            }
        });
    </script>
</body>
</html>
<?php /**PATH C:\Users\DELL\Documents\Almonkez\employer_mange\resources\views/layouts/auth.blade.php ENDPATH**/ ?>